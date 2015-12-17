<?php
error_reporting(0);

session_start();
//header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
//header('Access-Control-Allow-Methods: GET, POST');
require_once ('connection.php');
//require_once ('auth.php');

/** whatever you're serializing **/;


//var_dump($_POST);


if (!isset($_POST["roll"]))
{
  //replace echo with a shared variable for proper styling and use...
  $array=array("val"=>"no rollnumber");
                        echo json_encode($array);
 // echo "{'msg':'You haven't entered your roll number. Please do enter it.'}";
}
if (!isset($_POST["pass"]))
{
  //replace echo with a shared variable for proper styling and use...
$array=array("val"=>"no pass");
                        echo json_encode($array);
//  echo "{'msg':'You haven't entered your password. Please do enter it.'}";
}

if (isset($_POST["pass"]) && isset($_POST["roll"]) ) 
{
$roll = strtoupper($_POST["roll"]);
$pass = $_POST["pass"];

  $ldapServer = "ldap.iitm.ac.in";
  $ldapPort = 389;
  $ldapDn = "cn=students,ou=bind,dc=ldap,dc=iitm,dc=ac,dc=in";
  $ldapPass = "rE11Bg_oO~iC";
  $ldapConn = ldap_connect($ldapServer, $ldapPort) or die("Could not connect to LDAP server.");  

  $studentUser = $roll;
  $studentPass = $pass;

  if($ldapConn) 
  {
    $ldapBind = ldap_bind($ldapConn, $ldapDn, $ldapPass);
    if($ldapBind)
    {
      //echo "Bound<br>";
      $filter = "(&(objectclass=*)(uid=".$studentUser."))";
      $ldapDn = "dc=ldap,dc=iitm,dc=ac,dc=in";
      $result = ldap_search($ldapConn, $ldapDn, $filter) or die ("Error in search query: ".ldap_error($ldapConn));   
      $entries = ldap_get_entries($ldapConn, $result);
      foreach($entries as $values => $values1)
      {
        $logindn = $values1['dn'];
      }
      $loginbind = ldap_bind($ldapConn, $logindn, $studentPass);
      //var_dump($loginbind);
      if ($loginbind)
      {
        $qry = "SELECT * FROM users WHERE username='$studentUser' ";
        $result = sqlquery($qry); 
	if ($result) {
		if (mysqli_num_rows($result) > 0) {
		$jsonData = array();
		while ($row = mysqli_fetch_assoc($result)) {
    //echo "Tutorial ID :{$row['name']}  <br> " . "Title: {$row['event']} <br> " . "Author: {$row['venue']} <br> " . "Submission Date : {$row['description']} <br> " . "--------------------------------<br>";
			$jsonData[]=$row;
		}
		echo json_encode(array_reverse($jsonData));
		} 
		else {
	//		$array=array("val"=>"s2");
	//		echo json_encode($array);
		      echo "{'success':2}";
			exit();
		}
	}
	}
	else
	{
		http_response_code(401);
		$array=array("message"=>"Wrong Password");
		echo json_encode($array);
	//      echo "{'success':0}";
        }
    }
  }
  ldap_unbind($ldapConn);
  //ldap authentication ends here//

  //connect to students database to retrieve user info...
  
  //}
}
?>

