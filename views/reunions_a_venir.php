<!DOCTYPE html>  

<?php var_dump($infos_reunions);?>
 <html>  
      <head>  
           <title>Webslesson Tutorial | Search HTML Table Data by using JQuery</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">  
                <h2 align="center">Search HTML Table Data by using JQuery</h2>  
                <h3 align="center">Employee Data</h3>                 
                <br /><br />  
                <div class="form-group">
                    <div class="row">
                    <div class="col-md-10">
                    <input type="text" name="search" id="search" class="form-control" />  
                    </div>
                    <div class="col-md-2">
                    <button type="button" name="search" class="btn btn-primary" id="search">Search</button>
                    </div>
                    </div>
                </div>
                <br /><br />  
            



                <div class="table-responsive" >  
                     <table class="table table-bordered" id="employee_table">  



                          <tr>  
                               <th width="5%">Numéro</th>  
                               <th width="25%">Date</th>  
                               <th width="35%">Heure</th>  
                               <th width="10%">Durée</th>  
                               <th width="20%">Sujet</th>  
                               <th width="5%">Nombre de participants</th>  
                          </tr>  


                     <?php foreach($infos_reunions as $infos_sondage){ ?>


                                                   <tr>  
                               <td><?=$infos_sondage['numEvent']?></td>  
                               <td><?=$infos_sondage['date_sond']?></td>  
                               <td><?=$infos_sondage['heureD']?></td>  
                               <td><?=$infos_sondage['heureF']?></td>  
                               <td><?=$infos_sondage['titre']?></td>  
                               <td><?=$infos_sondage['nombreParticipant']?></td>  
                          </tr>  

                     <?php } ?> 
                                              </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
      $(document).ready(function(){  
           $('#search').keyup(function(){  
                search_table($(this).val());  
           });  
           function search_table(value){  
                $('#employee_table tr').each(function(){  
                     var found = 'false';  
                     $(this).each(function(){  
                          if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)  
                          {  
                               found = 'true';  
                          }  
                     });  
                     if(found == 'true')  
                     {  
                          $(this).show();  
                     }  
                     else  
                     {  
                          $(this).hide();  
                     }  
                });  
           }  
      });  
 </script>  