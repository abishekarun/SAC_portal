<?php
session_start();
$server='localhost';
$user='root';
$passwd='root';
$db='sac_portal';
$status=0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['year']) && isset($_POST['name']) ) {
$result = '';
$meeting_name=$_POST['name'];
$year=$_POST['year'];
$count = count($_FILES['fileToUpload']['name']);
$valid_formats = array("pdf", "mkv", "flv", "mp4", "avi", "wmv");
$max_file_size = 1024*1000*1000; //1 gb
$i = 0;
$response=array();
try{
        $conn0=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt0 = $conn0->prepare("INSERT INTO years (year) 
                VALUES (:year)");
            $stmt0->bindParam(':year', $year);
            $stmt0->execute();
}
catch(PDOException $e)
{
//echo 'Error: ' . $e->getMessage();
}
try{
        mysql_connect($server, $user, $passwd) or
            die("Could not connect: " . mysql_error());
        mysql_select_db($db);

        $result = mysql_query("SELECT * FROM meetings WHERE year='$year' AND meeting_name='$meeting_name' ");
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        if($row == "")
        {
            $conn2=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
            $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt2 = $conn2->prepare("INSERT INTO meetings (year,meeting_name) 
                VALUES (:year,:meeting_name)");
            $stmt2->bindParam(':year', $year);
            $stmt2->bindParam(':meeting_name', $meeting_name);
            $stmt2->execute();
        }
}
catch(PDOException $e)
{
//echo 'Error: ' . $e->getMessage();
}
$conn0=null;

while($i<$count)
{

$target_dir = "uploads/";
$target_file = str_replace(' ','',$target_dir . basename(trim($_FILES["fileToUpload"]["name"][$i])));
$uploadOk = 1;
$FileType = pathinfo($target_file,PATHINFO_EXTENSION);
//$file_name=basename($_FILES["fileToUpload"]["name"][$i],$FileType);
$file_name=str_replace(' ','',pathinfo($_FILES["fileToUpload"]["name"][$i], PATHINFO_FILENAME));
// Check if image file is a actual image or fake image
/* if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
*/
// Check if file already exists
if (file_exists($target_file)) {
    echo nl2br("Sorry, file already exists.\n");
    break;
    $uploadOk = 0;
}
// Check file size
elseif ($_FILES["fileToUpload"]["size"][$i] > $max_file_size) {
    echo nl2br("Sorry, your file is too large.\n");
    break;
    $uploadOk = 0;
}
// Allow certain file formats

elseif(! in_array(strtolower(pathinfo($_FILES["fileToUpload"]["name"][$i], PATHINFO_EXTENSION)), $valid_formats)) {
    echo nl2br("Sorry, only PDF, MP4, MKV , FLV & WMV files are allowed.\n");
    break;
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
elseif ($uploadOk == 0) {
    echo nl2br("Sorry, your file was not uploaded.\n");
    break;
// if everything is ok, try to upload file
} 
else {
    if (move_uploaded_file(trim($_FILES["fileToUpload"]["tmp_name"][$i]), $target_file)) {
        echo nl2br("The file ". basename( $_FILES["fileToUpload"]["name"][$i]). " has been uploaded.\n");
        
        $path=$target_file;
        $type=$FileType;
        try{
        $conn3=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn3->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt3 = $conn3->prepare("INSERT INTO files (year,meeting_name,file_name,path,type) 
                VALUES (:year,:meeting_name,:file_name,:path,:type)");
            $stmt3->bindParam(':year', $year);
            $stmt3->bindParam(':meeting_name', $meeting_name);
            $stmt3->bindParam(':file_name', $file_name);
            $stmt3->bindParam(':path', $path);
            $stmt3->bindParam(':type', $type); 
            $stmt3->execute();
            $status=1;
            $message="Addition and Upload successful";
        }catch(PDOException $e)
            {
            echo 'Error: ' . $e->getMessage();
            break;
            }
    }
    else 
    {
            echo nl2br("Sorry, there was an error uploading your file.\n");
            $status=3;
            $message="Upload is not successful";
            break;
    }
    if($status == 1)
    {   
        header("Location: http://localhost/SAC_Portal/getstarted.html#/index");
        exit();
    }//$responseArray = array('status' => $status ,'message' => $message);
    //array_push($response, $responseArray);
        $i++;
    }
    } 
    //echo json_encode($response);
    }
?>
