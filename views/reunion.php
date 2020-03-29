<script type="text/javascript" src="/assets/js/script_reunion.js"></script>
<div class="container">

  <?php if (isset($error)) { ?>
    <div class="alert alert-warning" role="alert"><?= $error ?></div>
  <?php } ?>

  <div class="form-group" id="div_tableau_view_evenement">
    <div class="table-responsive">
      <table class="table table-bordered" id="tableau_view_evenement">
        <tr>
          <th width="5%">titre </th>
          <td><?= $infos_event['titre'] ?></td>
        </tr>
        <tr>
          <th width="5%">Organisateur</th>
          <td>
            <?= $organisateur['prenom'] ?> <?= $organisateur['nom'] ?> <br>
            (<?= $organisateur['email'] ?>)
          </td>
        </tr>
        <tr>
          <th width="5%">Lieu</th>
          <td> <?= $infos_event['lieu'] ?> </td>
        </tr>
        <tr>
          <th width="5%">
            Date <br>
            <?php if ($sondage) { ?> (en sondage) <?php } ?>
          </th>
          <?php foreach ($dates as $date) { ?>
            <td>
              <?= $date['date_sond'] ?> <br>
              <?= $date['heureD'] ?> - <?= $date['heureF'] ?>
            </td>
          <?php } ?>
        </tr>
      </table>
    </div>

    <?php if (!$sondage) { ?>
      <div id="div_boutons_participation_view_evenement">
        <form method="post" action="/index.php/evenements/modifier_participation_event/<?= $numEvent ?>/<?= !$participation ?>">
          <?php if (!$participation) { ?>
            <button class="btn btn-success">participer</button>
          <?php } else { ?>
            <button class="btn btn-danger">ne pas paticiper</button>
          <?php } ?>
        </form>
      </div>
    <?php } ?>

    <?php if (!$sondage) { ?>
      <div id="div_boutons_participation_view_evenement">
        <form method="post" action="/index.php/evenements/modifier_participation_event/<?= $numEvent ?>/<?= !$participation ?>">
          <?php if (!$participation) { ?>
            <button class="btn btn-success">participer</button>
          <?php } else { ?>
            <button class="btn btn-danger">ne pas paticiper</button>
          <?php } ?>
        </form>
      </div>
    <?php } ?>


    <div id=" div_descri_view_evenement">
      <p> <b>Description : </b></p>
      <textarea id="textarea_description" rows="15" cols="80" disabled>
        <?= $infos_event['descri'] ?>
      </textarea>
    </div>

    <!-- remplis avec fonction js -->
    <div id="div_documents_ajoutes_view_evenement" class="divDroite">
      <p> <b>Documents ajoutés : </b></p>
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

    <?php if ($is_administrator) { ?>
      <div id="div_persons_cherches_view_evenement" class="divDroite">
        <p><b>Ajouter les Invites :</b></p>
        <input type="text" id="input_recherche_personnes" class="form-control" onkeyup="remplirTabPersonsCherches('tab_persons', this, <?= $numEvent ?>, <?= $is_administrator ?>)">
        <!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
        <table id="tab_persons" class="table table-bordered"></table>
      </div>
    <?php } ?>

    <!-- remplis avec fonction js 'remplirTabGroupesCherches' -->
    <?php if ($is_administrator) { ?>
      <div id="div_groupes_cherches_view_evenement" class="divDroite">
        <p><b>Ajouter les groupes :</b></p>
        <table id="tab_groupes_cherches" class="table table-bordered"></table>
      </div>
    <?php } ?>


    <!-- remplis avec fonction js -->
    <div id="div_invites_view_evenement">
      <p></p>
      <div id="div_b_email_view_evenement">
        <a href="/index.php/evenements/envoyer_mails_invitation_sondage/<?= $numEvent ?>"><button>Envoyer un email au participants et terminer</button></a>
      </div>
      <table id="tab_invites" class="table table-bordered">
      </table>
    </div>

    <?php if (!$sondage) { ?>
      <!-- remplis avec fonction js -->
      <div id="div_participants_view_evenement">
        <p></p>
        <table id="tab_participants" class="table table-bordered">
        </table>
      </div>
    <?php } ?>

  </div>

  <script>
    var numEvent = <?= $numEvent ?>;
  </script>

  <?php if ($is_administrator) { ?>
    <script>
      var isAdministrateur = 1;
    </script>
  <?php } else { ?>
    <script>
      var isAdministrateur = 0;
    </script>
  <?php } ?>