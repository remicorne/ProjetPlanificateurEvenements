function creationLigneTabUserData(numLigne, date, horaireD, horaireF) {
  var output;
  output = '<tr id="row_' + numLigne + '">';
  output += creationColonnesTabUserData(numLigne, date, horaireD, horaireF)
  output += '</tr>';
  return output;
}
function creationColonnesTabUserData(numLigne, date, horaireD, horaireF) {
  var output;
  output += '<td>' + date + ' <input type="hidden" name="dates[]" id="date' + numLigne + '" class="date" value="' + date + '" /></td>';
  output += '<td>' + horaireD + ' <input type="hidden" name="horairesD[]" id="horaireD' + numLigne + '" value="' + horaireD + '" /></td>';
  output += '<td>' + horaireF + ' <input type="hidden" name="horairesF[]" id="horaireF' + numLigne + '" value="' + horaireF + '" /></td>';
  output += '<td><button type="button" name="view_details" class="btn btn-warning btn-xs view_details" id="' + numLigne + '">View</button></td>';
  output += '<td><button type="button" name="remove_details" class="btn btn-danger btn-xs remove_details" id="' + numLigne + '">Remove</button></td>';
  return output;
}


$(document).ready(function () {     //Attendre la disponibilité du DOM (obligatoire comme le main)

  var count = 0;

  // informe que le div user_dialog est un dialog 
  $('#user_dialog').dialog({
    autoOpen: false,
    width: 350
  });

  // ajout d'un fonction qui ouvre le dialog au click sur le boutton add. 
  $('#add').click(function () {
    if ($('.date').length > 3) {
      alert("Quatre date maximun autorisées.");
      return;
    }
    $('#user_dialog').dialog('option', 'title', 'Ajouter une date');
    $('#date').val('');
    $('#horaireD').val('');
    $('#horaireF').val('');
    $('#save').text('Save');
    $('#user_dialog').dialog('open');
  });

  // mise en place des action sur au click sur le bouton save du dialog.
  $('#save').click(function () {
    var error_date = '';
    var error_horaireD = '';
    var error_horaireF = '';
    var date = '';
    var horaireD = '';
    var horaireF = '';

    // enregistrement de la date dans la variable date..
    error_date = $('#date').val() == '' ? 'Date is required' : '';
    if ($('#date').val() == '')
      $('#date').css('border-color', '#cc0000');
    else
      $('#date').css('border-color', '');
    date = $('#date').val();
    $('#error_date').text(error_date);

    // enregistrement de l'heure de debut dans la variable horaireD.
    error_horaireD = $('#horaireD').val() == '' ? 'Heure début is required' : '';
    if ($('#horaireD').val() == '')
      $('#horaireD').css('border-color', '#cc0000');
    else
      $('#horaireD').css('border-color', '');
    horaireD = $('#horaireD').val();
    $('#error_horaireD').text(error_horaireD);

    // enregistrement de l'heure de fin dans la variable horaireF.
    error_horaireF = $('#horaireF').val() == '' ? 'Heure fin is required' : '';
    if ($('#horaireF').val() == '')
      $('#horaireF').css('border-color', '#cc0000');
    else
      $('#horaireF').css('border-color', '');
    horaireF = $('#horaireF').val();
    $('#error_horaireF').text(error_horaireF);

    // on verifie que le debut de la réunion n'est pas dans le passé.
    if (new Date() > new Date($('#date').val() + 'T' + horaireD)) {
      alert("Le debut de la réunion ne peut pas être dans le passé.");
      return;
    }
    // on verifie que l'heure de début soir bien avant l'heure de fin.
    if (new Date($('#date').val() + 'T' + horaireD) > new Date($('#date').val() + 'T' + horaireF)) {
      alert("L'heure de debut doit être antérieur à l'heure de fin.");
      return
    }
    // s'il y a une erreur on sort de la fonction.
    if (error_date != '' || error_horaireD != '' || error_horaireF != '') return;

    // si bouton save 
    if ($('#save').text() == 'Save') {
      count++;
      $('#user_data').append(creationLigneTabUserData(count, date, horaireD, horaireF));
    }
    else // si bouton edit.
      $('#row_' + $('#hidden_row_id').val()).html(creationColonnesTabUserData($('#hidden_row_id').val(), date, horaireD, horaireF));

    $('#user_dialog').dialog('close');
  });

  // si l'utilisateur appuie sur le bouton 'view'.
  $(document).on('click', '.view_details', function () {
    var row_id = $(this).attr("id");
    var date = $('#date' + row_id + '').val();
    var horaireD = $('#horaireD' + row_id + '').val();
    var horaireF = $('#horaireF' + row_id + '').val();
    $('#date').val(date);
    $('#horaireD').val(horaireD);
    $('#horaireF').val(horaireF);
    $('#save').text('Edit');
    $('#hidden_row_id').val(row_id);
    $('#user_dialog').dialog('option', 'title', 'Edit Data');
    $('#user_dialog').dialog('open');
  });

  // si l'utilisateur appuie sur le bouton 'remove'.
  $(document).on('click', '.remove_details', function () {
    var row_id = $(this).attr("id");
    if (confirm("Are you sure you want to remove this row data?"))
      $('#row_' + row_id + '').remove();
  });

  // appuie sur le bouton 'terminer'.
  $('form').submit(function () {
    if ($('.date').length != 0) return true;
    alert("Veuillez selectionner une date.");
    return false;
  });

});