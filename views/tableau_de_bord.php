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


      <script src="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler.js"></script>
      <link href="https://cdn.dhtmlx.com/scheduler/edge/dhtmlxscheduler_material.css" 
              rel="stylesheet" type="text/css" charset="utf-8">
        <style>

            #scheduler_here{
                left : 20%;
            }
            
            html, body{
                margin:0px;
                padding:0px;
                height:100%;
                width: 100%;
                overflow:hidden;
            }


        </style> 
    </head> 
    <body> 
    <div id="scheduler_here" class="dhx_cal_container" style='width:70%; height:90%;'> 
        <div class="dhx_cal_navline"> 
            <div class="dhx_cal_prev_button">&nbsp;</div> 
            <div class="dhx_cal_next_button">&nbsp;</div> 
            <div class="dhx_cal_today_button"></div> 
            <div class="dhx_cal_date"></div> 
            <div class="dhx_cal_tab" name="day_tab"></div> 
            <div class="dhx_cal_tab" name="week_tab"></div> 
            <div class="dhx_cal_tab" name="month_tab"></div> 
    </div> 
    <div class="dhx_cal_header"></div> 
    <div class="dhx_cal_data"></div> 
    </div> 
    <script>

        scheduler.init('scheduler_here', new Date(), "month");
        scheduler.load("index.php/evenements/tableau_de_bord_data/<?=$numUser?>");
 
        var dp = new dataProcessor("index.php/evenements/tableau_de_bord_data/<?=$numUser?>");
        dp.init(scheduler);
        dp.setTransactionMode("JSON"); // use to transfer data with JSON
        scheduler.config.icons_edit = [];
                scheduler.config.icons_select = [];
                scheduler.config.details_on_dblclick = false;
                scheduler.config.drag_create = false;
                scheduler.attachEvent("onClick", function (numEvent){
                    window.location = "/index.php/evenements/reunion/"+numEvent;
                });
                
                scheduler.attachEvent("onEmptyClick", function (date, e){
                    window.location = "/index.php/evenements/sondages_new";
                });
    </script> 
