# ~~~~~~~ BEGIN USER VARIABLES ~~~~~~~
$ExpireDays = "180"
$MySQLAdminUserName = 'root'
$MySQLAdminPassword = 'supersecretpassword'
$MySQLDatabase = 'hmailserver'
$MySQLHost = 'localhost'
$DBErrorLog = 'C:\scripts\hmailserver\ConLog\ConLogDBError.log'
# ~~~~~~~ END USER VARIABLES ~~~~~~~

Function MySQLQuery($Query) {
	$ConnectionString = "server=" + $MySQLHost + ";port=3306;uid=" + $MySQLAdminUserName + ";pwd=" + $MySQLAdminPassword + ";database=" + $MySQLDatabase
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
	  Write-Output "ERROR : Unable to run query : $query `n$Error[0]" | out-file $DBErrorLog -append
	 }
	Finally {
	  $Connection.Close()
	  }
}

#	Automatic expiration from Connection Log
$Query = "DELETE FROM hm_accrej WHERE timestamp < now() - interval $ExpireDays day"
MySQLQuery $Query