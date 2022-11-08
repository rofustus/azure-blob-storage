<?php

namespace AzurePHP\Service;
use \PDO;

class mysql {

public function insertDB1(string $filename, string $url){
$sqlServer = getenv('SQL_SERVER');
$sqlUser = getenv('SQL_USER');
$sqlPass = getenv('SQL_PASS');
$sqlDb = getenv('SQL_DB1');

// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:" . $sqlServer . ",1433; Database = " . $sqlDb, $sqlUser, $sqlPass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}
$time = time();
$timestamp = date("Y-m-d h:m:s", $time);
$sql = "INSERT INTO img (url, filename, upload-time) values (?,?,?)";
$stmt= $conn->prepare($sql);
$srmt->execute([$url, $filename, $timestamp]);
$conn=null;

return true;
} //end function

} //end class
?>
