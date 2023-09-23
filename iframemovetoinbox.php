<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	include_once("config.php");
	include_once("functions.php");

	if (isset($_GET['fn'])) {$fn = trim($_GET['fn']);} else {$fn = "";}
	if (isset($_REQUEST['spam'])) {$spam = $_REQUEST['spam'];} else {$spam = null;}
	if (isset($_REQUEST['ham'])) {$ham = $_REQUEST['ham'];} else {$ham = null;}

	echo "
<!DOCTYPE html> 
<html>
<head>
<title>hMailServer SQL Log</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<meta http-equiv='Content-Style-Type' content='text/css'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='stylesheet' href='./css/jquery-ui.smoothness.css'>
<script src='./js/jquery.min.js'></script>
<script src='./js/jquery-ui.min.js'></script>
<link rel='stylesheet' type='text/css' media='all' href='./css/iframe.css'>
</head>
<body>";

	echo "<div class='pagehead'>Move message to Inbox: ".$fn."</div><br><br>";
	if ($fn) {
		if (file_exists($fn)) {
			$fnExpl = explode("\\",$fn);
			$fnCount = count($fnExpl);
			$fnFileName = $fnExpl[($fnCount-1)];
			$acctDomain = $fnExpl[($fnCount-4)];
			$acctAddress = $fnExpl[($fnCount-3)]."@".$fnExpl[($fnCount-4)];
			echo "Filename: ".$fnFileName."<br>";
			echo "Account Address: ".$acctAddress."<br>";

			$hMS = hMSAuthenticate();
			$hMSDomain = $hMS->Domains->ItemByName($acctDomain);
			$hMSAccount = $hMSDomain->Accounts->ItemByAddress($acctAddress);
		
			// <#  Find inbox folder for account and grab folder ID  #>
			$inboxFolderID = null;
			for ($IterateIMAPFolders = 0; $IterateIMAPFolders < $hMSAccount->IMAPFolders->Count; $IterateIMAPFolders++) {
				$hMSIMAPFolder = $hMSAccount->IMAPFolders->Item($IterateIMAPFolders);
				if (preg_match("/(inbox)/i", $hMSIMAPFolder->Name)) {
					$inboxFolderID = $hMSIMAPFolder->ID;
					echo "<br>Identified Inbox folder for account ".$acctAddress." as folder ID ".$inboxFolderID." with folder name \"".$hMSIMAPFolder->Name."\"<br><br>";
				}
			}

			// <#  If inbox folder found then copy message to inbox folder and delete from original folder  #>
			if ($inboxFolderID) {
				$sql = $pdo->prepare("SELECT messageid, messagefolderid FROM hm_messages WHERE messagefilename = '".$fnFileName."';");
				$sql->execute();
				while($row = $sql->fetch(PDO::FETCH_ASSOC)){
					$messageID = $row['messageid'];
					$messageFolderID = $row['messagefolderid'];
				}
				echo "Existing message ID: ".$messageID."<br>";
				echo "Existing folder ID: ".$messageFolderID."<br>";
				echo "Existing folder name: ".($hMSAccount->IMAPFolders->ItemByDBID($messageFolderID))->Name."<br><br>";
				
				$Folder = $hMSAccount->IMAPFolders->ItemByDBID($messageFolderID);
				$Message = $Folder->Messages->ItemByDBID($messageID);
				
				if ($messageFolderID == $inboxFolderID) {
					echo "<br><span class='warning'>Message already exists in Inbox folder! Aborting operation!</span>";
				} else {
			
					echo "Moving message ".$fn." with ID ".$messageID." from folder \"".($hMSAccount->IMAPFolders->ItemByDBID($messageFolderID))->Name."\" to folder \"".($hMSAccount->IMAPFolders->ItemByDBID($inboxFolderID))->Name."\"<br>";

					try {
						// $Message->Flag{1} = True;
						$Message->Save;
						$Message->Copy($inboxFolderID);
						$Folder->Messages->DeleteByDBID($Message->ID);
						echo "<br><span class='success'>Successfully moved message to Inbox folder.</span><br>";
					} catch (Exception $ex) {
						echo "<span class='warning'>[ERROR] Could not copy or delete message with ID ".$Message->ID."</span>";
						echo "<span class='warning'>[ERROR] ".$ex."</span>";
					}
				}
				
			// <#  If junk folder not found then create junk folder, copy message to junk folder and delete from original folder  #>
			} else {
				echo "<span class='warning'>Inbox folder not found!</span>";
				// try {
					// $hMSAccount->IMAPFolders->Add('Spam');
					// echo "Added folder \"Spam\" to account ".$hMSAccount->Address;
					// $NewinboxFolderID = ($hMSAccount->IMAPFolders->ItemByName('Spam'))->ID;
					// echo "Moving message with ID ".$Message->ID." to folder ".($hMSAccount->IMAPFolders->ItemByDBID($NewinboxFolderID))->Name;
					// $Message->Flag{1} = True;
					// $Message->Save;
					// $Message->Copy($NewinboxFolderID);
					// $Folder->Messages->DeleteByDBID($Message->ID);
					// echo "<br><span class='success'>Successfully moved message to Junk folder.</span><br>";
				// } catch (Exception $ex) {
					// echo "<span class='warning'>[ERROR] Could not copy or delete message with ID ".$Message->ID."</span>";
					// echo "<span class='warning'>[ERROR] ".$ex."</span>";
				// }
			}

		} else {
			echo "<span class='warning'>ERROR - EML file could not be found</span>";
		}

	} else {
		echo "<span class='warning'>ERROR - EML filename not specified</span>";
	}


	echo "
</body>
</html>";

?>