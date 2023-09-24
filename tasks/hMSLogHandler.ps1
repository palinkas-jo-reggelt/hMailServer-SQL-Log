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

<#  Get hMailServer log folder location  #>
$hMS = hMSAuthenticate
$LogDir = $hMS.Settings.Directories.LogDirectory

<#	Import AWStats Log into database   #>
Get-Content "$LogDir\hmailserver_awstats.log" | ConvertFrom-String -Delimiter "`t" -PropertyNames TimeStamp, Sender, Recipient, ConnectionSender, ConnectionRecipient, Protocol, QuestionMark, StatusCode, MessageSize | ForEach {
	$Query = "
		INSERT INTO hm_log_awstats (timestamp, sender, recipient, connsender, connrecipient, statuscode) 
		VALUES (
			'$(($_.TimeStamp).ToString('yyyy-MM-dd HH:mm:ss'))',
			'$($_.Sender)',
			'$($_.Recipient)',
			'$($_.ConnectionSender)',
			'$($_.ConnectionRecipient)',
			'$($_.StatusCode)'
		);
	"
	MySQLQuery $Query
}

<#  Clear out AWStats Log  #>
Clear-Content "$LogDir\hmailserver_awstats.log"

<#  Update Msg Log table with statuscode  #>
$Query = "
	UPDATE hm_log_msg
	LEFT JOIN hm_log_awstats ON hm_log_msg.envelopeFrom = hm_log_awstats.sender AND hm_log_msg.envelopeTo = hm_log_awstats.recipient
	SET hm_log_msg.statuscode = hm_log_awstats.statuscode
	WHERE hm_log_msg.statuscode = 600 AND hm_log_awstats.timestamp < hm_log_msg.timestamp + INTERVAL 30 SECOND AND hm_log_awstats.timestamp > hm_log_msg.timestamp - INTERVAL 30 SECOND;
"
MySQLQuery $Query

<#  Expire IDS entries  #>
$Query = "DELETE FROM hm_ids WHERE timestamp < NOW() - INTERVAL $ExpireIDS MINUTE;"
MySQLQuery $Query
