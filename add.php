
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<? 
//initilize PHP

if($_POST['submit']) //If submit is hit
{
   //then connect as user
   //change user and password to your mySQL name and password
   mysql_connect("db.bullemhead.com","string1","string0"); 
	
   //select which database you want to edit
   mysql_select_db("stringalong_test"); 
	
   //convert all the posts to variables:
   $title = $_POST['title'];
   $url = $_POST['url'];
   $date = $_POST['date'];
   $time = $_POST['time'];
   
   //Insert the values into the correct database with the right fields
   //mysql table = quirktest
   //table columns = id, title, message, date, time
   //post variables = $title, $message, $date, $time
   $result=MYSQL_QUERY("INSERT INTO quirktest (id,title,url,date,time)".
      "VALUES ('NULL', '$title', '$url', '$date', '$time')"); 

    //confirm
   echo "Query Finished"; 
}
else
{
// close php so we can put in our code
?>
<form method="post" action="add.php">
<TABLE>
<TR>
   <TD>Title:</TD>
   <TD><INPUT TYPE='TEXT' NAME='title' VALUE='' size=60></TD>
</TR>
<TR>
   <TD>URL:</TD>
   <TD><INPUT TYPE='TEXT' NAME='url' VALUE='' size=60></TD>
</TR>
      
<TR>
   <TD><INPUT TYPE='hidden' NAME='date' VALUE='<? echo date("M.j.y"); ?>' size=60>
      <INPUT TYPE='hidden' NAME='time' VALUE='<? echo date("g:i a"); ?>' size=60>
	  </TD><br>
   <TD><INPUT TYPE="submit" name="submit" value="submit"></TD> 
</TR>
</TABLE>
</form>

<?
} //close the else statement
?>

</body>
</html>