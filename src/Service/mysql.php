<?php

namespace AzurePHP\Service;
use \PDO;
use \DateTime;

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
$time= DateTime::createFromFormat('U.u', microtime(true));
$timestamp = $time->format("y-m-d h:i:s.u");
echo $timestamp;
$t = 1;
$data = [
    'url' => $url,
    'filename' => $filename,
    'timestamp' => $timestamp,
];
echo $url;
echo $filename;
$sql = "INSERT INTO img (url, filename, timestamp) VALUES (:url, :filename, :timestamp)";
$stmt = $conn->prepare($sql);
//$stmt->bindParam(1, $url, PDO::PARAM_STR);
//$stmt->bindParam(2, $filename, PDO::PARAM_STR);
//$stmt->bindParam(3, $timestamp, PDO::PARAM_STR);
//$stmt->exec([$url, $filename, $timestamp]);
$stmt->execute($data);
$conn=null;

return true;
} //end function

} //end class
?>
