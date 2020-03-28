<script type="text/javascript" src="/assets/js/script_reunion.js"></script>
<div class="container">
  <div class="form-group">
    <div class="table-responsive">
      <table class="table table-bordered" id="employee_table">
        <tr>
          <th width="5%">Description </th>
          <td width="25%"><?= $infos_reunion['descri'] ?></td>
        </tr>
        <tr>
          <th width="5%">Date </th>
          <td width="25%"><?= $infos_reunion['date_sond'] ?></td>
        </tr>
        <tr>
          <th width="5%">Heure de début </th>
          <td width="25%"><?= $infos_reunion['heureD'] ?></td>
        </tr>
        <tr>
          <th width="5%">Heure fin </th>
          <td width="25%"><?= $infos_reunion['heureF'] ?></td>
        </tr>
        <tr>
          <th width="5%">Organisateurs</th> <!-- IMPROVE affichage des coordonnées en popup jQUERY -->
          <td width="25%">
            <?php foreach ($organisateurs as $organisateur) { ?>
              <p class="organisateurs"><?= $organisateur['prenom'] ?> <?= $organisateur['nom'] ?> (<?= $organisateur['email'] ?>)</p>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>

    <?php if (isset($error)) { ?>
      <div class="alert alert-warning" role="alert"><?= $error ?></div>
    <?php } ?>

    <!-- remplis avec fonction js 'remplirTabGroupesCherches' -->
    <?php if ($is_administrator) { ?>
      <div id="div_groupes_cherches">
        <p>Ajouter les groupes</p>
        <table id="tab_groupes_cherches"></table>
      </div>
    <?php } ?>


    <!-- remplis avec fonction js -->
    <div id="div_participants">
      <p> Participants</p>
      <table style="display: inline-block; overflow:auto;" id="tab_participants">
      </table>

      <?php if ($is_administrator) { ?>
        <div id="div_persons_cherches">
          <p>Ajouter les participants</p>
          <input type="text" id="input_recherche_personnes" onkeyup="remplirTabPersonsCherches('tab_persons', this, <?= $numEvent ?>)">
          <button class="btn btn-light">Q</button>
          <!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
          <table id="tab_persons"></table>
        </div>
      <?php } ?>

    </div>


    <!-- remplis avec fonction js -->
    <div id="div_documents_ajoutes">
      <p> Documents ajoutés</p>
      <?php if ($is_administrator) { ?>
        <div id="div_file_upload">
          <button onclick="showAddDocument()"> Ajouter un document</button>
          <input type="file" id="file_upload" onchange="uploadFile()"></input>
          <p id="error_message"> </p>

        </div>
      <?php } ?>
      <div>
        <table style="display: inline-block; overflow:auto;" id="tab_documents">
        </table>
      </div>
    </div>

  </div>

  <script>
    var numEvent = <?= $numEvent ?>;
  </script>