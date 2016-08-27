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

$q = @$_POST["q"];
$ordby = @$_POST["o"];
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
		<th></th>
		<form name='formt' action='home.php' method='post'>
		<input type='hidden' name='q' value='$q' />
		<input type='hidden' name='o' value='t' /></form>
		<th><a href='#' onclick=";
		?>"<? echo "document.formt.submit();return false;";
		?>"<? echo " >Title</a></th>
		<form name='forma' action='home.php' method='post'>
		<input type='hidden' name='q' value='$q' />
		<input type='hidden' name='o' value='a' /></form>
		<th><a href='#' onclick=";
		?>"<? echo "document.forma.submit();return false;";
		?>"<? echo " >Author</a></th>
		<form name='formso' action='home.php' method='post'>
		<input type='hidden' name='q' value='$q' />
		<input type='hidden' name='o' value='so' /></form>
		<th><a href='#' onclick=";
		?>"<? echo "document.formso.submit();return false;";
		?>"<? echo " >Ord</a></th>
		<th>Tag</th>
		<form name='formo' action='home.php' method='post'>
		<input type='hidden' name='q' value='$q' />
		<input type='hidden' name='o' value='dt' /></form>
		<th><a href='#' onclick=";
		?>"<? echo "document.formo.submit();return false;";
		?>"<? echo " >Last Listen</a></th>
		<form name='formc' action='home.php' method='post'>
		<input type='hidden' name='q' value='$q' />
		<input type='hidden' name='o' value='ct' /></form>
		<th><a href='#' onclick=";
		?>"<? echo "document.formc.submit();return false;";
		?>"<? echo " >Ct</th>
		<th></th>
		</tr>";

$mysqli = iConnect2();

$qdata = "select distinct a.author, s.id sid, a.id aid, s.title, s.lastlistendt, s.listenct,
  s.comment, s.tags, s.seriesorder, s.filepath, s.id3tags
  from authors a, storytapes s, queue q
  where a.id=s.authorid
  and (a.author like '%$q%'
  or s.title like '%$q%'
  or s.tags like '%$q%')";
  
//echo "$ordby"; 

if ($ordby != "")
{
	if ($ordby == "dt")
        $qdata .= " order by s.lastlistendt desc, s.seriesorder desc";
    if ($ordby == "ct")
	    $qdata .= " order by s.listenct desc, s.lastlistendt desc, s.seriesorder desc";
	if ($ordby == "t")
	    $qdata .= " order by s.title";
	if ($ordby == "a")
	    $qdata .= " order by a.author";
	if ($ordby == "so")
	    $qdata .= " order by s.seriesorder, a.author";
	if ($ordby == "r")
	    $qdata .= " order by s.addeddt desc";
	if ($ordby == "q")
	    $qdata .= " AND q.userid='". $_SESSION["short_name"] ."' AND q.titleid=s.id AND q.active='Y' ORDER BY q.queueddt";
}
else
  $qdata .= " order by s.lastlistendt desc";

$data = $mysqli->query($qdata); 

//echo "$qdata"; 
  
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
	    
		//$row = $data->fetch_array();
		$author = $row['author'];
		$sid = $row['sid'];
		$aid = $row['aid'];
		$title = $row['title'];
		
		if (strlen($title) > 25)
		{
			$processedtitle = substr($title, 0, 25);
		    $processedtitle .= "...";
		}
		else
		    $processedtitle = $title;
		
		$lastlistendt = $row['lastlistendt'];
		$lastlistendt = date('d/m/Y',strtotime($lastlistendt));
		if ($lastlistendt == "01/01/1970")
		    $lastlistendt = "N/A";
		$listenct = $row['listenct'];
		$nextlistenct = $listenct + 1;
		$comment = $row['comment'];
		$tags = $row['tags'];
		$seriesorder = $row['seriesorder'];
		
		if ($seriesorder == 0)
		    $seriesorder = "-";
			
		$filepath = $row['filepath'];
		$id3tags = $row['id3tags'];
		
		echo "<tr $trclass align='left'>";
		echo "<td>";
		echo DisplayImage($sid);
		echo "</td>";
		echo "<td>";
		if ($id3tags == "X")
		  echo "* ";
		echo "<form name='tform$i' action='edittitle.php' method='post'>
		<input type='hidden' name='sid' value='$sid' /></form>
		<a href='#' onclick=";
		?>"<? echo "document.tform$i.submit();return false;";
		?>"<? echo " title='Tags: $tags&#10;Series order: $seriesorder&#10;File path: $filepath'>$processedtitle</a>";
		//<a href='" . $_SESSION["external_prefix"] . "$filepath.m3u' target='_blank'> >></a></td>";
		echo '<br/><select onchange="window.location=this.options[this.selectedIndex].value;">
				<option value="">< Listen now ></option>';
		//echo "  <option value=" . $_SESSION["external_prefix"] . "$filepath.m3u>External</option>";
		//echo "  <option value=" . $_SESSION["internal_prefix"] . "$filepath.m3u>Internal</option>";
		echo "  <option value=player.php?pl=$filepath>New Player</option>"; 
		echo "</select>
		<form method='post' action='updatetitle.php' name='forml$i'>
		<input type='hidden' name='sentfrom' value='edittitle.php'/>
		<input type='hidden' name='sid' value='$sid' />
		<input type='hidden' name='lastlistendt' value='$datetoday' />
		<input type='hidden' name='listenct' value='$nextlistenct' />
		<input type='hidden' name='title' value='$title' />
		<input type='hidden' name='comment' value='$comment' />
		<input type='hidden' name='tags' value='$tags' />
		<input type='hidden' name='seriesorder' value='$seriesorder' />
		<input type='hidden' name='filepath' value='$filepath' />
	    <input type='submit' name='listened' value='Listened' />
		</form>
		</td>";
		echo "<td><form name='aform$i' action='home.php' method='post'>
		<input type='hidden' name='q' value='$author' /></form>
		<a href='#' onclick=";
		?>"<? echo "document.aform$i.submit();return false;";
		?>"<? echo " >$author</a></td>";
        echo "<td>$seriesorder</td>";
		echo "<td><form name='tagform$i' action='home.php' method='post'>
		<input type='hidden' name='q' value='$tags' />
		<input type='hidden' name='o' value='so' /></form>
		<a href='#' onclick=";
		?>"<? echo "document.tagform$i.submit();return false;";
		?>"<? echo " >$tags</a></td>";
		echo "<td>$lastlistendt</td>";
		echo "<td>$listenct</td>";
		echo "<td></td>";
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

echo " <div class='searchbar'><form name='formpagefirst' id='formpagefirst' method='post' action='home.php'>
<input type='submit' name='first' value='<<' $firstbuttonenabled />
<input type='hidden' name='q' value='$q' />
<input type='hidden' name='o' value='$ordby' />
<input type='hidden' name='p' value='1' /></form></div>";

$previous = $pagenum-1;

echo " <div class='searchbar'><form name='formpageprevious' id='formpageprevious' method='post' action='home.php'>
<input type='submit' name='previous' value='<' $firstbuttonenabled />
<input type='hidden' name='q' value='$q' />
<input type='hidden' name='o' value='$ordby' />
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
<input type='hidden' name='q' value='$q' />
<input type='hidden' name='o' value='$ordby' />
<input type='hidden' name='p' value='$next' /></form></div>";

echo " <div class='searchbar'><form name='formpagelast' id='formpagelast' method='post' action='home.php'>
<input type='submit' name='last' value='>>' $lastbuttonenabled />
<input type='hidden' name='q' value='$q' />
<input type='hidden' name='o' value='$ordby' />
<input type='hidden' name='p' value='$last' /></form></div>";

echo "<div class='searchbar'>Page $pagenum of $last &nbsp;</div>";
	
	echo "<div class='searchbar'>| $num_rows $results
	</div></div>
	</div></body></html>"; 
}

?>