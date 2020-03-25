<!DOCTYPE html>  

<?php// var_dump($infos_reunions);?>
 <html>  
    
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">  
                                
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
                     <table class="table table-striped table-bordered" id="reunions_table">  



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
                               <td><a href="/evenements/reunion/<?=$infos_sondage['numEvent']?>/<?=$infos_sondage['nombreParticipant']?>"><?=$infos_sondage['numEvent']?></td>  
                               <td><?=$infos_sondage['date_sond']?></td>  
                               <td><?=$infos_sondage['heureD']?></td>  
                               <td><?=$infos_sondage['heureF']?></td>  
                               <td><?=$infos_sondage['titre']?></td>  
                               <td><a href="/evenements/participants/<?=$infos_sondage['numEvent']?>"><?=$infos_sondage['nombreParticipant']?></td>  
                          </tr>  
                     <?php } ?> 
                                              </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script type="text/javascript" src="/assets/js/search_table.js"></script>