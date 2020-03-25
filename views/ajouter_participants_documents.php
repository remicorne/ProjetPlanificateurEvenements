<script type="text/javascript" src="/assets/js/script_participants_documents.js"></script>

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
    <table style="display: inline-block; overflow:auto;" id="tab_participants" > 
    </table>
  </div>

  <!-- remplis avec fonction js -->
  <div id="div_documents_ajoutes"> 
    <p> Documents ajoutés</p>
      <div style="overflow:auto;">
        <table style="display: inline-block; overflow:auto;" id="tab_documents" > 
        </table>
      </div>
  </div>

  <!-- FormData -->
  <div id="div_documents_ajax"> 
  <button id="nouveau_document" onclick="creerDivNouveauDocument()">Ajouter un document</button>
  </div>

  <script>
  var numEvent = <?=$numEvent?>;
  </script>
</div>
