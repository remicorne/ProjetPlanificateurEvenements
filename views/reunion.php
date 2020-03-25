
<!DOCTYPE html>  

<?php var_dump($infos_reunion['titre']);?>
 <html>  
    
      <body>  
           <br /><br />  
           <div class="container" style="width:900px;">  
                                
                <br /><br />  
                <div class="form-group">
                    <div class="row">
                    
                </div>
                <br /><br />  

                <div class="table-responsive" >  
                     <table class="table table-bordered" id="employee_table">  
                         <tr>
                         <th width="5%">Ttire </th>
                         <td width="5%"><?= $infos_reunion['titre'] ?></td>
                         </tr>
                         <tr>
                         <th width="5%">Description </th>
                         <td width="25%"><?= $infos_reunion['descri']?></td>
                         </tr>
                         <tr>
                         <th width="5%">Heure de début </th>
                         <td width="25%"><?= $infos_reunion['heureD']?></td>
                         </tr>
                         <tr>
                         <th width="5%">Heure fin </th>
                         <td width="25%"><?= $infos_reunion['heureF']?></td>
                         </tr>

                         <tr>  
                               <th width="5%">Organisateur
                               <p><a href="/evenements/participants/<?=$infos_reunion['numEvent']?>">Voir les <?=$nombreParticipants?> participants de cette reunion</p></th>  
                               <td width="25%">
                               
                               <img src="/index.php/evenements/photos_get/<?=$infos_reunion['numUser']?>" alt="photo">
                              <p> Numéro:<?=$infos_reunion['numUser']?></p> 
                              <p> Prénom:<?=$infos_reunion['prenom']?> </p>
                              <p>Nom:<?=$infos_reunion['nom']?> </p>
                              <p> email:<?=$infos_reunion['email']?></p> 
                               
                               </td>  
                          </tr> 

                          <tr>
                         <th width="5%">Docuemnts</th>
                         <td width="25%"></td>
                         </tr>



                                              </table>  
                </div>  
           </div>  
      </body>  
 </html>  