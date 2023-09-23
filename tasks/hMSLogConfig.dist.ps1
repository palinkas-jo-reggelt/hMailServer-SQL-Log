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

<###   HMAILSERVER VARIABLES   ###>
$hMSAdminPass          = "supersecretpassword" # hMailServer Administrator Password

<###   DATABASE EXPIRY VARIABLES   ###>
$ExpireDataLogDays     = 30                    # Number of days before expiring DATA log tables
$ExpireMsgLogDays      = 60                    # Number of days before expiring MESSAGE log tables
$ExpireLogonLogDays    = 10                    # Number of days before expiring LOGON log tables
$ExpireFailedLogons    = $False                # True to expire, False to leave failed logons forever
$ExpireIDS             = 180                   # Number of minutes before expiring IDS entries

<###   MySQL VARIABLES   ###>
$MySQLUserName         = "hmailserver"
$MySQLPassword         = "supersecretpassword"
$MySQLDatabase         = "hmailserver"
$MySQLHost             = "localhost"
$MySQLPort             = 3306
$MySQLSSL              = "none"
$MySQLConnectTimeout   = 300
$MySQLCommandTimeOut   = 9000000               # Leave high if read errors

<###   STATS DATA VARIABLES   ###>
$wwwFolder             = "C:\xampp\htdocs\hmslog" # Location of webserver folder for php admin files