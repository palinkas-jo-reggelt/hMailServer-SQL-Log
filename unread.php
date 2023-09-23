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
	include_once("head.php");

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
		$display_pagination = 1;
	} else {
		$page = 1;
		$total_pages = 1;
		$display_pagination = 0;
	}
	if (isset($_GET['search'])) {$search = trim($_GET['search']);} else {$search = "";}
	if (isset($_GET['clear'])) {header("Location: unread.php");}

	if ($search=="") {
		$search_ph="";
		$search_page = "";
		$search_SQL = "";
	} else {
		$search_ph=$search;
		$search_page = "&search=".$search;
		$search_SQL = " AND hm_accounts.accountaddress LIKE '%".$search."%'";
	}

	echo "
	<div class='section'>
		<h3>Unread Messages</h3>
		<span style='font-size:0.8em;'>hMailServer database query to find un-flagged (unread) messages. This information gives an indication on actual user activity.</span>

		<div style='line-height:24px;'>
			<form autocomplete='off' id='myForm' action='unread.php' method='GET'><br>
				<input type='text' size='20' name='search' placeholder='Search Accounts...' value='".$search_ph."'>
				<input type='submit' name='submit' value='Search' >
				<button class='button' type='submit' name='clear'>Reset</button>
			</form>
		</div>
	</div>
	
	<div class='section'>";

	$offset = ($page-1) * $no_of_records_per_page;
	$total_pages_sql = $pdo->prepare("
		SELECT COUNT(DISTINCT(hm_accounts.accountaddress)) AS count
		FROM hm_messages
		INNER JOIN hm_accounts ON hm_messages.messageaccountid = hm_accounts.accountid
		WHERE RIGHT(BIN(hm_messages.messageflags),1) = '0' AND accountactive = '1'".$search_SQL.";
	");
	$total_pages_sql->execute();
	$total_rows = $total_pages_sql->fetchColumn();
	$total_pages = ceil($total_rows / $no_of_records_per_page);

	$sumUnread_sql = $pdo->prepare("
		SELECT COUNT(*) AS count
		FROM hm_messages
		INNER JOIN hm_accounts ON hm_messages.messageaccountid = hm_accounts.accountid
		WHERE RIGHT(BIN(hm_messages.messageflags),1) = '0' AND accountactive = '1'".$search_SQL.";
	");
	$sumUnread_sql->execute();
	$sumUnread = $sumUnread_sql->fetchColumn();

	$sql = $pdo->prepare("
		SELECT hm_accounts.accountaddress AS address, COUNT(*) AS countunread
		FROM hm_messages
		INNER JOIN hm_accounts ON hm_messages.messageaccountid = hm_accounts.accountid
		WHERE RIGHT(BIN(hm_messages.messageflags),1) = '0' AND accountactive = '1'".$search_SQL."
		GROUP BY hm_accounts.accountaddress
		ORDER BY countunread DESC
		LIMIT ".$offset.", ".$no_of_records_per_page.";
	");
	$sql->execute();

	if ($search==""){
		$search_res="";
	} else {
		$search_res=" for account \"<b>".$search."</b>\"";
	}

	if ($total_pages < 2){
		$pagination = "";
	} else {
		$pagination = "(Page: ".number_format($page)." of ".number_format($total_pages).")";
	}

	if ($total_rows == 1){$singular = '';} else {$singular= 's';}
	if ($total_rows == 0){
		if ($search == "" && $reason == "" && $acc == ""){
			echo "Please enter a search term";
		} else {
			echo "No results ".$search_res;
		}	
	} else {
		echo "
		<span style='font-size:0.8em;'>Results ".$search_res.": ".number_format($sumUnread)." unread messages among ".number_format($total_rows)." account".$singular." ".$pagination."<br></span>
		<center>
		<div class='simple-div-table'>
			<div class='simple-div-table-row-header'>
				<div class='simple-div-table-col'>Account Address</div>
				<div class='simple-div-table-col'>Unread Messages</div>
			</div>";
		
		while($row = $sql->fetch(PDO::FETCH_ASSOC)){
			echo "
			<div class='simple-div-table-row'>
				<div class='simple-div-table-col left'><a href='./messages.php?search=".urlencode($row['address'])."'>".$row['address']."</a>&nbsp;</div>
				<div class='simple-div-table-col right'>".number_format($row['countunread'])."</a>&nbsp;</div>
			</div>";
		}
		echo "
		</div>
		</center>";

		if ($search==""){$search_page = "";} else {$search_page = "&search=".$search;}
		
		if ($total_pages == 1){
			echo "";
		} else {
			echo "
		<span class='nav'>
			<ul>
				";
			if($page <= 1){echo "<li>First</li>";} else {echo "<li><a href=\"?page=1".$search_page."\">First</a><li>";}
			if($page <= 1){echo "<li>Prev</li>";} else {echo "<li><a href=\"?page=".($page - 1).$search_page."\">Prev</a></li>";}
			if($page >= $total_pages){echo "<li>Next</li>";} else {echo "<li><a href=\"?page=".($page + 1).$search_page."\">Next</a></li>";}
			if($page >= $total_pages){echo "<li>Last</li>";} else {echo "<li><a href=\"?page=".$total_pages.$search_page."\">Last</a></li>";}
			echo "
			</ul>
		</span>";
		}
	}

?>

	</div> <!-- end of section -->

<?php include("foot.php") ?>