<?phpinclude "functions.php";//Check if user is legitimately logged insession_start();if (!isset($_SESSION["valid_user"])){	// User not logged in, redirect to login page	header("Location: index.php");	exit();}if (isset($_POST["another"]))	$currentaid = @$_POST["another"];else	$currentaid = "";?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/search_results.dwt" codeOutsideHTMLIsLocked="false" --><head><title>Add Title</title><meta name="HandheldFriendly" content="true" /><meta name="MobileOptimized" content="320" /><meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scaleable=no, width=device-width" /><link rel="stylesheet" href="storytapes.css"><link rel="shortcut icon" href="http://benkent.servehttp.com/storytapes/favicon.ico?v=2" /><link rel="icon" type="image/jpg"  href="http://benkent.servehttp.com/storytapes/largeicon.jpg?v=2" /><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	<link rel="stylesheet" href="../jquery/development-bundle/themes/base/jquery.ui.all.css">	<script src="../jquery/development-bundle/jquery-1.7.1.js"></script>	<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>	<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>	<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>	<script src="../jquery/development-bundle/ui/i18n/jquery.ui.datepicker-en-GB.js"></script>	<link rel="stylesheet" href="./jquery/development-bundle/demos.css">	<script>	$(function() {		$( "#datepicker" ).datepicker();	});	$(function() {		$( "#datepicker2" ).datepicker();	});	</script></head><body><?php $mysqli = iConnect2();echo "<table>";echo "<form method='post' action='updatetitle.php'>";echo "<tr><td align='right' width='auto'><input type='hidden' name='sentfrom' value='addtitle.php'/><b>Author: </b></td><td width='auto'>";if ($currentaid == ""){	$dropdowndata = $mysqli->query("select id, author	  from authors	  order by author");	  	echo "<select name='aid'>";	  	$num_rows = $dropdowndata->num_rows;	if ($num_rows > 0)	{		while ($row = $dropdowndata->fetch_array())		{			$author = $row['author'];			$aid = $row['id'];			echo "<option value='$aid' >$author</option>";		}	}	echo "</select>&nbsp;<a href='addauthor.php'>Add Author</a></td></tr>";}else{	$data = $mysqli->query("select author	  from authors	  where id=$currentaid");		$row = $data->fetch_array();	$author = $row['author'];		  	echo "<input type='text' value='$author' readonly='readonly' />";	echo "<input type='hidden' name='aid' value='$currentaid'/>";	echo "</td></tr>";}//echo "current authorid=$currentaid";	//$lastlistendt = date('d/m/Y');	echo "<tr><td align='right'><b>Title: </b></td><td><input type='text' name='title' /></tr>";//echo "<tr><td align='right'><b>Listen count: </b></td><td><input type='number' min='0' name='listenct' value='0' disabled /></tr>";echo "<input type='hidden' name='listenct' value='0' />";//echo "<tr><td align='right'><b>Last Listen: </b></td><td><input type='text' name='lastlistendt' id='datepicker' value='";//echo "$lastlistendt'/><input type='hidden' id='locale' value='en-GB'/></td></tr>";echo "<tr><td align='right'><b>Comment: </b></td><td><input type='text' name='comment' /></tr>";echo "<tr><td align='right'><b>Tags: </b></td><td><input type='text' name='tags' /></tr>";echo "<tr><td align='right'><b>Genre: </b></td><td><select name='genre' >";echo "<option selected='selected' value='Crime' >Crime</option>";echo "<option value='Adventure' >Adventure</option>";echo "<option value='Childrens' >Childrens</option>";echo "<option value='Fantasy' >Fantasy</option>";echo "<option value='Sci-fi' >Sci-fi</option>";echo "<option value='Comedy' >Comedy</option>";echo "<option value='Espionage' >Espionage</option>";echo "<option value='Historical Crime' >Historical Crime</option>";echo "</select></td></tr>";echo "<tr><td align='right'><b>Series Order: </b></td><td><input type='number' min='0' name='seriesorder' value='0' /></tr>";echo "<tr><td align='right'><b>Filepath: </b></td><td><input type='text' name='filepath' /></tr>";echo "<tr><td></td><td align='right'><input type='submit' name='another' value='Another same author' /> &nbsp; <input type='submit' name='Submit' value='OK' />";echo "&nbsp; <form method='post' action='home.php' name='formhome'><input type='submit' name='home' value='Cancel' /></form></td></tr></table>";echo "</form>";?></body></html>