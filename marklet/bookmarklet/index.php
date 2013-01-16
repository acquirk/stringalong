<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- include the jquery code -->
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>

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
  $.get("<?php
         // build the URL for this server to call the display.php script
         $urlinfo = parse_url((isset($_SERVER['HTTPS'])?"https://":"http://").$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
         $urlpath = pathinfo($urlinfo['path']);
         $appurl = $urlinfo['scheme']."://".$urlinfo['host']."/".$urlpath['dirname']."/";
         echo $appurl;
         ?>display.php?" + $("form[name='urlform']").serialize(), function(data) {
           // when Ajax GET call returns data use it as the HTML for the div tag with class display
           $("div[name='display']").html(data)
         })
}

</script>

</head>

<body>
<form name="urlform" method="post" action="add.php">
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
   <TD><INPUT TYPE="button" name="posturl" value="submit">&lt;--- not a submit type anymore</TD> 
</TR>
</TABLE>
</form>

<br />
<div name="display"></div>
</body>
</html>
