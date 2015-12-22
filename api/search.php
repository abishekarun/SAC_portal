<?php
// Array with names

require '../config.php';
$conn = new PDO("mysql:host=$server;dbname=$db", $user, $pwd);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("SELECT * FROM files");
//$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();
$a=[];
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	array_push($a,$row["file_name"]);
}
// get the q parameter from URL
$q = $_POST["q"];
$hint = "";
$i=0;
$list=[];
$arrlen=0;
$data=array();

// lookup all hints from array if $q is different from "" 
if ($q !== "") 
{
    $q = strtolower($q);
    $len=strlen($q);
		if($len>2)
		{
    	foreach($a as $name) 
			{
				$i++;
        if (stristr($name, $q))
				{
						$conn0 = new PDO("mysql:host=$server;dbname=$db", $user, $pwd);
						// set the PDO error mode to exception
						$conn0->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$stmt1 = $conn->prepare("SELECT * FROM files WHERE file_name='$name'");
						//$stmt->setFetchMode(PDO::FETCH_ASSOC);
						$stmt1->execute();
						$row = $stmt1->fetch();
						$details=array('year' => "{$row['year']}",'file' => "{$row['file_name']}",
	    				'path' =>"{$row['path']}" ,'meeting_name'=> "{$row['meeting_name']}" ,'type' => "{$row['type']}");
						array_push($data,$details);	

						$arrlen++;
						$name=strtolower($name);
						//$name[0]=strtoupper($name[0]);
						$name=rtrim($name," ");
						for($j=0;$j<strlen($name);$j++)
						{
							if( $name[$j] === " " )
							{
								$k=$j+1;
								$name[$k]=strtoupper($name[$k]);
							}
						}
						array_push($list,$name);					
          //  $hint .= "<li name='suggestions' class='list' id=".$i." onclick='submit(".$i.");'>".$name."</li>"; 
        }
    	}
		/*	$l=0;
			$m=0;
			for($l=0;$l<$arrlen;$l++)
			{
				for($m=0;$m<$arrlen-$l;$l++)
				{
					if($list[$m][0]>$list[$m+1][0])
					{
						$swap=$list[$m];
						$list[$m]=$list[$m+1];
						$list[$m+1]=$swap;
					}
				}
			}*/
			$id=0;
			sort($list,SORT_STRING);
			/*if($hint==="")
			echo "No suggestions";
			else
			*/
			$response=array('list' => $list, 'data' => $data);
			echo json_encode($response);
		}
		else
		echo "Minimum number of characters is 3 ";		
}
// Output "no suggestion" if no h int was found or output correct values 
function Bold($text, $str) {
    return str_replace($text, "<strong>".$text."</strong>", $str);
}
?>
