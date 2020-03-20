/**
*Fonction qui ajoute les utilisateurs au tableau 'tab_persons' de la page ajout_des_participants.
*les utilisateurs sont ajoutés au tableau en fonction de ce qu'y est tapé dans l'input 'input_personne'.
*/
function remplirTabPersonsCherches(idTab,input,numEvent){
	document.getElementById(idTab).innerHTML="";
	var requete = chercherDesPersonnes(input);

	requete.addEventListener('readystatechange',function(){
		if (requete.readyState === XMLHttpRequest.DONE && requete.status==200) { // La constante DONE appartient à l'objet XMLHttpRequest, elle n'est pas globale
			var persCherches = requete.response; 
			if(persCherches===null) return;
			// on remplis le tableau des personnes cherchés.
			tab = construireTableauDePersonne(idTab, persCherches);
			for (var i = 0; i < tab.children.length; i++) 
				verifierSiPersDejaAjoute(persCherches[i]['numUser'], numEvent, tab.children[i]);
		}
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
			//ligne.append(addColumn("td",'<button id="bouton" >ajouter</button> '));
			//document.getElementById("bouton").addEventListener("click",function(){ajouterParticipantsBd(persCherches[i]['numUser'])}, false);
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
		$('#tab_participants').html("");
		afficherParticipantsEvent(numEvent);
	});
}

function afficherParticipantsEvent(numEvent){
	var requete = $.ajax({
		dataType: "json",
		url:"/index.php/evenements/afficher_participants_event/"+numEvent //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function(){
		var participants = JSON.parse(requete.responseText); 
		construireTabParticipants('tab_participants', participants, numEvent);
	});
}	

function construireTabParticipants(nomTab, participants, numEvent){
	construireTableauDePersonne(nomTab, participants);
		$('#tab_participants').children().each(function(index){
			$(this).append('<td>'+participants[index]['statut']+'</td>');
			if(participants[index]['statut']!='createur'){
				$(this).append('<td><button class="retirer">retirer</button></td>');
				$(this).children(':last').click(function() { retirerParticipantBd(participants[index]['numUser'], numEvent) } );
			}
		})
}

function retirerParticipantBd(numUser, numEvent){
	var requete = $.ajax({
		url:"/index.php/evenements/retirer_participant_event/"+numUser+"/"+numEvent //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function(){
		$('#tab_persons').html("");
		$('#tab_participants').html("");
		afficherParticipantsEvent(numEvent);
	});
}