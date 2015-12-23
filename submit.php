<?php
session_start();
$_SESSION['user_id']=9626;
/*-----configuration ----*/
// $host="localhost";  
// $username="root";
// $password="sai"; 
// $db='comment';

require 'config.php';
$post_data = file_get_contents("php://input");
$data = json_decode($post_data);
$message=$data->message;
$meeting_name=$data->meeting_name;	
$year=$data->meeting_year;

$user_id=$_SESSION['user_id'];
try {
$con=new PDO("mysql:host=$server;dbname=$db",$user,$passwd);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("INSERT INTO msg (message,meeting_name,user_id,year) VALUES (:message, :meeting_name,:user_id,:year)");
$stmt->bindParam(':message', $message);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':meeting_name', $meeting_name);
$stmt->bindParam(':year', $year);
$stmt->execute();
//echo "{message:'".$message."',user_id:'".$user_id."'}";
$json_data=array('message'=>$message,'user_id'=>$user_id);
//array_push($json_data,$user_id);
echo json_encode($json_data);
}
catch(PDOException $e)
{
echo "Error: " . $e->getMessage();	
}
$con=null;

?>