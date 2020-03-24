<script type="text/javascript" src="/assets/js/script_gestion_des_participants.js"></script>

<div class="container">
  <br /><br />

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

  <!-- remplis avec fonction js -->
  <div id="div_documents_ajoutes"> 
    <p> Tableau des documents ajoutés</p>
    <table id="tab_documents" > 
    </table>
  </div>

  <!-- ajout des documents -->
  <div id="div_documents"> 
  <button id="button_ajouterDocument" onclick="ajouterDocument()">Ajouter des document</button>
    <form enctype="multipart/form-data" method="post" action="/index.php/users/document_add">
        <div id="div_input_documents">
        </div>
        <button id="button_submit" type="submit">Soumettre</button>
    </form>
  </div>

  <!-- FormData -->
  <div id="div_documents_ajax"> 
  <button id="nouveau_document" onclick="nouveauDocument()">nouveau document</button>
  </div>

  <script>
  var numEvent = <?=$numEvent?>;
    remplirTabGroupesCherches("tab_groupes_cherches",<?= $numEvent ?>);
    afficherParticipantsEvent('tab_participants' ,<?= $numEvent ?>);
    refreshDocTable(<?=$numEvent?>); 
  </script>
</div>
