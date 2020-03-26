/**
*Fonction qui ajoute les utilisateurs au tableau 'tab_persons' de la page ajout_des_participants.
*les utilisateurs sont ajoutés au tableau en fonction de ce qui est tapé dans l'input 'input_personne'.
*/
function remplirTabPersonsCherches(idTab,input,numEvent){
	$('#'+idTab).html("");
	var requete = chercherDesPersonnes(input);
	requete.done(function() {
		var persCherches = JSON.parse(requete.responseText); 
		if(persCherches===null) return;
		// on remplis le tableau des personnes cherchés.
		tab = construireTableauDePersonne(idTab, persCherches);
		for (var i = 0; i < tab.children.length; i++) 
			verifierSiPersDejaAjoute(persCherches[i]['numUser'], numEvent, tab.children[i]);
	});
}

function remplirTabGroupesCherches(idTab, numEvent){
	$('#'+idTab).html("");

	var requete = $.ajax({
		url: "/index.php/evenements/obtenir_les_groupes"
	});
	requete.done(function(){
		var groupes = JSON.parse(requete.responseText);
		groupes.forEach(function(groupe, index){ 
			$('#'+idTab).append('<tr>'+
									'<td>'+groupe['nomGroupe']+'</td> <td>'+groupe['nbMembre']+'</td>'+
									'<td><button class="bAjoutGroup'+index+'" >ajouter</button></td>'+
									'<td><button class="bRetirerGroup'+index+'" >retirer</button></td>'+
								'</tr>');
			$('.bAjoutGroup'+index).click(function() { ajouterGroupeEventBd(groupe['numGroupe'], numEvent) })
			$('.bRetirerGroup'+index).click(function() { retirerGroupeEventBd(groupe['numGroupe'], numEvent) })
		})
	})
}

function ajouterGroupeEventBd(numGroupe, numEvent){
	var requete = $.ajax({
		url:"/index.php/evenements/ajouter_groupe_event/"+numGroupe+"/"+numEvent
	});
	requete.done(function(){
		afficherParticipantsEvent('tab_participants', numEvent);
	});
} 

function retirerGroupeEventBd(numGroupe, numEvent){
	var requete = $.ajax({
		url:"/index.php/evenements/retirer_groupe_event/"+numGroupe+"/"+numEvent
	});
	requete.done(function(){
		afficherParticipantsEvent('tab_participants', numEvent);
	});
} 

/**
*Verify si les personnes sont déjà ajoutés à la bd 'Particiants' et ajoute les boutons en fonction.
*/
function verifierSiPersDejaAjoute(numUser, numEvent, ligne){
	var requete = $.ajax({
		url:"/index.php/evenements/participant_deja_ajoute/"+numUser+"/"+numEvent, //adresse à laquelle la requête doit être envoyée
		method:"GET"  //type de la requête, GET ou POST (GET par défaut).
	});
	requete.done(function(){
		if(requete.responseText==='false'){
			$(ligne).append('<td><button class="bouton_part" >participant</button></td> ');
			$(ligne).children(':last').click( function() { ajouterParticipantBd(numUser, numEvent, $(this).text()) } );
			$(ligne).append('<td><button class="bouton_adm" >administrateur</button></td> ');
			$(ligne).children(':last').click( function() { ajouterParticipantBd(numUser, numEvent, $(this).text()) } );
		}
		else
			$(ligne).append('<td>ajouté</td>');
	})
}

function ajouterParticipantBd(numUser, numEvent, statut){
	var requete = $.ajax({
		url:"/index.php/evenements/ajouter_participant_event/"+numUser+"/"+numEvent+"/"+statut //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function(){
		$('#tab_persons').html("");
		afficherParticipantsEvent('tab_participants', numEvent);
	});
}

function afficherParticipantsEvent(nomTab, numEvent){
	$('#tab_participants').html("");
	var requete = $.ajax({
		dataType: "json",
		url:"/index.php/evenements/afficher_participants_event/"+numEvent //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function(){
		var participants = JSON.parse(requete.responseText); 
		construireTabParticipants(nomTab, participants, numEvent);
	});
}	

function construireTabParticipants(nomTab, participants, numEvent){
	construireTableauDePersonne(nomTab, participants);
		$('#tab_participants').children().each(function(index){
			$(this).append('<td>'+participants[index]['statut']+'</td>');
			if(participants[index]['statut']!='createur'){
				$(this).append('<td><button class="retirer">retirer</button></td>');
				$(this).children(':last').click(function() { retirerParticipantBd(participants[index]['numPart'], numEvent, nomTab) } );
			}
		})
}

function retirerParticipantBd(numPart, numEvent, nomTab){
	var requete = $.ajax({
		url:"/index.php/evenements/retirer_participant_event/"+numPart
	});
	requete.done(function(){
		$('#tab_persons').html("");
		afficherParticipantsEvent(nomTab, numEvent);
	});
}