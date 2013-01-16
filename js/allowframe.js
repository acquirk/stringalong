var prevent_bust = 0
 window.onbeforeunload = function() { prevent_bust++ }
 setInterval(function() {
   if (prevent_bust > 0) {
     prevent_bust -= 2
     window.top.location = 'http://stringalong.bullemhead.com/nocontent/'
   }
 }, 1)