<?php


require '../config.php';
$response=array();
$data=array();
$result=array();
$output=array();
try{
        $conn0=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
        $conn0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt1 = $conn0->prepare("SELECT * FROM years ORDER BY year ASC");
        $stmt1->execute();
		while($row=$stmt1->fetch(PDO::FETCH_ASSOC))
		{
			$year=$row["year"];
			try{
			        $conn2=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
			        $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			        $stmt2 = $conn2->prepare("SELECT * FROM meetings WHERE year=$year");
			        $stmt2->execute();
					while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
					{
						$meeting_name=$row["meeting_name"];
							try
							{
						        $conn1=new PDO("mysql:dbname=$db;host=$server", $user, $passwd);
						        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						        $stmt = $conn1->prepare(" SELECT * FROM files WHERE meeting_name='$meeting_name' AND year=$year");
					            $stmt->execute();
					    		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					    		{
					    			//str_replace('/','',"{$row['path']}");
					    			//$path=$row['path'];
					    			$path=str_replace('\/','/',$row['path']);
					    			$itemAr = array( 'file_id' => "{$row['id']}",'file_name' => "{$row['file_name']}",
					    				'path' => $path, 'type' => "{$row['type']}");
									array_push($data, $itemAr);
					    		}
							}
							catch(PDOException $e)
							{
							echo 'Error: ' . $e->getMessage();
							}
							if($data != null)
							{
								$response=array('meeting_name' => $meeting_name , 'files' => $data );
								array_push($result,$response);
							}
						$data=[];
					}
				}
				catch(PDOException $e)
				{
				echo 'Error: ' . $e->getMessage();
				}
				if($result != null)
							{
								$temp=array('year' => $year , 'meetings' => $result );
								array_push($output,$temp);
							}
						$result=[];
		}
	}
	catch(PDOException $e)
	{
	echo 'Error: ' . $e->getMessage();
	}
//print_r($result);
echo json_encode($output);
?>