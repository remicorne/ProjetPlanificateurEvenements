<!DOCTYPE html>

<html>

<body>
     <br /><br />
     <div class="container">

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
               <table class="table table-striped table-bordered" id="reunions_table">
                    <tr>
                         <th width="5%">Numéro</th>
                         <th width="25%">Date</th>
                         <th width="35%">Heure</th>
                         <th width="10%">Durée</th>
                         <th width="20%">Sujet</th>
                         <th width="5%">Nombre de d'invités</th>
                         <th width="5%">Nombre de participants</th>
                    </tr>

                    <?php foreach ($infos_reunions as $infos_sondage) {
                         $datedebut = new DateTime($infos_sondage['date_sond'] . " " . $infos_sondage['heureD'] . ":00");
                         $datefin = new DateTime($infos_sondage['date_sond'] . " " . $infos_sondage['heureF'] . ":00");
                         $datediff = $datedebut->diff($datefin);
                         $date_format = $datediff->format("%H:%I");

                    ?>
                         <tr onclick="goToReunion(<?= $infos_sondage['numEvent'] ?>)">
                              <td><?= $infos_sondage['numEvent'] ?></td>
                              <td><?= $infos_sondage['date_sond'] ?></td>
                              <td><?= $infos_sondage['heureD'] ?></td>
                              <td><?= $date_format ?></td>
                              <td><a><?= $infos_sondage['titre'] ?></a></td>
                              <td> <?= $nombre_inv_array[$infos_sondage['numEvent']] ?></td>
                              <td> <?= $nombre_part_array[$infos_sondage['numEvent']] ?></td>
                         </tr>
                    <?php } ?>
               </table>
          </div>
     </div>
</body>

</html>
<script type="text/javascript" src="/assets/js/search_table.js"></script>