<?php

$serverNameServer = "PBI1852\SQLEXPRESS"; // Replace with the IP address or name of the remote/on-premise SQL Server
$databaseNameServer = "pbi_BTL_db"; // Replace with the name of the database you want to connect to
$usernameServer = ""; // Replace with the SQL Server login username
$passwordServer = ""; // Replace with the SQL Server login password

// Create the ODBC connection string
$dsnServer = "Driver={SQL Server};Server=$serverNameServer;Database=$databaseNameServer;";

// Connect to the remote SQL Server using the ODBC connection string
$connServer = odbc_connect($dsnServer, $usernameServer, $passwordServer);

if (!$connServer) {
    die("Connection server failed: " . odbc_errormsg());
}
// else{
//     echo "Connected";
// }

?>