<#
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝

.SYNOPSIS


.DESCRIPTION


.FUNCTIONALITY


.NOTES

	
.EXAMPLE


#>

<#  Include required files  #>
Try {
	.("$PSScriptRoot\hMSLogConfig.ps1")
	.("$PSScriptRoot\hMSLogFunctions.ps1")
}
Catch {
	Write-Output "$((Get-Date).ToString(`"yy/MM/dd HH:mm:ss.ff`")) : ERROR : Unable to load supporting PowerShell Scripts : $query `n$($Error[0])" | Out-File "$PSScriptRoot\PSError.log" -Append
}

<###   START SCRIPT   ###>

Add-Type -AssemblyName System.Web


<###   EXPIRE DATA   ###>
<#  Expire Data Log tables  #>
$Query = "
	DELETE FROM hm_log_smtp
	WHERE hm_log_smtp.timestamp < NOW() - INTERVAL $ExpireDataLogDays DAY;
"
MySQLQuery $Query

<#  Expire Message Log tables  #>
$Query = "
	DELETE hm_log_msg,hm_log_attr FROM hm_log_msg 
	INNER JOIN hm_log_attr ON hm_log_msg.id = hm_log_attr.msgid
	WHERE hm_log_msg.timestamp < NOW() - INTERVAL $ExpireMsgLogDays DAY;
"
MySQLQuery $Query

<#  Expire AWStats tables  #>
$Query = "DELETE FROM hm_log_awstats WHERE timestamp < NOW() - INTERVAL $ExpireMsgLogDays DAY;"
MySQLQuery $Query

<#  Expire Message Logon tables  #>
If ($ExpireFailedLogons) {
	$Query = "DELETE FROM hm_log_logon WHERE timestamp < NOW() - INTERVAL $ExpireLogonLogDays DAY;"
} Else {
	$Query = "DELETE FROM hm_log_logon WHERE acc = 1 AND timestamp < NOW() - INTERVAL $ExpireLogonLogDays DAY;"
}
MySQLQuery $Query

<###   CREATE INDEX.PHP DATA   ###>
<#  Delete and recreate php data file  #>
$TempFolder = "$PSScriptRoot\Temp"
If (-not(Test-Path $TempFolder)) {md $TempFolder}
$StatsDataPHPTemp = "$TempFolder\statsDataTemp.php"
If (Test-Path $StatsDataPHPTemp) {Remove-Item -Force -Path $StatsDataPHPTemp}
New-Item $StatsDataPHPTemp -ItemType "file"
Write-Output "<?php" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Today's Connections Dial - Max  #>
$QueryDTC = "
	SELECT	
		ROUND(((COUNT(ip)) * 1.25), -2) AS dailymax,
		DATE(timestamp) AS daily
	FROM hm_log_smtp
	WHERE acc=1 AND reason='Client_Connection' AND DATE(timestamp) < DATE(NOW())
	GROUP BY daily
	ORDER BY dailymax DESC
	LIMIT 1;
"
MySQLQuery $QueryDTC | ForEach {
	$DailyMaxDTC = $_.dailymax
}
If ($DailyMaxDTC -gt 0) {$gaugeMaxC = $DailyMaxDTC} Else {$gaugeMaxC = 100}
$gauge100C = $gaugeMaxC / 1.25
$gauge75C = $gauge100C * 0.75
$gauge50C = $gauge100C * 0.5
$gauge25C = $gauge100C * 0.25

Write-Output "`$gaugeMaxC = $gaugeMaxC;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge100C = $gauge100C;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge75C = $gauge75C;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge50C = $gauge50C;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge25C = $gauge25C;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Today's Connections Dial - Max  #>
$QueryDTR = "
	SELECT	
		ROUND(((COUNT(ip)) * 1.25), -2) AS dailymax,
		DATE(timestamp) AS daily
	FROM hm_log_smtp
	WHERE acc=0 AND DATE(timestamp) < DATE(NOW())
	GROUP BY daily
	ORDER BY dailymax DESC
	LIMIT 1;
"
MySQLQuery $QueryDTR | ForEach {
	$DailyMaxDTR = $_.dailymax
}
If ($DailyMaxDTR -gt 0) {$gaugeMaxR = $DailyMaxDTR} Else {$gaugeMaxR = 100}
$gauge100R = $gaugeMaxR / 1.25
$gauge75R = $gauge100R * 0.75
$gauge50R = $gauge100R * 0.5
$gauge25R = $gauge100R * 0.25

Write-Output "`$gaugeMaxR = $gaugeMaxR;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge100R = $gauge100R;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge75R = $gauge75R;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge50R = $gauge50R;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge25R = $gauge25R;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Today's Connections Dial - Max  #>
$QueryDTM = "
	SELECT	
		ROUND(((COUNT(timestamp)) * 1.25), -1) AS dailymax,
		DATE(timestamp) AS daily
	FROM hm_log_msg 
	WHERE DATE(timestamp) < DATE(NOW())
	GROUP BY daily
	ORDER BY dailymax DESC
	LIMIT 1;
"
MySQLQuery $QueryDTM | ForEach {
	$DailyMaxDTM = $_.dailymax
}
If ($DailyMaxDTM -gt 0) {$gaugeMaxM = $DailyMaxDTM} Else {$gaugeMaxM = 100}
$gauge100M = $gaugeMaxM / 1.25
$gauge75M = $gauge100M * 0.75
$gauge50M = $gauge100M * 0.5
$gauge25M = $gauge100M * 0.25

Write-Output "`$gaugeMaxM = $gaugeMaxM;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge100M = $gauge100M;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge75M = $gauge75M;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge50M = $gauge50M;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$gauge25M = $gauge25M;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Connections Per Day line chart  #>
$QueryCPD = "
	SELECT DATE(timestamp) AS daily, COUNT(ip) AS hits 
	FROM hm_log_smtp
	WHERE DATE(timestamp) < DATE(NOW()) AND acc=1 AND reason='Client_Connection' AND port REGEXP '25|587|465'
	GROUP BY daily ASC;
"
$dataArrCPD = @()
$hitsArrCPD = @()
$dateArrCPD = @()
$iterArrCPD = @()
$iCPD = 1
MySQLQuery $QueryCPD | ForEach {
	$dateCPD = ([DateTime]$_.daily).ToString('yyyy-MM-dd')
	$hitsCPD = $_.hits
	$dataArrCPD += "`"{x: '$dateCPD', y: $hitsCPD}`""
	$hitsArrCPD += $hitsCPD
	$dateArrCPD += "'$dateCPD'"
	$iterArrCPD += $iCPD
	$iCPD++
}
Write-Output "`$dataArrCPD = array($($dataArrCPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$hitsArrCPD = array($($hitsArrCPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$dateArrCPD = array($($dateArrCPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$iterArrCPD = array($($iterArrCPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$iCPD = $iCPD;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Rejections Per Day line chart  #>
$QueryRPD = "
	SELECT DATE(timestamp) AS daily, COUNT(ip) AS hits 
	FROM hm_log_smtp
	WHERE DATE(timestamp) < DATE(NOW()) AND acc=0
	GROUP BY daily ASC
"
$dataArrRPD = @()
$hitsArrRPD = @()
$dateArrRPD = @()
$iterArrRPD = @()
$iRPD = 1
MySQLQuery $QueryRPD | ForEach {
	$dateRPD = ([DateTime]$_.daily).ToString('yyyy-MM-dd')
	$hitsRPD = $_.hits
	$dataArrRPD += "`"{x: '$dateRPD', y: $hitsRPD}`""
	$hitsArrRPD += $hitsRPD
	$dateArrRPD += "'$dateRPD'"
	$iterArrRPD += $iRPD
	$iRPD++
}
Write-Output "`$dataArrRPD = array($($dataArrRPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$hitsArrRPD = array($($hitsArrRPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$dateArrRPD = array($($dateArrRPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$iterArrRPD = array($($iterArrRPD -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$iRPD = $iRPD;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Connections Per Hour bar chart  #>
$QueryCPH = "
	SELECT 
		hour, 
		ROUND(AVG(numhits), 0) AS avghits 
	FROM (
		SELECT 
			DATE(timestamp) AS day, 
			HOUR(timestamp) AS hour, 
			COUNT(*) as numhits 
		FROM hm_log_smtp
		WHERE acc=1 AND reason='Client_Connection'
		GROUP BY DATE(timestamp), HOUR(timestamp)
	) d 
	GROUP BY hour 
	ORDER BY hour ASC;
"
$dataArrCPH = @()
$labelArrCPH = @()
MySQLQuery $QueryCPH | ForEach {
	$hitsCPH = $_.avghits
	$hourCPH = $_.hour
	$dataArrCPH += $hitsCPH
	$labelArrCPH += "'$hourCPH'"
}
Write-Output "`$dataArrCPH = array($($dataArrCPH -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$labelArrCPH = array($($labelArrCPH -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Rejections Per Hour bar chart  #>
$QueryRPH = "
	SELECT 
		hour, 
		ROUND(AVG(numhits), 0) AS avghits 
	FROM (
		SELECT 
			DATE(timestamp) AS day, 
			HOUR(timestamp) AS hour, 
			COUNT(*) as numhits 
		FROM hm_log_smtp
		WHERE acc=0
		GROUP BY DATE(timestamp), HOUR(timestamp)
	) d 
	GROUP BY hour 
	ORDER BY hour ASC;
"
$dataArrRPH = @()
$labelArrRPH = @()
MySQLQuery $QueryRPH | ForEach {
	$hitsRPH = $_.avghits
	$hourRPH = $_.hour
	$dataArrRPH += $hitsRPH
	$labelArrRPH += "'$hourRPH'"
}
Write-Output "`$dataArrRPH = array($($dataArrRPH -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$labelArrRPH = array($($labelArrRPH -Join ","));" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Map Data  #>
$QueryRPD = "
	SELECT 
		country, 
		countrycode, 
		SUM(hitsacc) AS acc, 
		SUM(hitsrej) AS rej, 
		ROUND((SUM(hitsrej) / SUM(hitsacc) * 100),0) AS ratio 
	FROM hm_log_ip 
	WHERE countrycode <> '' 
	GROUP BY countrycode;
"
$mapjson = @()
MySQLQuery $QueryRPD | ForEach {
	$CN = $_.country
	$ECN = [System.Web.HttpUtility]::UrlEncode($_.country)
	$CC = $_.countrycode
	$Acc = $_.acc
	$Rej = $_.rej
	$Ratio = $_.ratio
	$mapjson += "$CC`: {link: 'ip.php?search=$ECN', acc: $Acc, rej: $Rej, ratio: $Ratio}"
}
Write-Output "`$mapjson = `"`{$($mapjson -Join ",")`}`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  User Activity Tables  #>
$QueryCountMsgs = "SELECT COUNT(*) AS count FROM hm_log_msg;"
MySQLQuery $QueryCountMsgs | ForEach {
	$AllMsgCount = $_.count
}

$QueryCountFrom = "
	SELECT envelopeFrom, COUNT(envelopeFrom) AS count
	FROM hm_log_msg
	GROUP BY envelopeFrom
	ORDER BY count DESC
	LIMIT 10;
"
$EnvFromRows = ""
MySQLQuery $QueryCountFrom | ForEach {
	$envelopeFrom = $_.envelopeFrom
	$encodedEnvFrom = [System.Web.HttpUtility]::UrlEncode($_.envelopeFrom)
	$CountEnvFrom = ($_.count).ToString("#,###")
	$PercentOfMsgs = ($_.count / $AllMsgCount).ToString("0.0%")
	$EnvFromRows += "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=$encodedEnvFrom'>$envelopeFrom</a></div>
					<div class='div-table-col right' data-column='Count'>$CountEnvFrom</div>
					<div class='div-table-col right' data-column='Percent'>$PercentOfMsgs</div>
				</div>"
}
Write-Output "`$EnvFromRows = `"$EnvFromRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

$QueryCountTo = "
	SELECT envelopeTo, COUNT(envelopeTo) AS count
	FROM hm_log_msg
	GROUP BY envelopeTo
	ORDER BY count DESC
	LIMIT 10;
"
$EnvToRows = ""
MySQLQuery $QueryCountTo | ForEach {
	$envelopeTo = $_.envelopeTo
	$encodedEnvTo = [System.Web.HttpUtility]::UrlEncode($_.envelopeTo)
	$CountEnvTo = ($_.count).ToString("#,###")
	$PercentOfMsgs = ($_.count / $AllMsgCount).ToString("0.0%")
	$EnvToRows += "
				<div class='div-table-row'>
					<div class='div-table-col truncate' data-column='From'><a href='./messages.php?from=$encodedEnvTo'>$envelopeTo</a></div>
					<div class='div-table-col right' data-column='Count'>$CountEnvTo</div>
					<div class='div-table-col right' data-column='Percent'>$PercentOfMsgs</div>
				</div>"
}
Write-Output "`$EnvToRows = `"$EnvToRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Log Reasons Tables  #>
$QueryCountReasons = "SELECT COUNT(*) AS count FROM hm_log_smtp WHERE acc=1;"
MySQLQuery $QueryCountReasons | ForEach {
	$AllReasonsCount = $_.count
}

$QueryCountAcc = "
	SELECT reason, COUNT(reason) AS count
	FROM hm_log_smtp
	WHERE acc = 1
	GROUP BY reason
	ORDER BY reason ASC;
"
$ReasonAccRows = ""
MySQLQuery $QueryCountAcc | ForEach {
	$ReasAcc = $_.reason
	$CountReasonAcc = ($_.count).ToString("#,###")
	$PercentReasonAcc = ($_.count / $AllReasonsCount).ToString("0.0%")
	$ReasonAccRows += "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=$ReasAcc'>$ReasAcc</a></div>
					<div class='div-table-col right' data-column='Count'>$CountReasonAcc</div>
					<div class='div-table-col right' data-column='Percent'>$PercentReasonAcc</div>
				</div>";
}
Write-Output "`$ReasonAccRows = `"$ReasonAccRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

$QueryCountRej = "
	SELECT reason, COUNT(reason) AS count
	FROM hm_log_smtp
	WHERE acc = 0
	GROUP BY reason
	ORDER BY reason ASC;
"
$ReasonRejRows = ""
MySQLQuery $QueryCountRej | ForEach {
	$ReasRej = $_.reason
	$CountReasonRej = ($_.count).ToString("#,###")
	$PercentReasonRej = ($_.count / $AllReasonsCount).ToString("0.0%")
	$ReasonRejRows += "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Reason'><a href='./data.php?reason=$ReasRej'>$ReasRej</a></div>
					<div class='div-table-col right' data-column='Count'>$CountReasonRej</div>
					<div class='div-table-col right' data-column='Percent'>$PercentReasonRej</div>
				</div>";
}
Write-Output "`$ReasonRejRows = `"$ReasonRejRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Top Ten IPs  #>
$QueryCountIPs = "SELECT SUM(hitsacc + hitsrej) AS hits FROM hm_log_ip;"
MySQLQuery $QueryCountIPs | ForEach {
	$AllIPsCount = $_.hits
}

$QueryTopTenIPs = "
	SELECT 
		TRIM(BOTH '`"' FROM country) AS trimcountry, 
		(hitsacc + hitsrej) AS hits,
		INET6_NTOA(ipaddress) AS ip
	FROM hm_log_ip 
	ORDER BY hits DESC
	LIMIT 10;
"
$TopTenIPsRows = ""
MySQLQuery $QueryTopTenIPs | ForEach {
	$TTIPCountry = $_.trimcountry
	$TTIPHits = ($_.hits).ToString("#,###")
	$TTIPIP = $_.ip
	$EncTTIPIP = [System.Web.HttpUtility]::UrlEncode($_.ip)
	$PercentTTIP = ($_.hits / $AllIPsCount).ToString("0.0%")
	$TopTenIPsRows += "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='IP'><a href='./data.php?search=$EncTTIPIP'>$TTIPIP</a></div>
					<div class='div-table-col' data-column='Country'>$TTIPCountry</div>
					<div class='div-table-col center' data-column='Hits'>$TTIPHits</div>
					<div class='div-table-col center' data-column='Percent'>$PercentTTIP</div>
				</div>";
}
Write-Output "`$TopTenIPsRows = `"$TopTenIPsRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Top Ten Countries  #>
$QueryTopTenCountries = "
	SELECT 
		TRIM(BOTH '`"' FROM country) AS trimcountry,
		SUM(hitsacc + hitsrej) AS sumhits
	FROM hm_log_ip 
	GROUP BY country 
	ORDER BY sumhits DESC
	LIMIT 10
"
$TopTenCountriesRows = ""
MySQLQuery $QueryTopTenCountries | ForEach {
	$TTCCountry = $_.trimcountry
	$EncTTCCountry = [System.Web.HttpUtility]::UrlEncode($_.trimcountry)
	$TTCHits = ($_.sumhits).ToString("#,###")
	$PercentTTC = ($_.sumhits / $AllIPsCount).ToString("0.0%")
	$TopTenCountriesRows += "
				<div class='div-table-row'>
					<div class='div-table-col' data-column='Country'><a href='./data.php?search=$EncTTCCountry'>$TTCCountry</a></div>
					<div class='div-table-col center' data-column='Hits'>$TTCHits</div>
					<div class='div-table-col center' data-column='Percent'>$PercentTTC</div>
				</div>";
}
Write-Output "`$TopTenCountriesRows = `"$TopTenCountriesRows`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

Write-Output "?>" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

Copy-Item -Path $StatsDataPHPTemp -Destination "$wwwFolder\statsData.php"
