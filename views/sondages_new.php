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
    <input type="hidden" name="row_id" id="hidden_row_id" />
    <button type="button" name="save" id="save" class="btn btn-info">Save</button>
   </div>
</div>

</div>



<script>  
$(document).ready(function(){ 
 
 var count = 0;

 $('#user_dialog').dialog({
  autoOpen:false,
  width:350
 });

 $('#add').click(function(){
  $('#user_dialog').dialog('option', 'title', 'Ajouter une date');
  $('#date').val('');
  $('#horaireD').val('');
  $('#horaireF').val('');
  $('#error_date').text('');
  $('#error_horaireD').text('');
  $('#error_horaireF').text('');
  $('#date').css('border-color', '');
  $('#horaireD').css('border-color', '');
  $('#horaireF').css('border-color', '');
  $('#save').text('Save');
  $('#user_dialog').dialog('open');
 });

 $('#save').click(function(){
  var error_date = '';
  var error_horaireD = '';
  var error_horaireF = '';
  var date = '';
  var horaireD = '';
  var horaireF = '';
  if($('#date').val() == '')
  {
   error_date = 'Date is required';
   $('#error_date').text(error_date);
   $('#date').css('border-color', '#cc0000');
   date = '';
  }
  else
  {
   error_date = '';
   $('#error_date').text(error_date);
   $('#date').css('border-color', '');
   date = $('#date').val();
  } 
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
   return false;
  }
  else
  {
   if($('#save').text() == 'Save')
   {
    count = count + 1;
    output = '<tr id="row_'+count+'">';
    output += '<td>'+date+' <input type="hidden" name="date[]" id="date'+count+'" class="date" value="'+date+'" /></td>';
	output += '<td>'+horaireD+' <input type="hidden" name="horaireD[]" id="horaireD'+count+'" value="'+horaireD+'" /></td>';
	output += '<td>'+horaireF+' <input type="hidden" name="horaireF[]" id="horaireF'+count+'" value="'+horaireF+'" /></td>';
	output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+count+'">View</button></td>';
    output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+count+'">Remove</button></td>';
    output += '</tr>';
    $('#user_data').append(output);
   }
   else
   {
    var row_id = $('#hidden_row_id').val();
    output = '<td>'+date+' <input type="hidden" name="date[]" id="date'+row_id+'" class="fate" value="'+date+'" /></td>';
	output += '<td>'+horaireD+' <input type="hidden" name="horaireD[]" id="horaireD'+row_id+'" value="'+horaireD+'" /></td>';
	output += '<td>'+horaireF+' <input type="hidden" name="horaireF[]" id="horaireF'+row_id+'" value="'+horaireF+'" /></td>';
	output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="'+row_id+'">View</button></td>';
    output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="'+row_id+'">Remove</button></td>';
    $('#row_'+row_id+'').html(output);
   }

   $('#user_dialog').dialog('close');
  }
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
  {
   $('#row_'+row_id+'').remove();
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
  $('.date').each(function(){
   count_data = count_data + 1;
  });
  if(count_data > 0)
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"/index.php/evenements/sondages_add",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     $('#user_data').find("tr:gt(0)").remove();
     $('#action_alert').html('<p>Data Inserted Successfully</p>');
     $('#action_alert').dialog('open');
    }
   })
  }
  else
  {
   $('#action_alert').html('<p>Please Add atleast one data</p>');
   $('#action_alert').dialog('open');
  }
 });
 
});  
</script>

