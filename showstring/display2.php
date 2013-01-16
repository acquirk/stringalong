<? 
//initilize PHP
   mysql_connect("db.bullemhead.com","string1","string0"); 
   mysql_select_db("stringalong_test"); 

if($_GET['title'] && $_GET['url'])
{
   //then connect as user
   //change user and password to your mySQL name and password
//   mysql_connect("stringdb.stringalong.me","string1","string0"); 
	
   //select which database you want to edit
//   mysql_select_db("stringalong_test"); 
	
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

$result=mysql_query("SELECT * FROM quirktest");
/*
while ($row=mysql_fetch_assoc($result)) {
  foreach($row as $k => $v ) echo $k." = '".$v."'&nbsp;&nbsp;";
  echo "<br>\n";
}
*/
?>
