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

<#  MySQL Run Query Function  #>
Function MySQLQuery($Query) {
	$Today = (Get-Date).ToString("yyyyMMdd")
	$DBErrorLog = "$PSScriptRoot\DBError-$Today.log"
	$ConnectionString = "server=" + $MySQLHost + ";port=" + $MySQLPort + ";uid=" + $MySQLUserName + ";pwd=" + $MySQLPassword + ";database=" + $MySQLDatabase + ";SslMode=" + $MySQLSSL + ";Default Command Timeout=" + $MySQLCommandTimeOut + ";Connect Timeout=" + $MySQLConnectTimeout + ";"
	Try {
		[void][System.Reflection.Assembly]::LoadWithPartialName("MySql.Data")
		$Connection = New-Object MySql.Data.MySqlClient.MySqlConnection
		$Connection.ConnectionString = $ConnectionString
		$Connection.Open()
		$Command = New-Object MySql.Data.MySqlClient.MySqlCommand($Query, $Connection)
		$DataAdapter = New-Object MySql.Data.MySqlClient.MySqlDataAdapter($Command)
		$DataSet = New-Object System.Data.DataSet
		$RecordCount = $dataAdapter.Fill($dataSet, "data")
		$DataSet.Tables[0]
	}
	Catch {
		Write-Output "$(Get-Date -f G) : ERROR : Unable to run query : $Query" | Out-File $DBErrorLog -Append
		Write-Output "$(Get-Date -f G) : ERROR : $($Error[0])" | Out-File $DBErrorLog -Append
	}
	Finally {
		$Connection.Close()
	}
}

<#  hMS Authentication Function  #>
Function hMSAuthenticate(){
	$hMS = New-Object -COMObject hMailServer.Application
	$hMS.Authenticate("Administrator", $hMSAdminPass) | Out-Null
	return $hMS
}

<#  Test For Local Account Function  #>
Function IsLocalAccount($Address){
	If ($Address) {
		$hMS = hMSAuthenticate
		$Domain = ($Address).Split("@")[1]
		Try {
			$hMSAccount = ($hMS.Domains.ItemByName($Domain)).Accounts.ItemByAddress($Address)
			If ($hMSAccount) {Return $True}
		}
		Catch {
			Return $False
			Exit
		}
	} Else {
		Return $False
	}
}

<#  Test if scheduled task exists  #>
Function TaskExists($ScheduledTaskName) {
	Try {
		Get-ScheduledTask -TaskName $ScheduledTaskName -TaskPath "\" -ErrorAction Stop
		Return $True
	}
	Catch {
		Return $False
	}
}
