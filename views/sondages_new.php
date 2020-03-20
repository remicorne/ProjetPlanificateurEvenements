<div id="sondage_new">

	<form role="form" action="/index.php/evenements/creer_sondages_event" method="post" enctype="multipart/form-data">
   
    <p>Titre :</p>
		<input class="form-control"  name="titre" type="text"  required/>
		<p>Lieu :</p>
		<input class="form-control"  name="lieu" type="text" required>
		<p>Ajouter une description : </p>
		<textarea name="descri" rows="8" cols="45" placeholder=" Description (facultatif)"></textarea><br>
		<div>
		  <p>Dates et horaires de la réunion :</p><br>
		  <button type="button" name="add" id="add" class="btn btn-success btn-xs" >Add</button><br>
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
    
    <div class="form-group">
      <label>Date </label>&nbsp;&nbsp;
      <input type="date" class="diag_sond_new" id="date" name="date" min=<?=date('Y-m-d')?> max="2050-12-31"  required >
      <span id="error_date" class="text-danger"></span>
    </div>
    
    <div class="form-group">
      <label>Heure début</label>&nbsp;&nbsp;
      <input type="time" class="diag_sond_new" id="horaireD" name="horaireD" required> 
      <span id="error_horaireD" class="text-danger"></span>
    </div>
    
    <div class="form-group">
      <label>Heure fin</label>&nbsp;&nbsp;
      <input type="time" class="diag_sond_new" id="horaireF" name="horaireF"  required> 
      <span id="error_horaireF" class="text-danger"></span>
    </div>

    <div>
      <input type="hidden" name="row_id" id="hidden_row_id" />
      <button type="button" name="save" id="save" class="btn btn-info">Save</button>
    </div>

  </div>
</div>



<script>  

function creationLigneTabUserData(numLigne, date, horaireD, horaireF){
  var output;
  output = '<tr id="row_'+numLigne+'">';
  output += creationColonnesTabUserData(numLigne, date, horaireD, horaireF)
  output += '</tr>';
  return output;
}
function creationColonnesTabUserData(numLigne, date, horaireD, horaireF){
  var output;
  output += '<td>'+date+' <input type="hidden" name="dates[]" id="date'+numLigne+'" class="date" value="'+date+'" /></td>';
  output += '<td>'+horaireD+' <input type="hidden" name="horairesD[]" id="horaireD'+numLigne+'" value="'+horaireD+'" /></td>';
  output += '<td>'+horaireF+' <input type="hidden" name="horairesF[]" id="horaireF'+numLigne+'" value="'+horaireF+'" /></td>';
  output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+numLigne+'">View</button></td>';
  output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+numLigne+'">Remove</button></td>';
  return output;
}


$(document).ready(function(){     //Attendre la disponibilité du DOM (obligatoire comme le main)

  var count = 0;
 
  // informe que le div user_dialog est un dialog 
  $('#user_dialog').dialog({
    autoOpen:false,
    width:350
  });

  // ajout d'un fonction qui ouvre le dialog au click sur le boutton add. 
  $('#add').click(function(){
    $('#user_dialog').dialog('option', 'title', 'Ajouter une date');
    $('#date').val('');
    $('#horaireD').val('');
    $('#horaireF').val('');
    $('#save').text('Save');
    $('#user_dialog').dialog('open');
  });

  // mise en place des action sur au click sur le bouton save du dialog.
  $('#save').click(function(){
    var error_date = '';
    var error_horaireD = '';
    var error_horaireF = '';
    var date = '';
    var horaireD = '';
    var horaireF = '';

    // enregistrement de la date dans la variable date..
    error_date = $('#date').val() == '' ? 'Date is required' : '';       
    if($('#date').val() == '')
      $('#date').css('border-color', '#cc0000');
    else
      $('#date').css('border-color', '');
    date = $('#date').val(); 
    $('#error_date').text(error_date);

    // enregistrement de l'heure de debut dans la variable horaireD.
    error_horaireD = $('#horaireD').val() == '' ? 'Heure début is required' : '';
    if($('#horaireD').val() == '')
      $('#horaireD').css('border-color', '#cc0000');
    else
      $('#horaireD').css('border-color', '');
    horaireD = $('#horaireD').val();
    $('#error_horaireD').text(error_horaireD);

    // enregistrement de l'heure de fin dans la variable horaireF.
    error_horaireF = $('#horaireF').val() == '' ? 'Heure fin is required' : '';
    if($('#horaireF').val() == '')
      $('#horaireF').css('border-color', '#cc0000');
    else
      $('#horaireF').css('border-color', '');
    horaireF = $('#horaireF').val();
    $('#error_horaireF').text(error_horaireF);

    // on verifie que le debut de la réunion n'est pas dans le passé.
    if( new Date() > new Date( $('#date').val()+'T'+horaireD) ){
      alert("Le debut de la réunion ne peut pas être dans le passé.");
      return;
    }
    // on verifie que l'heure de début soir bien avant l'heure de fin.
    if( new Date( $('#date').val()+'T'+horaireD) > new Date( $('#date').val()+'T'+horaireF)){
      alert("L'heure de debut doit être antérieur à l'heure de fin.");
      return
    }
    // s'il y a une erreur on sort de la fonction.
    if(error_date != '' || error_horaireD != '' || error_horaireF != '') return;
     
    // si bouton save 
    if($('#save').text() == 'Save')
    {
      count++;
      $('#user_data').append(creationLigneTabUserData(count, date, horaireD, horaireF));
    }
    else // le bouton edit.
      $('#row_'+$('#hidden_row_id').val()).html(creationColonnesTabUserData($('#hidden_row_id').val(), date, horaireD, horaireF)); 

    $('#user_dialog').dialog('close');
  });


  $(document).on('click', '.view_details', function(){
    var row_id = $(this).attr("id");
    var date = $('#date'+row_id+'').val();
    var horaireD = $('#horaireD'+row_id+'').val();
    var horaireF = $('#horaireF'+row_id+'').val();
    $('#date').val(date);
    $('#horaireD').val(horaireD);
    $('#horaireF').val(horaireF);
    $('#save').text('Edit');
    $('#hidden_row_id').val(row_id);
    $('#user_dialog').dialog('option', 'title', 'Edit Data');
    $('#user_dialog').dialog('open');
  });


  $(document).on('click', '.remove_details', function(){
    var row_id = $(this).attr("id");
    if(confirm("Are you sure you want to remove this row data?"))
      $('#row_'+row_id+'').remove();
  });

  
  $('form').submit(function(){
    if( $('.date').length != 0) return true;
    alert("Veuillez selectionner une date.");
    return false;
  });

});

</script>

