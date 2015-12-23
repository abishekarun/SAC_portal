<?php
session_start();
require 'config.php';

$post_data = file_get_contents("php://input");
$data = json_decode($post_data);
$meeting_name=$data->meeting_name;
$year=$data->meeting_year;

try {
$con=new PDO("mysql:host=$server;dbname=$db",$user,$passwd);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("select * from msg where meeting_name = :meeting_name and year=:year ORDER BY id DESC");
$stmt->bindValue(':meeting_name',$meeting_name);
$stmt->bindValue(':year',$year);
$stmt->execute();
$result=array();
$db2='students_1415';
$con2=new PDO("mysql:host=$server;dbname=$db2",$user,$passwd);
$con2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
$username=$row['username'];
$stmt2 = $con2->prepare("select fullname from users where  username= :username");
$stmt2->bindValue(':username',$username);
$stmt2->execute();
$fullname=$stmt2->fetch(PDO::FETCH_ASSOC);

$row['fullname']=$fullname['fullname'];
array_push($result, $row);
}

echo json_encode($result);

}
catch(PDOException $e)
{
echo "Error: " . $e->getMessage();	
}
$con=null;

?>