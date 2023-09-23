Option Explicit

Private Const hMSPASSWORD = "supersecretpassword"     'hMailServer Administrator password
Private Const hMSdbPW = "supersecretpassword"         'hMailServer MySQL database password

Function Lookup(strRegEx, strMatch) : Lookup = False
	With CreateObject("VBScript.RegExp")
		.Pattern = strRegEx
		.Global = False
		.MultiLine = True
		.IgnoreCase = True
		If .Test(strMatch) Then Lookup = True
	End With
End Function

Function oLookup(strRegEx, strMatch, bGlobal)
	If strRegEx = "" Then strRegEx = StrReverse(strMatch)
	With CreateObject("VBScript.RegExp")
		.Pattern = strRegEx
		.Global = bGlobal
		.MultiLine = True
		.IgnoreCase = True
		Set oLookup = .Execute(strMatch)
	End With
End Function

Function GetDatabaseObject()
	Dim oApp : Set oApp = CreateObject("hMailServer.Application")
	Call oApp.Authenticate("Administrator", hMSPASSWORD)
	Set GetDatabaseObject = oApp.Database
End Function

Function EscapeMySQLSpecialChars(strStr)
	strStr = Replace(strStr, Chr(39), "\" & Chr(39)) 	'single quote '
    EscapeMySQLSpecialChars = strStr
End Function

Function LogReason(xIPAddress, xPort, xAccRej, xReason, xPTR, xHELO, xEvent, xMsgID)
	Dim Match, Matches, strRegEx
	Dim m_CountryCode, m_CountryName
	Dim strSQL, strSQLIP
	DIM oDB : Set oDB = GetDatabaseObject
	
	strRegEx = "127\.0\.0\.1|192\.168\.1\.[0-9]{1,3}"
	If Lookup(strRegEx, xIPAddress) Then
		m_CountryName = "LOCAL"
	Else
		Call GeoIPLookup(xIPAddress, m_CountryCode, m_CountryName)
	End If

	strSQL = "INSERT INTO hm_log_smtp (ip,port,acc,event,reason,ptr,helo,country,msgid) VALUES (INET6_ATON('" & xIPAddress & "'),'" & xPort & "','" & xAccRej & "','" & xEvent & "','" & xReason & "','" & xPTR & "','" & xHELO & "','" & m_CountryName & "','" & xMsgID & "');"
	Call oDB.ExecuteSQL(strSQL)

	If xAccRej = 1 Then
		strSQLIP = 	"INSERT INTO hm_log_ip (ipaddress,lastport,country,countrycode,lastreason,lastevent,lastacc) " & _ 
					"VALUES (INET6_ATON('" & xIPAddress & "'),'" & xPort & "','" & m_CountryName & "','" & m_CountryCode & "','" & xReason & "','" & xEvent & "','" & xAccRej & "') " & _
					"ON DUPLICATE KEY UPDATE hitsacc = (hitsacc+1), lasthit = NOW(), lastreason = '" & xReason & "', lastport = '" & xPort & "', lastacc = '" & xAccRej & "', lastevent = '" & xEvent & "';"
	Else
		strSQLIP = 	"INSERT INTO hm_log_ip (ipaddress,lastport,country,countrycode,lastreason,lastevent,lastacc) " & _ 
					"VALUES (INET6_ATON('" & xIPAddress & "'),'" & xPort & "','" & m_CountryName & "','" & m_CountryCode & "','" & xReason & "','" & xEvent & "','" & xAccRej & "') " & _
					"ON DUPLICATE KEY UPDATE hitsrej = (hitsrej+1), lasthit = NOW(), lastreason = '" & xReason & "', lastport = '" & xPort & "', lastacc = '" & xAccRej & "', lastevent = '" & xEvent & "';"
	End If
	Call oDB.ExecuteSQL(strSQLIP)
End Function

Function LogLogon(xIPAddress, xPort, xAccRej, xReason, xUsername, xCountry)
	Dim Match, Matches, strRegEx
	Dim strSQL
	DIM oDB : Set oDB = GetDatabaseObject

	strSQL = "INSERT INTO hm_log_logon (ip, port, acc, country, username, reason) " & _
			 "VALUES (INET6_ATON('" & xIPAddress & "'),'" & xPort & "','" & xAccRej & "','" & xCountry & "','" & xUsername & "','" & xReason & "');"
	Call oDB.ExecuteSQL(strSQL)
End Function

Function LogAttributes(xMsgID, xItem, xValue)
	Dim Match, Matches, strRegEx
	Dim strSQL
	DIM oDB : Set oDB = GetDatabaseObject
	xValue = EscapeMySQLSpecialChars(xValue)
	strSQL = "INSERT INTO hm_log_attr (msgid, item, value) VALUES ('" & xMsgID & "','" & xItem & "','" & xValue & "');"
	Call oDB.ExecuteSQL(strSQL)
End Function

Function LogMessageAttributes(oMessage, oClient, messageID)
	Dim m, k, j, strRegEx, Match, Matches
	
	REM - Log spam and virus status
	If oMessage.HeaderValue("X-Spam-Status") <> "" Then Call LogAttributes(messageID, "X-Spam-Status", oMessage.HeaderValue("X-Spam-Status"))
	If oMessage.HeaderValue("X-Spam-Virus") <> "" Then Call LogAttributes(messageID, "X-Spam-Virus", oMessage.HeaderValue("X-Spam-Virus"))

	REM - Insert received headers into log db
	For m = 0 To oMessage.Headers.Count-1
		If oMessage.Headers(m).Name = "Received" Then Call LogAttributes(messageID, "Received", oMessage.Headers(m).Value)
	Next

	REM - Insert List-Unsubscribe (mailto) into log db
	If oMessage.HeaderValue("List-Unsubscribe") <> "" Then
		strRegEx = "([^\<]*?)(mailto:[\s\S]*?)(?=\>)"
		Set Matches = oLookup(strRegEx, oMessage.HeaderValue("List-Unsubscribe"), True)
		If Matches.Count > 0 Then
			For Each Match In Matches
				Call LogAttributes(messageID, "List-Unsubscribe", Mid(Trim(Match.Value), 8))
			Next
		End If
	End If

	REM - Get all recipients
	If oMessage.Recipients.Count > 1 Then
		For k = 1 To oMessage.Recipients.Count-1
			Call LogAttributes(messageID, "Recipient " & k+1, oMessage.Recipients(k).Address)
		Next
	End If

	REM - Insert Message SPAM attributes into Log db
	If oMessage.HeaderValue("X-hMailServer-Reason-Score") <> "" Then

		REM - Insert spam items into db
		If oMessage.HeaderValue("X-Spam-Flag") <> "" Then Call LogAttributes(messageID, "X-Spam-Flag", oMessage.HeaderValue("X-Spam-Flag"))
		If oMessage.HeaderValue("X-Spam-Score") <> "" Then Call LogAttributes(messageID, "X-Spam-Score", oMessage.HeaderValue("X-Spam-Score"))
		If oMessage.HeaderValue("X-Spam-Report") <> "" Then Call LogAttributes(messageID, "X-Spam-Report", Replace(oMessage.HeaderValue("X-Spam-Report"), "*", vbCrLf & " *"))
		Call LogAttributes(messageID, "X-hMailServer-Reason-Score", oMessage.HeaderValue("X-hMailServer-Reason-Score"))

		REM - Insert spam reasons into log db
		For j = 0 To 9
			If oMessage.HeaderValue("X-hMailServer-Reason-" & j) <> "" Then 
				Call LogAttributes(messageID, "Spam-Reason-" & j, oMessage.HeaderValue("X-hMailServer-Reason-" & j))
			End If
		Next

	End If
End Function

Function LogMessage(oClient, oMessage)
	Dim strSQL, msgText, msgSubject, msgFromHeader, msgToHeader
	Dim oDB : Set oDB = GetDatabaseObject

	If oMessage.HTMLBody = "" Then
		msgText = EscapeMySQLSpecialChars(oMessage.Body)
	Else
		msgText = EscapeMySQLSpecialChars(oMessage.HTMLBody)
	End If
	msgSubject = EscapeMySQLSpecialChars(oMessage.Subject)
	msgFromHeader = EscapeMySQLSpecialChars(oMessage.From)
	msgToHeader = EscapeMySQLSpecialChars(oMessage.To)

	strSQL = "INSERT INTO hm_log_msg (envelopeFrom, headerFrom, envelopeTo, headerTo, subject, message, ip) VALUES ('" & oMessage.FromAddress & "','" & msgFromHeader & "','" & oMessage.Recipients(0).OriginalAddress & "','" & msgToHeader & "','" & msgSubject & "','" & msgText & "',INET6_ATON('" & oClient.IPAddress & "'));"
	Call oDB.ExecuteSQL(strSQL)
End Function

Sub AssignLogMsgID(ByRef msgID)
    Dim oRecord, oConn : Set oConn = CreateObject("ADODB.Connection")
    oConn.Open "Driver={MariaDB ODBC 3.1 Driver}; Server=localhost; Database=hmailserver; User=hmailserver; Password=" & hMSdbPW & ";"
    If oConn.State <> 1 Then
		EventLog.Write( "Sub AssignMsgID - ERROR: Could not connect to database" )
        Exit Sub
    End If

    Set oRecord = oConn.Execute("SELECT MAX(id)+1 AS msgid FROM hm_log_msg")
    Do Until oRecord.EOF
        msgID = oRecord("msgid")
        oRecord.MoveNext
    Loop
    oConn.Close
    Set oRecord = Nothing

	If IsNull(msgID) Then msgID = 1
End Sub

Function LogSpam(oMessage)
	If oMessage.HeaderValue("X-Envelope-Spam") <> "" Then
		If oMessage.HeaderValue("X-Envelope-Spam") = "DELETED SPAM" Then
			Call LogReason (oMessage.HeaderValue("X-Envelope-IPAddress"), oMessage.HeaderValue("X-Envelope-Port"), 0, "Msg_Deleted_As_SPAM", "", "", "Rules_Action", oMessage.HeaderValue("X-hMailServer-LogID"))
		Else 
			Call LogReason (oMessage.HeaderValue("X-Envelope-IPAddress"), oMessage.HeaderValue("X-Envelope-Port"), 0, "Msg_To_SPAM_Folder", "", "", "Rules_Action", oMessage.HeaderValue("X-hMailServer-LogID"))
		End If
	End If
End Function

Function IsLocalDomain(emailAddress)
	On Error Resume Next
	Err.Clear
	Dim oApp, oDomain
	oDomain = Split(emailAddress,"@")(1)
	Set oApp = CreateObject("hMailServer.Application")
	Call oApp.Authenticate("Administrator", hMSPASSWORD)
	If (oApp.Domains.ItemByName(oDomain)).Name = oDomain Then IsLocalDomain = True
	If Err.Number <> 0 Then IsLocalDomain = False
	On Error Goto 0
End Function

Function IsSenderAutoWhitelisted(m_Sender, m_Recipient) : IsSenderAutoWhitelisted = False
	Dim LookBackDays : LookBackDays = 90  'Number of days to look back to see if envelopeFrom was recipient
	Dim MinSent : MinSent = 3             'Min number of messages previously sent to envelopeFrom in order to whitelist envelopeFrom
	Dim CountSent : CountSent = 0
	Dim oRecord, oConn : Set oConn = CreateObject("ADODB.Connection")
	oConn.Open "Driver={MariaDB ODBC 3.1 Driver}; Server=localhost; Database=hmailserver; User=hmailserver; Password=" & hMSdbPW & ";"

	If oConn.State <> 1 Then
		EventLog.Write( "Function IsSenderAutoWhitelisted - ERROR: Could not connect to database" )
		CountSent = 0
		Exit Function
	End If

	Set oRecord = oConn.Execute("SELECT COUNT(*) AS count FROM hm_message_metadata WHERE metadata_dateutc > NOW() - INTERVAL " & LookBackDays & " DAY AND metadata_from REGEXP '" & m_Recipient & "' AND metadata_to REGEXP '" & m_Sender & "';")
	Do Until oRecord.EOF
		CountSent = oRecord("count")
		oRecord.MoveNext
	Loop
	Set oRecord = Nothing
	oConn.Close
	Set oConn = Nothing

	If CInt(CountSent) > MinSent Then IsSenderAutoWhitelisted = True
End Function

Sub OnClientConnect(oClient)

	REM - Exclude Backup-MX & local LAN from test
	If (Left(oClient.IPAddress, 11) = "192.168.10.") Then           ' local LAN
		If Lookup("^(25|465|587)$", oClient.Port) Then              ' SMTP ports
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
		Else     													' non-SMTP Ports - Comment if you only want to log SMTP
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
		End If
		Exit Sub
	End If
	If oClient.IPAddress = "127.0.0.1" Then                         ' localhost
		If Lookup("^(25|465|587)$", oClient.Port) Then              ' SMTP ports
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
		Else     													' non-SMTP Ports - Comment if you only want to log SMTP
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
		End If
		Exit Sub
	End If
	
	REM - All other IPs
	If Lookup ("^(25|465|587)$", oClient.Port) Then					' SMTP ports
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
	Else  															' non-SMTP Ports - Comment if you only want to log SMTP
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Connection", "", "", "OnClientConnect", 0)
	End If

	REM - THE FOLLOWING ARE PROVIDED AS EXAMPLES OF HOW TO LOG CUSTOM ITEMS
	REM - YOU CAN LOG ANYTHING YOU WANT
	REM - DO NOT RELY ON THIS CODE FOR GEOIP OR COMPLAIN THAT IT DOESN'T WORK - IT DOESN'T WORK IN ITS EXAMPLE FORM
	REM - YOU ARE RESPONSIBLE FOR YOUR OWN CODING

	REM	- GeoIP Lookup
	'
	' Your GeoIP code here
	'
	If Not PassYourGeoipTest Then
		REM	- Disconnect 
		Result.Value = 2
		Result.Message = ". 01 This mail server does not accept mail submission from " & CountryName & ". If you believe that this failure is in error, please contact the intended recipient via alternate means."
		Call LogReason (oClient.IPAddress, oClient.Port, 0, "GeoIP", "", "", "OnClientConnect", 0)
		Exit Sub
	End If

End Sub

Sub OnHELO(oClient)

	REM	- Exclude local LAN & Backup from test after recording connection
	If (Left(oClient.IPAddress, 11) = "192.168.10.") Then 
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Record_PTR-HELO", PTR_Record, oClient.HELO, "OnHELO", 0)
		Exit Sub
	End If
	If oClient.IPAddress = "127.0.0.1" Then
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Record_PTR-HELO", PTR_Record, oClient.HELO, "OnHELO", 0)
		Exit Sub
	End If

	Call LogReason (oClient.IPAddress, oClient.Port, 1, "Record_PTR-HELO", PTR_Record, oClient.HELO, "OnHELO", 0)

	REM - THE FOLLOWING ARE PROVIDED AS EXAMPLES OF HOW TO LOG CUSTOM ITEMS. 
	REM - THE CODE FOR THE DYNAMIC WHITELIST/BLACKLIST CAN BE FOUND HERE:
	REM - https://hmailserver.com/forum/viewtopic.php?f=20&t=33602
	REM - HOWEVEVER, YOU CAN LOG ANYTHING YOU WANT.
	REM - DO NOT RELY ON THIS CODE OR COMPLAIN THAT IT DOESN'T WORK - IT DOESN'T WORK IN ITS EXAMPLE FORM
	REM - YOU ARE RESPONSIBLE FOR YOUR OWN CODING

	REM	- Exclude servers with specific EHLO records (Whitelist)
    strRegEx = MyListRegEx(MyListDict, "//Whitelist/HELO")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, oClient.HELO, False)
		For Each oMatch In oMatchCollection
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "WL-HELO", PTR_Record, oClient.HELO, "OnHELO", 0)
			Call MyListStat(MyListDict, oMatch.Value)
			Exit Sub
		Next
	End If

	REM	- Exclude servers with specific PTR records (Whitelist)
    strRegEx = MyListRegEx(MyListDict, "//Whitelist/PTR")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, PTR_Record, False)
		For Each oMatch In oMatchCollection
			Call LogReason (oClient.IPAddress, oClient.Port, 1, "WL-PTR", PTR_Record, oClient.HELO, "OnHELO", 0)
			Call MyListStat(MyListDict, oMatch.Value)
			Exit Sub
		Next
	End If

	REM - Reject specific IP addresses
    strRegEx = MyListRegEx(MyListDict, "//Reject/IPRange")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, oClient.IPAddress, False)
		For Each oMatch In oMatchCollection
			Result.Value = 2
			Result.Message = ". 10 Your access to this mail system has been rejected due to the sending MTA's poor reputation. If you believe that this failure is in error, please contact the intended recipient via alternate means."
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "Rejected_IP_Range", PTR_Record, oClient.HELO, "OnHELO", 0)
			Call MyListStat(MyListDict, oMatch.Value)
			Exit Sub
		Next
	End If

	REM	- Filter connections on dynamic looking records
	REM	- First, split the IPAddress
	a = Split(oClient.IPAddress, ".")
	For i = 0 to 3
	Next
	REM	- Search for dynamic looking PTR
	If PTR_Record <> "" AND IsWhitelisted = 0 Then
		REM	- Search for dynamic looking PTR
		strRegEx = 	"(.*(((?:[0]{0,2})" & a(0) & "|(?:[0]{0,2})" & a(1) & "|(?:[0]{0,2})" & a(2) & "|(?:[0]{0,2})" & a(3) & ")(?:.+)){3}" &_
					"((?:[0]{0,2})" & a(0) & "|(?:[0]{0,2})" & a(1) & "|(?:[0]{0,2})" & a(2) & "|(?:[0]{0,2})" & a(3) & ").+)$"
		If (oClient.Port = 25) Then
			If Lookup(strRegEx, PTR_Record) Then
				Result.Value = 2
				Result.Message = ". 18 Your access to this mail system has been rejected due to the sending MTA's poor reputation. If you believe that this failure is in error, please contact the intended recipient via alternate means."
				Call LogReason (oClient.IPAddress, oClient.Port, 0, "Dynamic_PTR", PTR_Record, oClient.HELO, "OnHELO", 0)
				Exit Sub
			End If
		End If
	End If
	REM	- Search for dynamic looking HELO
	If IsWhitelisted = 0 Then
		strRegEx = 	"(.*(((?:[0]{0,2})" & a(0) & "|(?:[0]{0,2})" & a(1) & "|(?:[0]{0,2})" & a(2) & "|(?:[0]{0,2})" & a(3) & ")(?:.+)){3}" &_
					"((?:[0]{0,2})" & a(0) & "|(?:[0]{0,2})" & a(1) & "|(?:[0]{0,2})" & a(2) & "|(?:[0]{0,2})" & a(3) & ").+)$"
		If (oClient.Port = 25) Then
			If Lookup(strRegEx, oClient.HELO) Then
				Result.Value = 2
				Result.Message = ". 05 Your access to this mail system has been rejected due to the sending MTA's poor reputation. If you believe that this failure is in error, please contact the intended recipient via alternate means."
				Call LogReason (oClient.IPAddress, oClient.Port, 0, "Residential_IP", PTR_Record, oClient.HELO, "OnHELO", 0)
				Exit Sub
			End If
		End If
	End If

	REM	- Deny servers with specific HELO/EHLO greetings
	strRegEx = MyListRegEx(MyListDict, "//Reject/HELO")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, oClient.HELO, False)
		For Each oMatch In oMatchCollection
			Result.Value = 2
			Result.Message = ". 03 Your access to this mail system has been rejected due to the sending MTA's poor reputation. If you believe that this failure is in error, please contact the intended recipient via alternate means."
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "Rejected_HELO", PTR_Record, oClient.HELO, "OnHELO", 0)
			Call MyListStat(MyListDict, oMatch.Value)
			Exit Sub
		Next
	End If

	REM	- Deny servers with specific PTR
	strRegEx = MyListRegEx(MyListDict, "//Reject/PTR")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, oClient.HELO, False)
		For Each oMatch In oMatchCollection
			Result.Value = 2
			Result.Message = ". 03 Your access to this mail system has been rejected due to the sending MTA's poor reputation. If you believe that this failure is in error, please contact the intended recipient via alternate means."
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "Rejected_HELO", PTR_Record, oClient.HELO, "OnHELO", 0)
			Call MyListStat(MyListDict, oMatch.Value)
			Exit Sub
		Next
	End If

End Sub

Sub OnClientLogon(oClient)

	'
	' YOUR GEOIP CODE HERE
	'
	
	REM	- LOG LOGON SUCCESSES AND FAILURES
	If oClient.Authenticated Then
		Call LogLogon(oClient.IPAddress, oClient.Port, 1, "Client_Logon", oClient.Username, m_CountryName)
	Else
		If IsLocalDomain(oClient.Username) Then
			Call LogLogon(oClient.IPAddress, oClient.Port, 0, "Failed_Local_Logon", oClient.Username, m_CountryName)
		Else
			Call LogLogon(oClient.IPAddress, oClient.Port, 0, "Failed_Non-Local_Logon", oClient.Username, m_CountryName)
			EventLog.Write("Failed non-local logon: " & oClient.IPAddress & ":" & oClient.Port)
		End If
	End if

End Sub

Sub OnAcceptMessage(oClient, oMessage)

	REM - !!!WARNING!!! FILTER CODE PROVIDED FOR EXAMPLE ONLY
	REM - YOU WILL NEED TO WORK OUT YOUR OWN CODE FOR ANY TEST THAT RESULTS IN A WANTED LOG ENTRY
	REM - DO NOT RELY ON THIS CODE OR COMPLAIN THAT IT DOESN'T WORK - IT DOESN'T WORK IN ITS EXAMPLE FORM
	REM - YOU ARE RESPONSIBLE FOR YOUR OWN CODING

	REM - Create Message ID
	Call AssignLogMsgID(messageID)
	oMessage.HeaderValue("X-hMailServer-LogID") = messageID
	oMessage.Save

	REM - Insert Message body and attributes into Log db
	Call LogMessage(oClient, oMessage)

	REM - Add X-Envelope... headers
	Call XEnvelope(oMessage, oClient)

	REM - Exclude authenticated users test
	If oClient.Username <> "" Then 
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Client_Authenticated", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
		Call LogMessageAttributes(oMessage, oClient, messageID)
		Exit Sub
	Else 
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Incoming_Message", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
	End If

	REM - Test Whitelist (0 = Not Listed, 1 = Whitelisted)
	Dim IsWhitelisted : IsWhitelisted = Whitelisted(oClient.IPAddress, oClient.Port)
	
	REM - Auto Whitelisting
	If IsSenderAutoWhitelisted(oMessage.FromAddress, oMessage.Recipients(0).OriginalAddress) Then
		Call WhiteList(oMessage, "AutoWhitelist From = '" & oMessage.FromAddress & "'")
		oMessage.HeaderValue("X-hMailServer-AutoWhitelisted") = "YES"
		oMessage.Save
		EventLog.Write("AutoWhitelisted " & oMessage.FromAddress)
		Call LogReason (oClient.IPAddress, oClient.Port, 1, "Auto-Whitelisted", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
		Call LogMessageAttributes(oMessage, oClient, messageID)
		Exit Sub
	End If

	REM - THE FOLLOWING ARE PROVIDED AS EXAMPLES OF HOW TO LOG CUSTOM ITEMS. 
	REM - THE CODE FOR THE DYNAMIC WHITELIST/BLACKLIST CAN BE FOUND HERE:
	REM - https://hmailserver.com/forum/viewtopic.php?f=20&t=33602
	REM - HOWEVEVER, YOU CAN LOG ANYTHING YOU WANT.
	REM - DO NOT RELY ON THIS CODE OR COMPLAIN THAT IT DOESN'T WORK - IT DOESN'T WORK IN ITS EXAMPLE FORM
	REM - YOU ARE RESPONSIBLE FOR YOUR OWN CODING

	REM - RansomWare - Body text/URL check 
    strRegEx = MyListRegEx(MyListDict, "//Ransomware/Bodytxt")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, strMsgBody, False)
		For Each oMatch In oMatchCollection
			Call MyListStat(MyListDict, oMatch.Value)
			Call RansomWare(oMessage)
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "RansomWare", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
		Next
	End If

	REM - Extortion - Body text/URL check 
    strRegEx = MyListRegEx(MyListDict, "//Extortion/Bodytxt")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, strMsgBody, False)
		For Each oMatch In oMatchCollection
			Call MyListStat(MyListDict, oMatch.Value)
			Call RansomWare(oMessage)
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "Extortion", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
		Next
	End If

	REM - Extortion - SpamAssassin X-Spam-Status 
	strRegEx = MyListRegEx(MyListDict, "//Extortion/X-Spam-Status")
	If strRegEx <> "VOID" Then
		Set oMatchCollection = oLookup(strRegEx, oMessage.HeaderValue("X-Spam-Status"), False)
		For Each oMatch In oMatchCollection
			Call MyListStat(MyListDict, oMatch.Value)
			Call RansomWare(oMessage)
			Call LogReason (oClient.IPAddress, oClient.Port, 0, "Extortion", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
		Next
	End If

	REM - Blacklist messages received through GmailAPI 
	For n = 0 To oMessage.Headers.Count-1
		If (oMessage.Headers(n).Name = "Received") Then
			strRegEx = "\bgmailapi\.google\.com\b"
			If Lookup(strRegEx, oMessage.Headers(n).Value) Then
				Call BlackList(oMessage, "Received in GmailAPI", 3)
				Call LogReason (oClient.IPAddress, oClient.Port, 0, "Rcvd_in_GmailAPI", PTR_Record, oClient.HELO, "OnAcceptMessage", messageID)
				Exit For
			End If
		End If
	Next

	REM - Insert Message attributes into Log db (should be dead last of non-reject/disconnect filters in OnAcceptMessage, before any reject/disconnect filters)
	Call LogMessageAttributes(oMessage, oClient, messageID)
	
End Sub

Sub OnDeliverMessage(oMessage)

	REM - Make X-Spam-Report Readable Again 
	If oMessage.HeaderValue("X-Spam-Report") <> "" Then
		oMessage.HeaderValue("X-Spam-Report") = Replace(oMessage.HeaderValue("X-Spam-Report"), "*", vbCrLf & " *") 
		oMessage.Save
	End If

	REM - INSERT MESSAGE ID HEADER INTO EML FILE
	Call LogAttributes(oMessage.HeaderValue("X-hMailServer-LogID"), "hMailServer ID", oMessage.ID)
	oMessage.HeaderValue("X-hMailServer-ID") = oMessage.ID
	oMessage.Save

End Sub

Sub OnDeliveryFailed(oMessage, sRecipient, sErrorMessage)

	REM - LOG DELIVERY FAILURE
	If oMessage.HeaderValue("X-hMailServer-LogID") <> "" Then 
		Call LogAttributes(oMessage.HeaderValue("X-hMailServer-LogID"), "Delivery Failure", "Delivery to " & sRecipient & " failed with error: " & sErrorMessage)
	End If

End Sub