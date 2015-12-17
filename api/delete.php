<?php

$server='localhost';
$user='root';
$passwd='root';
$db='sac_portal';
$response=array();
if (isset($_POST['file_name']) && isset($_POST['year']) && isset($_POST['type']) && isset($_POST['meeting_name'])  ) {
try{
        $filename=$_POST['file_name'];
        $year=$_POST['year'];
        $type=$_POST['type'];
        $meeting_name=$_POST['meeting_name'];
        $conn0=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn0->prepare("DELETE FROM files WHERE file_name= '$filename' ");
        $stmt->execute();
        $a=".";
        $full_name=$filename.$a.$type;
        //console.log("uploads/".$full_name);
		unlink("../uploads/".$full_name);
        $conn0=null;
        $conn0=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn0->prepare("DELETE FROM meetings WHERE meeting_name= '$meeting_name' AND year= '$year' ");
        $stmt->execute();
        $conn0=null;
        $conn1=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt1 = $conn1->prepare(" SELECT * FROM files WHERE year= '$year' ");
        $stmt1->execute();
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
        $conn1=null;
		if($row["file_name"] == "")
		{
        	//console.log("success");
			$conn2=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        	$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt2 = $conn2->prepare(" DELETE FROM years WHERE year='$year' ");
        	$stmt2->execute();
		}
		$status = 1;
		$message = 'OK';
	}
	catch(PDOException $e)
	{
	//echo 'Error: ' . $e->getMessage();
		$status = 2;
		$message = $e->getMessage();
	}
}
	else{
		$status = 3;
		$message = 'invalid params';
	}
//print_r($result);
$response = array('status' => $status ,'message' => $message);
echo json_encode($response);
?>