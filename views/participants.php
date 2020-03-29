<!DOCTYPE html>
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
          <div class="table-responsive">
               <table class="table table-striped table-bordered">
                    <tr>
                         <th width="5%">Identifiant</th>
                         <th width="25%">Picture</th>
                         <th width="35%">Nom</th>
                         <th width="10%">Prénom</th>
                         <th width="20%">Email</th>
                         <th width="5%">Statut</th>
                         <th width="5%">Participation</th>
                    </tr>
                    <tbody id="search_table">
                         <?php foreach ($infos_participants as $infos_participant) { ?>
                              <tr>
                                   <td><?= $infos_participant['numUser'] ?></td>
                                   <td><img src="/index.php/evenements/photos_get/<?= $infos_participant['numUser'] ?>" alt="photo"></td>
                                   <td><?= $infos_participant['nom'] ?></td>
                                   <td><?= $infos_participant['prenom'] ?></td>
                                   <td><?= $infos_participant['email'] ?></td>
                                   <td><?= $infos_participant['statut'] ?></td>
                                   <?php if ($infos_participant['participation'] === '1') { ?>
                                        <td>Oui</td>
                                   <?php } else { ?>
                                        <td>Non</td>
                                   <?php }   ?>
                              </tr>
                         <?php } ?>
                    </tbody>
               </table>
          </div>
     </div>
</body>

</html>
<script type="text/javascript" src="/assets/js/search_table.js"></script>