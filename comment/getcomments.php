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
$post_id=12;	
$user_id=$_SESSION['user_id'];
try {
$con=new PDO("mysql:host=$host;dbname=$db",$username,$password);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("select * from msg where post_id = :post_id");
$stmt->bindValue(':post_id',$post_id);
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