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

<#  Delete and recreate php data file  #>
$TempFolder = "$PSScriptRoot\Temp"
If (-not(Test-Path $TempFolder)) {md $TempFolder}
$StatsDataPHPTemp = "$TempFolder\statsCurrentDataTemp.php"
If (Test-Path $StatsDataPHPTemp) {Remove-Item -Force -Path $StatsDataPHPTemp}
New-Item $StatsDataPHPTemp -ItemType "file"
Write-Output "<?php" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

$Today = (Get-Date).ToString("yyyy-MM-dd")

<#  Today's Connections Dial - Connections  #>
$QueryDTC = "
		SELECT COUNT(ip) AS hits
		FROM hm_log_smtp
		WHERE DATE(timestamp) = DATE(NOW()) AND acc=1 AND reason='Client_Connection';
"
MySQLQuery $QueryDTC | ForEach {
	$hitsC = $_.hits
}
Write-Output "`$hitsC = $hitsC;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Today's Connections Dial - Rejections  #>
$QueryDTR = "
	SELECT COUNT(ip) AS hits
	FROM hm_log_smtp
	WHERE DATE(timestamp) = DATE(NOW()) AND acc=0;
"
MySQLQuery $QueryDTR | ForEach {
	$hitsR = $_.hits
}
Write-Output "`$hitsR = $hitsR;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Today's Connections Dial - Messages  #>
$QueryDTM = "SELECT COUNT(*) AS count FROM hm_log_msg WHERE DATE(timestamp) = DATE(NOW());"
MySQLQuery $QueryDTM | ForEach {
	$hitsM = $_.count
}
Write-Output "`$hitsM = $hitsM;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Accepted Reasons Today bar chart  #>
$QueryART = "
	SELECT reason, COUNT(*) as numhits 
	FROM hm_log_smtp
	WHERE acc=1 AND DATE(timestamp) = DATE(NOW())
	GROUP BY reason
	ORDER BY reason ASC;
"
$dataArrART = @()
$labelArrART = @()
MySQLQuery $QueryART | ForEach {
	$dataArrART += $_.numhits
	$labelArrART += "'$($_.reason)'"
}
Write-Output "`$dataArrART = `"$($dataArrART -Join ",")`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$labelArrART = `"$($labelArrART -Join ",")`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

$QueryCountART = "
	SELECT COUNT(DISTINCT(reason)) as countacc 
	FROM hm_log_smtp
	WHERE acc=1 AND DATE(timestamp) = DATE(NOW());
"
MySQLQuery $QueryCountART | ForEach {
	$countAcc_rows += $_.countacc
}
Write-Output "`$countAcc_rows = $countAcc_rows;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

<#  Rejected Reasons Today bar chart  #>
$QueryRRT = "
	SELECT reason, COUNT(*) as numhits 
	FROM hm_log_smtp
	WHERE acc=0 AND DATE(timestamp) = DATE(NOW())
	GROUP BY reason
	ORDER BY reason ASC;
"
$dataArrRRT = @()
$labelArrRRT = @()
MySQLQuery $QueryRRT | ForEach {
	$dataArrRRT += $_.numhits
	$labelArrRRT += "'$($_.reason)'"
}
Write-Output "`$dataArrRRT = `"$($dataArrRRT -Join ",")`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append
Write-Output "`$labelArrRRT = `"$($labelArrRRT -Join ",")`";" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

$QueryCountRRT = "
	SELECT COUNT(DISTINCT(reason)) as countrej 
	FROM hm_log_smtp
	WHERE acc=0 AND DATE(timestamp) = DATE(NOW());
"
MySQLQuery $QueryCountRRT | ForEach {
	$countRej_rows += $_.countrej
}
Write-Output "`$countRej_rows = $countRej_rows;" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

Write-Output "?>" | Out-File $StatsDataPHPTemp -Encoding ASCII -Append

Copy-Item -Path $StatsDataPHPTemp -Destination "$wwwFolder\statsCurrentData.php"
