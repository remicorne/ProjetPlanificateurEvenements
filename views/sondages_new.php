<div id="sondage_new">
	<form role="form" action="/index.php/evenements/sondages_add" method="post" enctype="multipart/form-data">
		<p>Titre :</p>
		<input class="form-control"  name="titre" type="text"  required/>
		<p>Lieu :</p>
		<input class="form-control"  name="lieu" type="text" required>
		<p>Ajouter une description : </p>
		<textarea name="message" rows="8" cols="45" placeholder=" Description (facultatif)"></textarea><br>
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
    <input type="date" id="date" name="date" value=<?=date('Y-m-d')?> min=<?=date('Y-m-d')?> max="2050-12-31"  required >
    <span id="error_date" class="text-danger"></span>
   </div>
   <div class="form-group">
    <label>Heure début</label>&nbsp;&nbsp;
	<input type="time" id="horaireD" name="horaireD" required> 
    <span id="error_horaireD" class="text-danger"></span>
   </div>
   <div class="form-group">
    <label>Heure fin</label>&nbsp;&nbsp;
    <input type="time" id="horaireF" name="horaireF"  required> 
    <span id="error_horaireF" class="text-danger"></span>
   </div>
   <div>
    <input type="hidden" name="row_id" id="hidden_row_id" /> <!--  c'est comme une variable intermédiaire  pour éditer la ligne date du tableau -->
    <button type="button" name="save" id="save" class="btn btn-info"></button>
   </div>
</div>

</div>



<script>  
$(document).ready(function(){     //Attendre la disponibilité du DOM (obligatoire comme le main)
 
 var count = 0;

 $('#user_dialog').dialog({  // l'ajout de la fenetre pour ajouter les dates et heures 
  autoOpen:false,// par défaut elle est fermée 
  width:350  //taille de la fenetre
 });

 $('#add').click(function(){ // s'execute lorsque on clique sur add

  $('#user_dialog').dialog('option', 'title', 'Ajouter une date'); // ajouter un titre à la fenetre
  $('#date').val('');  //initialisation de la valeur de la  date 
  $('#horaireD').val(''); //initialisation de la valeur de heure début
  $('#horaireF').val('');//initialisation de la valeur de heure fin
  $('#error_date').text(''); 
  $('#error_horaireD').text('');
  $('#error_horaireF').text('');
  $('#date').css('border-color', '');
  $('#horaireD').css('border-color', '');
  $('#horaireF').css('border-color', '');
  $('#save').text('Save');  // Nommer le bouton save
  $('#user_dialog').dialog('open'); // la fenetre des dates et heures s'ouvre
 });

 $('#save').click(function(){  //s'execute lorsque on clique sur save
 /* Décalaration des variables */
  var error_date = '';
  var error_horaireD = '';
  var error_horaireF = '';
  var date = '';
  var horaireD = '';
  var horaireF = '';

  if($('#date').val() == '') // si l'utilisateur n'a pas saisie la date 
  {
   error_date = 'Date is required'; //afficher un message d'erreur
   $('#error_date').text(error_date);
   $('#date').css('border-color', '#cc0000'); //css du message d'erreur
   date = ''; 
  }
  else //si non
  {

   error_date = '';
   $('#error_date').text(error_date); // on efface le message d'erreur s'il existe

   $('#date').css('border-color', ''); 
   date = $('#date').val(); //on recupére la valeur de la date
  } 
  /* meme procedure pour les autres champs */
  if($('#horaireD').val() == '')
  {
   error_horaireD = 'Heure début is required';
   $('#error_horaireD').text(error_horaireD);
   $('#horaireD').css('border-color', '#cc0000');
   horaireD = '';
  }
  else
  {
   error_horaireD = '';
   $('#error_horaireD').text(error_horaireD);
   $('#horaireD').css('border-color', '');
   horaireD = $('#horaireD').val();
  }

  if($('#horaireF').val() == '')
  {
   error_horaireF = 'Heure fin is required';
   $('#error_horaireF').text(error_horaireF);
   $('#horaireF').css('border-color', '#cc0000');
   horaireF = '';
  }
  else
  {
   error_horaireF = '';
   $('#error_horaireF').text(error_horaireD);
   $('#horaireF').css('border-color', '');
   horaireF = $('#horaireF').val();
  }



  if(error_date != '' || error_horaireD != '' || error_horaireF != '') 
  {
   return false; // le formulaire ne peut pas etre envoyé 
  }
  else
  {
   if($('#save').text() == 'Save')  // lorsque la fenetre pour ajouter les dates est ouverte 
   {
     /* On remplit la ligne du tableau en donnant des id pour chaque colonne*/
    count = count + 1;
    output = '<tr id="row_'+count+'">';   // id d'une ligne du tableau
    output += '<td>'+date+' <input type="hidden" name="date[]" id="date'+count+'" class="date" value="'+date+'" /></td>'; //colonne date
	output += '<td>'+horaireD+' <input type="hidden" name="horaireD[]" id="horaireD'+count+'" value="'+horaireD+'" /></td>'; // colonne heure début
	output += '<td>'+horaireF+' <input type="hidden" name="horaireF[]" id="horaireF'+count+'" value="'+horaireF+'" /></td>'; // colonne heure fin
	output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+count+'">View</button></td>'; //bouton détails
    output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+count+'">Remove</button></td>'; //bouton remove
    output += '</tr>';
    $('#user_data').append(output);  //on ajoute la ligne au tableau
   }
   else // if $('#save').text() == 'Edit'  Pour modifier une date 
   {
    var row_id = $('#hidden_row_id').val();  // récupérer l'id de la date cliquée
    output = '<td>'+date+' <input type="hidden" name="date[]" id="date'+row_id+'" class="fate" value="'+date+'" /></td>';
	output += '<td>'+horaireD+' <input type="hidden" name="horaireD[]" id="horaireD'+row_id+'" value="'+horaireD+'" /></td>';
	output += '<td>'+horaireF+' <input type="hidden" name="horaireF[]" id="horaireF'+row_id+'" value="'+horaireF+'" /></td>';
	output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+row_id+'">View</button></td>';
    output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+row_id+'">Remove</button></td>';
    $('#row_'+row_id+'').html(output);  // on modifier l'html de la date qu'on veut éditer
   }

   $('#user_dialog').dialog('close');  //on ferme la fenetre  
  }
 });

 $(document).on('click', '.view_details', function(){  //s'execute lorsque on clique sur détails
  var row_id = $(this).attr("id"); // récupérer l'id de la date
  var date = $('#date'+row_id+'').val();   //récupérer la valeur de la date
  var horaireD = $('#horaireD'+row_id+'').val(); //récupérer la valeur d'heure début
  var horaireF = $('#horaireF'+row_id+'').val(); //récupérer la valeur d'heure fin
  $('#date').val(date);    
  $('#horaireD').val(horaireD);
  $('#horaireF').val(horaireF);
  $('#save').text('Edit'); //on change le nom du bouton 
  $('#hidden_row_id').val(row_id);  // sert à modifier la date dans le code en haut  
  $('#user_dialog').dialog('option', 'title', 'Editer la date'); 
  $('#user_dialog').dialog('open');
 });

 

 $(document).on('click', '.remove_details', function(){  //s'execute lorsque on clique sur remove
  var row_id = $(this).attr("id");
  if(confirm("Are you sure you want to remove this date?"))  //message de confirmation
  {
   $('#row_'+row_id+'').remove(); //supprimer la date
  }
  else
  {
   return false;
  }
 });

 $('#action_alert').dialog({
  autoOpen:false
 });

 $('#Terminer').on('submit', function(event){
  event.preventDefault();
  var count_data = 0;
  $('.date').each(function(){  //on compte nombre de date ajoutée
   count_data = count_data + 1;
  });
  if(count_data > 0)  //si il y a au moins une date en envoie le formulaire 
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"/index.php/evenements/sondages_add",
    method:"POST",
    data:form_data,
    success:function(data)  
    {
     $('#user_data').find("tr:gt(0)").remove(); //Sélectionnez tous les éléments <tr> après la 1ere ligne en-tete et les supprimer
     $('#action_alert').html('<p>Date Inserted Successfully</p>');
     $('#action_alert').dialog('open');
    }
   })
  }
  else
  {
   $('#action_alert').html('<p>Veuillez ajouter au moins une date</p>');
   $('#action_alert').dialog('open');
  }
 });
 
});  
</script>

