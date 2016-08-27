<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="HandheldFriendly" content="true" />
  <meta name="MobileOptimized" content="320" />
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scaleable=no, width=device-width" />

  <title>Update Title</title>
  <link rel="stylesheet" href="storytapes.css" />
  <link rel="shortcut icon" href="http://192.168.0.99/storytapes/favicon.ico?v=2" />
  <link rel="icon" type="image/jpg" href="http://192.168.0.99/storytapes/largeicon.jpg?v=2" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
  <?php
  include "functions.php";
  //Check if user is legitimately logged in
  session_start();
  
if (!isset($_SESSION["valid_user"]))
  {
  	// User not logged in, redirect to login page
  	header("Location: index.php");
  	exit();
  }
    $userid = $_SESSION["user_id"];
  	$bid = @$_POST["bid"];
    $bookmark = @$_POST["bookmark"];
  	$sentfrom = @$_POST["sentfrom"];
  	
  	$another="";
  	  	       
  	$mysqli = iConnect2();
  	
  if ($sentfrom == "bookmarks.php")
  {
  	$data = $mysqli->query("delete from bookmarks where id=$bid");
  }
  else if ($sentfrom == "player.php")
  {       
    $addeddt = date("Y-m-d H:i:s");
  	
  	$data = $mysqli->query("insert into bookmarks (userid,bookmark,createddt)
  	  values ($userid,'$bookmark','$addeddt')");
  }
  
  echo "<form method='post' action='bookmarks.php' id='autosubmit' name='autosubmit'>";
  echo "</form>";
  
?><script language="JavaScript">
    document.autosubmit.submit();
  </script>
</body>
</html>
