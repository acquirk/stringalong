<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Stringalong</title>
<link href="/css/main.css" media="screen" rel="stylesheet" type="text/css" /> 

</head>

<body>

<div id="start" align="center">
<img src="/images/navigation_mockups.png" width="710" height="570"/></div>

<div id="contact" align="center">
<h2>Stringalong is a playlist for the Internet - Grab the <a href="javascript:
function ajaxobject() {
 try {
  xmlHttp=new XMLHttpRequest();
 }
 catch (e) {
  try {
   xmlHttp=new ActiveXObject('Msxml2.XMLHTTP');
  }
  catch (e) {
   try {
    xmlHttp=new ActiveXObject('Microsoft.XMLHTTP');
   }
   catch (e) {
    alert('Your browser does not support AJAX!');
    return false;
   }
  }
 }
 return xmlHttp;
}

var dnow = new Date();
var urldate = dnow.getFullYear() + '-' + (dnow.getMonth() + 1) + '-' + dnow.getDate();
var urltime = dnow.getHours() + ':' + dnow.getMinutes() + ':' + dnow.getSeconds();

plao = ajaxobject();
plao.onreadystatechange=function() { };
plao.open('GET', 'http://xorengineering.com/dev/dev/bookmarklet/display2.php?url=' + escape(document.location) + '&title=' + escape(document.title) + '&urldate=' + escape(urldate) + '&urltime=' + escape(urltime), true);
plao.send(null);
">bookmarklet</a></h2>
String together sites, videos, or images then cycle through them in a playlist.<br />
Lots of stuff doesn't work yet. - <a href="mailto:bullemhead@gmail.com" target="_blank">Adam Quirk</a></div>
</body>
</html>
