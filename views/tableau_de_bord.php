

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
