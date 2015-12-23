<?php
session_start();
$_SESSION['username']=9626;
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
$username=$data->username;
try {
$con=new PDO("mysql:host=$server;dbname=$db",$user,$passwd);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $con->prepare("INSERT INTO msg (message,meeting_name,username,year) VALUES (:message, :meeting_name,:username,:year)");
$stmt->bindParam(':message', $message);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':meeting_name', $meeting_name);
$stmt->bindParam(':year', $year);
$stmt->execute();
//echo "{message:'".$message."',username:'".$username."'}";

/*----------get full name-----*/
$db2='students_1415';
$con2=new PDO("mysql:host=$server;dbname=$db2",$user,$passwd);
$con2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt2 = $con2->prepare("select fullname from users where  username= :username");
$stmt2->bindValue(':username',$username);
$stmt2->execute();
$fullname=$stmt2->fetch(PDO::FETCH_ASSOC);

//--------------------------------until here ------------------

$json_data=array('message'=>$message,'username'=>$username,'fullname'=>$fullname['fullname']);
//array_push($json_data,$username);
echo json_encode($json_data);
}
catch(PDOException $e)
{
echo "Error: " . $e->getMessage();	
}
$con=null;

?>