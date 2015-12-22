<?php
session_start();
$_SESSION['user_id']=9626;
/*-----configuration ----*/
$host="localhost";  
$username="root";
$password="sai"; 
$db='comment';

$post_data = file_get_contents("php://input");
$data = json_decode($post_data);
$message=$data->message;
$post_id=$data->post_id;	

$user_id=$_SESSION['user_id'];
try {
$con=new PDO("mysql:host=$host;dbname=$db",$username,$password);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("INSERT INTO msg (message,post_id,user_id) VALUES (:message, :post_id,:user_id)");
$stmt->bindParam(':message', $message);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':post_id', $post_id);
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