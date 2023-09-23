<?php
/*
╦ ╦╔╦╗╔═╗╦╦  ╔═╗╔═╗╦═╗╦  ╦╔═╗╦═╗
╠═╣║║║╠═╣║║  ╚═╗║╣ ╠╦╝╚╗╔╝║╣ ╠╦╝
╩ ╩╩ ╩╩ ╩╩╩═╝╚═╝╚═╝╩╚═ ╚╝ ╚═╝╩╚═
╔═╗╦ ╦╔═╗╔═╗╦═╗        ╦  ╔═╗╔═╗
╚═╗║ ║╠═╝║╣ ╠╦╝        ║  ║ ║║ ╦
╚═╝╚═╝╩  ╚═╝╩╚═        ╩═╝╚═╝╚═╝
*/

	echo "
	<br>
	<div class='footer'>
			Pálinkás jó reggelt kívánok!<br>";

	$versionGitHub = file_get_contents('https://raw.githubusercontent.com/palinkas-jo-reggelt/hMailServer-SQL-Log/master/VERSION');
	$versionLocal = file_get_contents('VERSION');
	if ((float)$versionLocal < (float)$versionGitHub) {
		echo "
		Upgrade to version ".trim($versionGitHub)." available at <a href='https://github.com/palinkas-jo-reggelt/hMailServer-SQL-Log'>GitHub</a>";
	}
	echo "
	</div> <!-- end FOOTER -->

</div> <!-- end WRAPPER -->
</body>
</html>";

?>