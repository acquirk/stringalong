<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stringalong</title>
</head>

<body>
<?php
include 'config.php';


// Retrieve one result from the "quirktest" table
$result = mysql_query("SELECT * FROM quirktest LIMIT 1,1")
or die(mysql_error());  

// Print out the contents of the entry 

while($row = mysql_fetch_array( $result )) {
$url = $row["url"];
$title = $row["title"];

// Print out the contents of each row into a table
	echo "<a href='$url' target='mainFrame' title='$title'><img src='/arrow_next.png' alt='Next' width='154' height='357' border='0'></a><br />";
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
	echo "<a href='$url' target='mainFrame' title='$title'>$title</a><br />";
} 
?>

</body>
</html>