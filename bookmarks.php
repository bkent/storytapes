<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="http://benkent.servehttp.com/storytapes/favicon.ico?v=2" />
<link rel="icon" type="image/jpg"  href="http://benkent.servehttp.com/storytapes/largeicon.jpg?v=2" />
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scaleable=no, width=device-width" />
<title>Storytapes</title>
<link rel="stylesheet" href="storytapes.css">
</head>
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

$datetoday = date('d/m/Y');

$pagenum = @$_POST["p"];

if (!isset($_POST["p"]))
{
	$pagenum  = 1;
}

echo "<body OnLoad=";?>"<? echo "document.form.q.focus()";?>"<? echo "><div align='center'><div class='searchbar'>Logged in as <a href='userstats.php'>". $_SESSION["short_name"] ."</a>&nbsp;</div>
      <div class='searchbar'><form name='form' id='form' method='post' action='home.php'><input type='text' name='q' id='q' value='$q' size='30' />
      <input type='submit' name='Search' value='Search' /></form></div>
	  <div class='searchbar'><form name='formr' method='post' action='random.php'>
      <input type='submit' name='Random' value='Rand' /></form></div>
	  <div class='searchbar'><form name='formq' method='post' action='home.php'>
	  <input type='hidden' name='o' value='q' />
      <input type='submit' name='Queue' value='Queue' /></form></div>
	  <div class='searchbar'><form name='formre' method='post' action='home.php'>
	  <input type='hidden' name='o' value='r' />
      <input type='submit' name='Recent' value='Recent' /></form></div>
	  <div class='searchbar'><form name='formadd' method='post' action='addtitle.php'>
      <input type='submit' name='addtitle' value='Add New' /></form></div>
	  <div class='searchbar'><form method='post' action='home.php' name='formreset'>
	  <input type='submit' name='reset' value='Reset Search' /></form></div>
	  <div class='searchbar'><form method='post' action='bookmarks.php' name='bookmarks'>
	  <input type='submit' name='bookmarks' value='Bookmarks' /></form></div>
	  <div class='searchbar'><form name='formlogout' method='post' action='logout.php'>
      <input type='submit' name='logout' value='Log Out' /></form></div></div>
      <div class='maintabdiv'><table>
		<tr>
		<th>Bookmark</th>
		<th>Created</th>	
		<th></th>	
		</tr>";

$mysqli = iConnect2();

$qdata = "select id,bookmark,createddt
  from bookmarks
  where userid='". $_SESSION["user_id"] . "'";
  
$data = $mysqli->query($qdata); 

  
$num_rows = $data->num_rows;

//This is the number of results displayed per page
$page_rows = 50;

//This tells us the page number of our last page 
$last = ceil($num_rows/$page_rows);

//this makes sure the page number isn't below one, or more than our maximum pages 
if ($pagenum < 1) 
{ 
	$pagenum = 1; 
} 
elseif ($pagenum > $last) 
{ 
	$pagenum = $last; 
}   

//This sets the range to display in our query 
$max = " LIMIT " .($pagenum - 1) * $page_rows ."," .$page_rows;

$qdata .= $max;

// run the query again with the limit clause
$data = $mysqli->query($qdata); 

if ($num_rows > 0)
{
    $i = 1;
	while ($row = $data->fetch_array())
	{
	    if ($i % 2 == 0) {  // if i is an even number
            $trclass = "class='alt'";
        }
		else {
		    $trclass="";
		}
	    
		$bid = $row['id'];
		$bookmark = $row['bookmark'];		
		$createddt = $row['createddt'];
		
		echo "<tr $trclass align='left'>";
		echo "<td><a href='$bookmark'>$bookmark</a></td>";
		echo "<td>" . date("d/m/Y H:i:s",strtotime($createddt)) . "</td>";
		echo "<td>";
		echo "<form method='post' action='editbookmark.php' name='formb$i'>
		<input type='hidden' name='sentfrom' value='bookmarks.php' />
		<input type='hidden' name='bid' value='$bid' />
		<input type='submit' name='delete' value='Delete' />
		</form>
		</td>";
		echo "</tr>";
		
		$i++;
	}
	
	if ($num_rows==1)
		$results = "title";
	else
		$results = "titles";
	
	echo "</table></div><div class='maintabdiv'>";
	
	echo "<div align='center'>";

// First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
if ($pagenum == 1) 
{
	$firstbuttonenabled = "disabled='disabled'";
}
else
{
	$firstbuttonenabled = "";
}

echo " <div class='searchbar'><form name='formpagefirst' id='formpagefirst' method='post' action='bookmarks.php'>
<input type='submit' name='first' value='<<' $firstbuttonenabled />
<input type='hidden' name='p' value='1' /></form></div>";

$previous = $pagenum-1;

echo " <div class='searchbar'><form name='formpageprevious' id='formpageprevious' method='post' action='home.php'>
<input type='submit' name='previous' value='<' $firstbuttonenabled />
<input type='hidden' name='p' value='$previous' /></form></div>";

//This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
if ($pagenum == $last) 
{
	$lastbuttonenabled = "disabled='disabled'";
}
else
{
	$lastbuttonenabled = "";
}

$next = $pagenum+1;

echo " <div class='searchbar'><form name='formpagenext' id='formpagenext' method='post' action='home.php'>
<input type='submit' name='next' value='>' $lastbuttonenabled />
<input type='hidden' name='p' value='$next' /></form></div>";

echo " <div class='searchbar'><form name='formpagelast' id='formpagelast' method='post' action='home.php'>
<input type='submit' name='last' value='>>' $lastbuttonenabled />
<input type='hidden' name='p' value='$last' /></form></div>";

echo "<div class='searchbar'>Page $pagenum of $last &nbsp;</div>";
	
	echo "<div class='searchbar'>| $num_rows $results
	</div></div>
	</div></body></html>"; 
}

?>