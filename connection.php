<?php
$servaname = "localhost";
$username = "root";
$password = "";
$dbname = "oğrenci_db";

$conn = new mysqli($servaname,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection Failed".$conn->connect_error);
}
echo"";

?>