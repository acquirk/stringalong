<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<!-- include the jquery code --> 
<script src="/js/jquery-1.4.2.js" type="text/javascript"></script> 
 
<script type="text/javascript"> 
// setup jquery events after the document loads
$(document).ready( function(){
  // call the getdisplay function to load the initial display
  getdisplay()
 
  // setup button click event to submit URL via Ajax
  $("input[name='posturl']").click( function() {
    getdisplay()
  })
})
 
// function that loads the display div with database content
function getdisplay() {
  $.get("http://stringalong.bullemhead.com/display.php?" + $("form[name='urlform']").serialize(), function(data) {
           // when Ajax GET call returns data use it as the HTML for the div tag with class display
           $("div[name='display']").html(data)
         })
}
 
</script> 
</head>

<body>

	
<?php

mysql_connect("db.bullemhead.com", "string1", "string0") or die(mysql_error());

mysql_select_db("stringalong_test") or die(mysql_error());

if($_GET['title'] && $_GET['url'])
{

   //convert all the posts to variables:
   $title = $_GET['title'];
   $url = $_GET['url'];
   $date = $_GET['urldate']; //date('Y-m-d', time());
   $time = $_GET['urltime']; //date('H:i:s', time());

   //Insert the values into the correct database with the right fields
   //mysql table = quirktest
   //table columns = id, title, message, date, time
   //post variables = $title, $message, $date, $time

   $result=MYSQL_QUERY("INSERT INTO quirktest (title,url,urldate,urltime)".
      "VALUES ('$title', '$url', '$date', '$time')");

}
?>

<br />

<div name="display"></div> 
<h2><a href="add.php">Add.php</a></h2>
</body>
</html>