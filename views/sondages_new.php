


<div id="sondage_new">

  <form role="form" action="/index.php/evenements/creer_sondages_event" method="post" enctype="multipart/form-data">

    <p>Titre :</p>
    <input class="form-control" name="titre" type="text" maxlength="10" required />
    <p>Lieu :</p>
    <input class="form-control" name="lieu" type="text" maxlength="30" required>
    <p>Ajouter une description : </p>
    <textarea name="descri" rows="8" cols="45" maxlength="150" placeholder=" Description (facultatif)"></textarea><br>
    <div>
      <p>Dates et horaires de la réunion :</p><br>
      <button type="button" name="add" id="add" class="btn btn-success btn-xs">Add</button><br>
    </div>

    <div>
      <table class="table table-striped table-bordered" id="user_data">
        <tr>
          <th>Date</th>
          <th>Heure début</th>
          <th>Heure fin</th>
          <th>Details</th>
          <th>Remove</th>
        </tr>
      </table>
    </div>

    <input type="submit" class="btn btn-lg btn-primary btn-block" id="Terminer" value="Suivant">

  </form>

  <br />

  <div id="user_dialog" title="Ajouter une date">

    <div class="form-group"> <!-- IMPROVE calendar dat selector (jquery je crois) -->
      <label>Date </label>&nbsp;&nbsp;
      <input type="date" class="diag_sond_new" id="date" name="date" min=<?= date('Y-m-d') ?> max="2050-12-31" required>
      <span id="error_date" class="text-danger"></span>
    </div>

    <div class="form-group">
      <label>Heure début</label>&nbsp;&nbsp;
      <input type="time" class="diag_sond_new" id="horaireD" name="horaireD" required>
      <span id="error_horaireD" class="text-danger"></span>
    </div>

    <div class="form-group">
      <label>Heure fin</label>&nbsp;&nbsp;
      <input type="time" class="diag_sond_new" id="horaireF" name="horaireF" required>
      <span id="error_horaireF" class="text-danger"></span>
    </div>

    <div>
      <input type="hidden" name="row_id" id="hidden_row_id" />
      <button type="button" name="save" id="save" class="btn btn-info">Save</button>
    </div>

  </div>
</div>

<script type="text/javascript" src="/assets/js/script_page_sondages_new.js"></script>
