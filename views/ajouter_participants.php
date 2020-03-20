<script type="text/javascript" src="/assets/js/script_gestion_des_participants.js"></script>

<div class="container" style="width:600px;">
  <br /><br />

  <div class="form-group">
      <p>Ajouter les participants</p>
      <input type="text" id="input_recherche" class="form-control"  onkeyup="remplirTabPersonsCherches('tab_persons', this,<?= $numEvent ?>)">
      <button class="btn btn-light">Q</span>
  </div>

  <!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
  <div id="display_persons">
    <table id="tab_persons" name="tab_persons"></table> 
  </div>

  <!-- remplis avec fonction js -->
  <div id="div_participants"> 
    <table id="tab_participants" ></table> 
  </div>
  
  <script>afficherParticipantsEvent(<?= $numEvent ?>);</script>
</div>
