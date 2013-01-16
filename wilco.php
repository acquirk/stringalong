<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stringalong</title>
<meta content="Stringalong lets you make and watch Internet playlists" name="description" />
<link href="http://stringalong.bullemhead.com/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href="http://stringalong.bullemhead.com/css/main.css" media="screen" rel="stylesheet" type="text/css" /> 

<script src="/js/float.js" type="text/javascript"></script>
<script src="/js/allowframe.js" type="text/javascript"></script>

</head>

<body onLoad="start()">
<div id="floatLayer" style="position: absolute; right:0px; top:0px;">
<div id="next">
  <?php
  
include $_SERVER['DOCUMENT_ROOT'] . '/config.php'; 


// Retrieve one result from the "quirktest" table
$result = mysql_query("SELECT * FROM wilco ORDER BY urltime DESC LIMIT 0,1")
or die(mysql_error());  

// Print out the contents of the entry 

while($row = mysql_fetch_array( $result )) {
$url = $row["url"];
$title = $row["title"];

// Print out the contents of each row into a table
	echo "<a href='$url' target='iframe' title='$title'><img src='/images/arrow_back.png' alt='Next' width='63' height='77' border='0'></a> &nbsp; <a href='$url' target='iframe' title='$title'><img style='float:right;' src='/images/arrow_next.png' alt='Next' width='63' height='77' border='0'></a><br />";
} 

echo "<br /><br />";

// Retrieve all the data from the "quirktest" table
$result = mysql_query("SELECT url, urltime, urldate, title FROM wilco ORDER BY urltime DESC")
or die(mysql_error());  

// Print out the contents of the entry 

while($row = mysql_fetch_array( $result )) {
$url = $row["url"];
$title = $row["title"];
$smalltitle = substr($title, 0, 22); 

	// Print out the contents of each row into a table
	echo "<a href='$url' target='iframe' title='$title'>$smalltitle</a><br />";
} 
?>

</div>

</DIV>

<div id="websites">

<iframe height="100%" width="100%" name="iframe" src="http://www.wilcoworld.net/" scrolling="yes" title="escape(document.title)"></iframe>

</div>
</body>
</html>