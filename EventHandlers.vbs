Private Const ADMIN = "Administrator"
Private Const PASSWORD = "supersecretpassword"

'	Credit to SorenR
Function Include(sInstFile)
   Dim f, s, oFSO
   Set oFSO = CreateObject("Scripting.FileSystemObject")
   On Error Resume Next
   If oFSO.FileExists(sInstFile) Then
      Set f = oFSO.OpenTextFile(sInstFile)
      s = f.ReadAll
      f.Close
      ExecuteGlobal s
   End If
   On Error Goto 0
End Function

'	Credit to SorenR
Function GetDatabaseObject()
   Dim oApp : Set oApp = CreateObject("hMailServer.Application")
   Call oApp.Authenticate(ADMIN, PASSWORD)
   Set GetDatabaseObject = oApp.Database
End Function

'	Credit 99.9% to SorenR
Function AccRejDB(xStringport, xPort, xEvent, xAccRej, xReason, xIPAddress, xHELO)
   ' Find VbsJson.vbs here: https://github.com/eklam/VbsJson
   Include("C:\Program Files (x86)\hMailServer\Events\VbsJson.vbs")
   Dim ReturnCode, Json, oGeoip, oXML
   Set Json = New VbsJson
   On Error Resume Next
   Set oXML = CreateObject ("Msxml2.XMLHTTP.3.0")
   oXML.Open "GET", "http://ip-api.com/json/" & xIPAddress, False
   oXML.Send
   Set oGeoip = Json.Decode(oXML.responseText)
   ReturnCode = oXML.Status
   On Error Goto 0

   Dim strSQL, oDB : Set oDB = GetDatabaseObject
   strSQL = "INSERT INTO hm_accrej (timestamp, stringport, port, event, accrej, reason, ipaddress, country, helo) VALUES (NOW(),'" & xStringport & "','" & xPort & "','" & xEvent & "','" & xAccRej & "','" & xReason & "','" & xIPAddress & "','" & oGeoip("country") & "','" & xHELO & "');"
   Call oDB.ExecuteSQL(strSQL)
End Function

'******************************************************************************************************************************
'********** hMailServer Triggers                                                                                     **********
'******************************************************************************************************************************

Sub OnHELO(oClient)

	'	Exclude local LAN & Backup from test
	If (Left(oClient.IPAddress, 8) = "192.168.") Then Exit Sub
	If (Left(oClient.IPAddress, 9) = "127.0.0.1") Then Exit Sub
	If (Left(oClient.IPAddress, 12) = "184.105.182.") Then Exit Sub

	Dim strPort
	strPort = Trim(Mid("SMTP POP  IMAP SMTPSSUBM IMAPSPOPS ", InStr("25   110  143  465  587  993  995  ", oClient.Port), 5))

	If YourFilter Then
		'
		' filter code to accept connection (my example below taken from "GeoIP" filter)
		'
		Call AccRejDB(strPort, oClient.Port, "OnHELO", "Accepted", "GeoIP", oClient.IPAddress, oClient.HELO)
	Else
		'
		' filter code to reject connection (my example below taken from "GeoIP" filter)
		'
		Call AccRejDB(strPort, oClient.Port, "OnHELO", "REJECTED", "GeoIP", oClient.IPAddress, oClient.HELO)
	End If

End Sub
