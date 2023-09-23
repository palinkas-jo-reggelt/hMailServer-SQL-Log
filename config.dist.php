<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

//	Site Logon Variables 
$user_name              = 'adminusername';            // User name for site login
$pass_word              = 'supersecretpassword';      // Password for site login
$cookie_duration        = 90;                         // Cookie duration in days

// Website variables
$no_of_records_per_page = 30;                         // Pagination - Number of records per page
$viewport_width         = 1400;                       // Width of tables. Set lower for lower screen resolution. Does not affect mobile view (small screens).
$TimeZone               = 'America/New_York';         // Timezone of server - required for converting to UTC for hMailServer db message queries
$msgSearchInterval      = 30;                         // Interval in SECONDS, used for searching for filename of physical EML files

// hMailServer Variables
$hMSAdminPass           = "supersecretpassword";      // Administrator password
$dataFolder             = "C:\\HMS-DATA\\Data";       // Data folder - required for display of Raw EML files (no trailing slash "\" please)
$spamAccount            = "spam@spam.domain.tld";     // Account to which spam is forwarded for administrator inspection (if used) (options are "account@address" or false)
$postMaster             = "postmaster@domain.tld";    // Postmaster account to copy on unsubscriber messages

// Spamassassin Variables
$useSpamassassin        = true;                       // Optional; enable to feed individual messages to Bayes 
$spamassassinPath       = "C:\\SpamAssassin";         // Spamassassin path (no trailing slash "\" please)

/*	Database Variables 

		'driver' = connection type
	
		For MySQL use driver = 'mysql'
		For ODBC  use driver = 'odbc'
		
		* When opting for ODBC use correct DSN!      *
		* Example: "MariaDB ODBC 3.0 Driver"         *
		* Example: "MySQL ODBC 5.3 Unicode Driver"   *
		* Exact spelling is critical!                *
	
*/

$Database = array (
	'host'              => 'localhost',
	'username'          => 'hmailserver',
	'password'          => 'supersecretpassword',
	'dbname'            => 'hmailserver',
	'driver'            => 'mysql',
	'port'              => '3306',
	'dsn'               => 'MariaDB ODBC 3.0 Driver'
);


?>