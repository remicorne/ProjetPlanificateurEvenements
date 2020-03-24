<script type="text/javascript" src="/assets/js/script_gestion_des_participants.js"></script>

<div class="container">
  
  <?php if (isset($error)) { ?>
    <div class="alert alert-warning" role="alert"><?= $error ?></div>
  <?php } ?>

  <div id="div_persons_cherches" >
      <p>Ajouter les participants</p>
      <input type="text" id="input_recherche_personnes" onkeyup="remplirTabPersonsCherches('tab_persons', this, <?= $numEvent ?>)">
      <button class="btn btn-light">Q</button>
      <!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
      <table id="tab_persons"></table>
  </div>

  <!-- remplis avec fonction js 'remplirTabGroupesCherches' -->
  <div id="div_groupes_cherches">
    <p>Ajouter les groupes</p>
    <table id="tab_groupes_cherches"></table> 
  </div>

  <!-- remplis avec fonction js -->
  <div id="div_participants"> 
    <p> Tableau des participants ajoutés</p>
    <table id="tab_participants" > 
    </table>
  </div>
</div>

<script>
    remplirTabGroupesCherches("tab_groupes_cherches",<?= $numEvent ?>);
    afficherParticipantsEvent('tab_participants' ,<?= $numEvent ?>);
</script>
