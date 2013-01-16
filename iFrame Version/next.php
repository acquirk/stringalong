<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stringalong</title>
<style type="text/css">
<!--
body {
overflow:hidden;
}
#next {
	position:absolute;
	right:0px;
	top:50px;
	width:200px;
	height:400px;
	z-index:999;
}
-->
</style>
</head>

<body>
<div id="next">
  <?php
mysql_connect("db.bullemhead.com", "string1", "string0") or die(mysql_error());

mysql_select_db("stringalong_test") or die(mysql_error());


// Retrieve one result from the "quirktest" table
$result = mysql_query("SELECT * FROM quirktest LIMIT 0,1")
or die(mysql_error());  

// Print out the contents of the entry 

while($row = mysql_fetch_array( $result )) {
$url = $row["url"];
$title = $row["title"];

// Print out the contents of each row into a table
	echo "<a href='$url' target='iframe' title='$title'><img src='/arrow_next.png' alt='Next' width='154' height='357' border='0'></a><br />";
} 

echo "<br /><br />";

// Retrieve all the data from the "quirktest" table
$result = mysql_query("SELECT * FROM quirktest")
or die(mysql_error());  

// Print out the contents of the entry 

while($row = mysql_fetch_array( $result )) {
$url = $row["url"];
$title = $row["title"];
	// Print out the contents of each row into a table
	echo "<a href='$url' target='iframe' title='$title'>$title</a><br />";
} 
?>
</div>

<div id="websites">

<iframe height="100%" width="100%" name="iframe" src="http://wreckandsalvage.com" scrolling="yes" title="escape(document.title)"></iframe>

</div>
</body>
</html>