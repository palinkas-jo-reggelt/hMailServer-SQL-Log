<#
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝

.SYNOPSIS
	Set up database and scheduled tasks for log website.

.DESCRIPTION
	Set up database and scheduled tasks for log website.


.FUNCTIONALITY


.NOTES
	Make sure config file is properly filled with accurate information.
	
.EXAMPLE


#>

<#  Include required files  #>
Try {
	.("$PSScriptRoot\hMSLogConfig.ps1")
	.("$PSScriptRoot\hMSLogFunctions.ps1")
}
Catch {
	Write-Output "$((Get-Date).ToString(`"yy/MM/dd HH:mm:ss.ff`")) : ERROR : Unable to load supporting PowerShell Scripts : `n$($Error[0])" | Out-File "$PSScriptRoot\PSError.log" -Append
}


<###   CREATE TABLES   ###>

$Query ="
	DROP TABLE IF EXISTS hm_log_attr;
	CREATE TABLE IF NOT EXISTS hm_log_attr (
		id int(11) NOT NULL AUTO_INCREMENT,
		msgid int(11) NOT NULL DEFAULT 0,
		item tinytext DEFAULT NULL,
		value text DEFAULT NULL,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query

$Query ="
	DROP TABLE IF EXISTS hm_log_awstats;
	CREATE TABLE IF NOT EXISTS hm_log_awstats (
		id int(11) NOT NULL AUTO_INCREMENT,
		timestamp datetime NOT NULL,
		sender tinytext NOT NULL,
		recipient tinytext NOT NULL,
		connsender tinytext NOT NULL,
		connrecipient tinytext NOT NULL,
		statuscode int(3) NOT NULL,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query

$Query ="
	DROP TABLE IF EXISTS hm_log_ip;
	CREATE TABLE IF NOT EXISTS hm_log_ip (
		ipaddress varbinary(16) NOT NULL,
		lastport int(3) NOT NULL DEFAULT 0,
		country tinytext DEFAULT NULL,
		countrycode tinytext NOT NULL,
		hitsacc int(11) NOT NULL DEFAULT 1,
		hitsrej int(11) NOT NULL DEFAULT 0,
		lasthit datetime NOT NULL DEFAULT current_timestamp(),
		lastreason tinytext NOT NULL DEFAULT '0',
		lastacc tinyint(1) NOT NULL DEFAULT 0,
		lastevent tinytext DEFAULT NULL,
		PRIMARY KEY (ipaddress)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query

$Query ="
	DROP TABLE IF EXISTS hm_log_logon;
	CREATE TABLE IF NOT EXISTS hm_log_logon (
		id int(11) NOT NULL AUTO_INCREMENT,
		timestamp datetime NOT NULL DEFAULT current_timestamp(),
		ip varbinary(16) NOT NULL,
		port int(4) NOT NULL,
		acc tinyint(1) NOT NULL,
		country tinytext NOT NULL,
		username tinytext NOT NULL,
		reason tinytext NOT NULL,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query

$Query ="
	DROP TABLE IF EXISTS hm_log_msg;
	CREATE TABLE IF NOT EXISTS hm_log_msg (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		timestamp datetime NOT NULL DEFAULT current_timestamp(),
		envelopeFrom tinytext DEFAULT NULL,
		headerFrom tinytext DEFAULT NULL,
		envelopeTo tinytext DEFAULT NULL,
		headerTo text DEFAULT NULL,
		subject tinytext DEFAULT NULL,
		message mediumtext DEFAULT NULL,
		ip varbinary(16) NOT NULL,
		statuscode int(3) DEFAULT 600,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query

$Query ="
	DROP TABLE IF EXISTS hm_log_smtp;
	CREATE TABLE IF NOT EXISTS hm_log_smtp (
		id int(11) NOT NULL AUTO_INCREMENT,
		timestamp timestamp NOT NULL DEFAULT current_timestamp(),
		ip varbinary(16) NOT NULL,
		port int(3) NOT NULL DEFAULT 0,
		acc tinyint(4) NOT NULL,
		event tinytext NOT NULL,
		reason tinytext NOT NULL,
		ptr tinytext DEFAULT NULL,
		helo tinytext DEFAULT NULL,
		country tinytext NOT NULL,
		msgid int(11) NOT NULL DEFAULT 0,
		PRIMARY KEY (id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"
MySQLQuery $Query


<###   SCHEDULED TASKS   ###>

<#  Log Handler - collects awstats, expires database records, etc.  #>
$TaskName = "hMS Log Handler"
If (-not(TaskExists($TaskName))) {
	$Trigger = New-ScheduledTaskTrigger -Once -At "12/25/2022 0am" -RepetitionInterval (New-TimeSpan -Minute 1) -RepetitionDuration ((Get-Date).AddYears(25) - (Get-Date))
	$Settings = New-ScheduledTaskSettingsSet
	$Action = New-ScheduledTaskAction -Execute "PowerShell.exe" -Argument "-ExecutionPolicy Bypass -File `"$PSScriptRoot\hMSLogHandler.ps1`""
	$Principal = New-ScheduledTaskPrincipal -UserID "SYSTEM" -LogonType ServiceAccount
	Register-ScheduledTask -TaskName $TaskName -Principal $Principal -Action $Action -Settings $Settings -Trigger $Trigger
}

<#  Current Stats - collects data necessary for timely rendering of charts on stats page  #>
$TaskName = "hMS Log Current Stats"
If (-not(TaskExists($TaskName))) {
	$Trigger = New-ScheduledTaskTrigger -Once -At "12/25/2022 0am" -RepetitionInterval (New-TimeSpan -Minute 1) -RepetitionDuration ((Get-Date).AddYears(25) - (Get-Date))
	$Settings = New-ScheduledTaskSettingsSet
	$Action = New-ScheduledTaskAction -Execute "PowerShell.exe" -Argument "-ExecutionPolicy Bypass -File `"$PSScriptRoot\hMSLogStatsCurrent.ps1`""
	$Principal = New-ScheduledTaskPrincipal -UserID "SYSTEM" -LogonType ServiceAccount
	Register-ScheduledTask -TaskName $TaskName -Principal $Principal -Action $Action -Settings $Settings -Trigger $Trigger
}

<#  Daily Stats - collects data necessary for timely rendering of charts on stats page  #>
$TaskName = "hMS Log Daily Stats"
If (-not(TaskExists($TaskName))) {
	$Trigger = New-ScheduledTaskTrigger -Daily -At 12:01am
	$Settings = New-ScheduledTaskSettingsSet
	$Action = New-ScheduledTaskAction -Execute "PowerShell.exe" -Argument "-ExecutionPolicy Bypass -File `"$PSScriptRoot\hMSLogStatsDaily.ps1`""
	$Principal = New-ScheduledTaskPrincipal -UserID "SYSTEM" -LogonType ServiceAccount
	Register-ScheduledTask -TaskName $TaskName -Principal $Principal -Action $Action -Settings $Settings -Trigger $Trigger
}
