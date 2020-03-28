<?php
//index.php




?>
<!DOCTYPE html>
<html>
 <head>
  <title>Jquery Fullcalandar Integration with PHP and Mysql</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />

 
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script>
   
  $(document).ready(function() {
   var calendar = $('#calendar').fullCalendar({
    editable:true,
    header:{
     left:'prev,next today',
     center:'title',
     right:'month,agendaWeek,agendaDay'
    },
    events: '/index.php/evenements/get_event_calendar',
    selectable:true,
    selectHelper:true,
 
    editable:false,


    eventClick:function(event)
    {
     
      var id = event.id;
      document.location.href = '/index.php/evenements/reunion/'+id;
     
    },
 
   });

   
  });
   
  </script>
 </head>
 <body>
  <br />
  <h2 align="center"><a href="#">Jquery Fullcalandar Integration with PHP and Mysql</a></h2>
  <br />
  <div class="container">
   <div id="calendar"></div>
  </div>
 </body>
</html>

