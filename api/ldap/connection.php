<?php
function sqlquery($query){
	$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "******";
$mysql_database = "students_1516";
$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password,$mysql_database) or die("Could not connect database");
 return mysqli_query($bd,$query);
}
function real_escape_string($str){
	$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "*******";
$mysql_database = "students_1516";
$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password,$mysql_database) or die("Could not connect database");
     $string=mysqli_real_escape_string($bd,$str);
 return $string;
 }
?>