<?php
function iConnect() 
{
mysql_connect("localhost", "root", "root") or die(mysql_error()); 
mysql_select_db("pidoorman") or die(mysql_error()); 
}

function iConnect2() 
{
	$mysqli = new mysqli("localhost", "root", "root","storytapes");
	if($mysqli->connect_errno > 0)
	{
		die('Unable to connect to database [' . $mysqli->connect_error . ']');
	}
	else
	    return $mysqli;
}

function ukstrtotime($str) {
	return strtotime(preg_replace("/^([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})/", "\\2/\\1/\\3", $str));
}

function DisplayImage($id) {

    $query = "SELECT file,type FROM artwork WHERE titleid = ". $id;

	$mysqli = iConnect2();
	$data = $mysqli->query($query); 
	$num_rows = $data->num_rows;
	if ($num_rows > 0)
	{
		$row = $data->fetch_array();
		$mime = $row["type"];

		$b64Src = "data:".$mime.";base64," . base64_encode($row["file"]);
		return '<img src="'.$b64Src.'" alt="" width="60px"/>';
	}
}

?>