<?php
session_start();
$_SESSION['user_id']=9626;

require 'config.php';

$post_data = file_get_contents("php://input");
$data = json_decode($post_data);
$meeting_name=$data->meeting_name;
$year=$data->meeting_year;

$user_id=$_SESSION['user_id'];
try {
$con=new PDO("mysql:host=$server;dbname=$db",$user,$passwd);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("select * from msg where meeting_name = :meeting_name and year=:year");
$stmt->bindValue(':meeting_name',$meeting_name);
$stmt->bindValue(':year',$year);
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

//echo "{message:'".$message."',user_id:'".$user_id."'}";
//array_push($json_data,$user_id);
echo json_encode($row);
}
catch(PDOException $e)
{
echo "Error: " . $e->getMessage();	
}
$con=null;

?>