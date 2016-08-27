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
	
	$position=0;
	if(!empty($_GET["pos"]))
	{
		$position=$_GET["pos"];
	}
	$playlist=$_GET["pl"];
	$track=0;
	if(!empty($_GET["tr"]))
	{
		$track=$_GET["tr"];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/search_results.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<title>Player</title>
<link rel="stylesheet" href="storytapes.css">
<link rel="shortcut icon" href="http://192.168.0.99/storytapes/favicon.ico?v=2" />
<link rel="icon" type="image/jpg"  href="http://192.168.0.99/storytapes/largeicon.jpg?v=2" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scaleable=no, width=device-width" />
</head>
<body>
	<audio controls="controls" id="audio2" preload="auto" 
 src="" 
 type="audio/mp3">
    <p>Your browser does not support the audio tag.</p>
</audio>
<div id="nowPlaying"></div>
<p><form id="addbookmark" method="post" action="editbookmark.php">
		<input type="hidden" name="sentfrom" value="player.php" />
		<input type="hidden" name="bookmark" id="bookmark" value="" />
		<input id="clickMe" type="button" value="Generate Bookmark" />
	</form>
</p>
		
<div id="timedisplay"></div>
<p><input id="prevtrack" type="button" value="Previous track" /><input id="testtracks" type="button" value="Next track" /></p>
<p>
	Sleep timer (mins)
	<select id="timer">
	  <option value="1800000">30</option>
	  <option value="2700000">45</option>
	  <option value="3600000">60</option>
	  <option value="7200000">120</option>
	</select>
	<input id="sleep" type="button" value="Start" />
</p>
<p><a href="home.php">Back to list</a></p>

<script src="<?php echo $_SESSION["external_prefix"] . $playlist; ?>.js"></script>
<script>
	// add an event listner to the audio control, 
	// to start the current track at a specific time (in seconds)
  myAudio=document.getElementById('audio2');
  myAudio.addEventListener('canplaythrough', function() {
    if(this.currentTime < <?php echo json_encode($position); ?>){this.currentTime = <?php echo json_encode($position); ?>}
    this.play();
    displayTrack();
  });
  
  myAudio=document.getElementById('audio2');
  myAudio.addEventListener('ended', function() {switchTrack();});
  
  document.getElementById("clickMe").onclick = function () {
	  //alert('hello!');
	  myAudio=document.getElementById('audio2');
	  var currTime = Math.floor(myAudio.currentTime);
	  var timedisplay = document.getElementById('timedisplay');
 	  //timedisplay.innerHTML = "http://benkent.servehttp.com/storytapes/player.php?pl= < ?php echo $playlist; ?>&tr=" + playlist_index.toString() + "&pos=" + currTime.toString();
      document.forms.addbookmark.bookmark.value = "http://benkent.servehttp.com/storytapes/player.php?pl=<?php echo $playlist; ?>&tr=" + playlist_index.toString() + "&pos=" + currTime.toString();
      document.forms["addbookmark"].submit(); 
  };
	
	//http://stackoverflow.com/questions/17506685/playlist-with-audio-javascript
	
	var playlist_index = <?php echo json_encode($track); ?>;
	
	//document.getElementById("testtracks").onclick = function () {
	window.onload = function () {
	  //alert(tracks[2]);
    //playlist_index = <?php echo json_encode($track); ?>;
    myAudio.src=tracks[playlist_index];
    myAudio.play();
    displayTrack();
    };
	
	document.getElementById("testtracks").onclick = function() {switchTrack()};
	document.getElementById("prevtrack").onclick = function() {switchTrackReverse()};
    
  function switchTrack(){
	if(playlist_index == (tracks.length - 1)){
		playlist_index = 0;
	} else {
	    playlist_index++;	
	}
	myAudio.src = tracks[playlist_index];
	myAudio.play();
    displayTrack();
	}
	
	function switchTrackReverse(){
	if(playlist_index == (0)){
		playlist_index = tracks.length - 1;
	} else {
	    playlist_index--;	
	}
	myAudio.src = tracks[playlist_index];
	myAudio.play();
    displayTrack();
	}
  
  function displayTrack(){
    var trackDisplay = document.getElementById('nowPlaying');
 	  trackDisplay.innerHTML = myAudio.src;
  }
  
  document.getElementById("sleep").onclick = function() {sleepTimerClickHandler()};  
  
  function sleepTimerClickHandler()
	{
	  if (document.getElementById("sleep").value == "Start")
	  {
		var e = document.getElementById("timer");
        var interval = e.options[e.selectedIndex].value;
	    // Start the timer
	    document.getElementById("sleep").value = "Cancel";
	    sleepTimer = setInterval(function(){ myAudio.pause(); }, interval);
	  }
	  else
	  {
	    document.getElementById("sleep").value = "Start";
	    clearInterval ( sleepTimer );
	  }
	}
  
</script>
</body>
</html>