/**
*Fonction qui ajoute les utilisateurs au tableau 'tab_persons' de la page ajout_des_participants.
*les utilisateurs sont ajoutés au tableau en fonction de ce qui est tapé dans l'input 'input_personne'.
*/

$(document).ready(function(){
	remplirTabGroupesCherches("tab_groupes_cherches",numEvent);
    afficherParticipantsEvent('tab_participants' ,numEvent);
    refreshDocTable(numEvent); 
})

function remplirTabPersonsCherches(idTab,input,numEvent){
	$('#'+idTab).html("");
	var nom = input.value;
	var requete = $.ajax({
		url: "/index.php/evenements/users_from_nom_js/"+nom
	});
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


function creerDivNouveauDocument(){ //crée le necessaire pour ajouter un document (input nom, input doc, etc...)
	$("#nouveau_document").hide();
	var divAjoutDocument = $("<div></div>").attr({"id" : "ajout_des_documents"});
	var newDoc = $("<input></input>").attr({
		"type" : "file",
		"id" : "newDoc",
		"name" : "document"
	});
	newDoc.change(function(){isNameTaken(docName.val())})
	var docName = $("<input></input>").attr({
		"type" : "text",
		"id" : "docName",
		"placeholder" : "Nommez votre fichier"
	});
	docName.keyup(function(){isNameTaken(docName.val())})
	var uploader = $("<button></button>").attr({
		"id" : "uploader"
		});
	var messageErreur = $("<span></span>").attr({
		"id" : "error_message"
		});	
	uploader.text("Upload");
	uploader.click(function(){uploadDocument()});
	divAjoutDocument.append(newDoc, docName, " taille < 50Mo<br>", uploader, messageErreur);
	$("#div_documents_ajax").append(divAjoutDocument);
}

function uploadDocument(){
	var newDoc = $("#newDoc");
	var docName = $("#docName");
	var file =  newDoc[0].files[0]; //recupère le document dans le input
	var extension = getFileExtension(file['name']);
	if (isNameValid(docName) && !nameIsTaken && file != null && file.size < 50000000) {
		var formData = new FormData();
		formData.append("document", file, $("#docName").val()+extension); 
		$.ajax({
			type: "POST",
			url: "/index.php/evenements/add_document/"+numEvent,
			data:formData,
			processData: false, //par défaut .post() converti la data en string, ici il ne faut pas puisqu'on envoie un file (je crois)
			contentType: false, // Je sais plus pourquoi mais par défaut c'est vrai et ca bloque le transfert
			success: function (data) {
				$("#nouveau_document").show();
				$("#ajout_des_documents").remove();
				refreshDocTable(data)
			},
			error: function (error) {
				$("#error_message").html("Ce document ne peut pas etre ajouté");
				console.log(error);				
			}
		})
	}
}

function deleteDocument(ligneDoc, numEvent, docName){
	$.ajax({
		url: ("/index.php/evenements/delete_document/"+numEvent+"/"+docName),
		success: function(){
			ligneDoc.remove();
		},
		error: function (error) {
			alert("Impossible de supprimer ce document");
			console.log(error);
		
	}})
}


function refreshDocTable(){
	$.ajax({
		url: "/index.php/evenements/get_event_documents/"+numEvent,
		success: function (data) {
			documentList = [];
			$("#tab_documents").html("");
			JSON.parse(data).forEach(function(document){
				documentList.push(document["nomDoc"]);
				var ligneDoc = $("<tr></tr>").attr({"class":"ligne_document"});
				var nomDoc = $("<a></a>").html(document["nomDoc"]);
				nomDoc.attr({"href" : "/uploads/"+numEvent+"/"+document["nomDoc"], "class":"uploaded_documents"});
				var supprimerDoc = $("<button></button>").html("X");
				supprimerDoc.attr({"class" :"supprimer_document"});
				supprimerDoc.click(function(){deleteDocument(ligneDoc, numEvent, document["nomDoc"])})
				$("#tab_documents").append(ligneDoc.append(nomDoc, supprimerDoc)); 
			})
		},
		error: function (error) {
			alert("Impossible de charger les documents ajoutés");
			console.log(error);
		},
	})

}

function isNameValid(docName){ 
	docName.css('border-color', 'initial');
	var nameFormat = /^[0-9a-zA-Z\^\&\'\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
	if (!nameFormat.test(docName.val())) { 
		docName.css('border-color', '#cc0000');
		$("#error_message").html("Lettres et chiffres autorisés seulement");
		return false;}
	return true;
}

function isNameTaken(docName){
	$("#docName").css('border-color', 'initial');
	file = $("#newDoc")[0].files[0];
	var extension = file != null ? getFileExtension(file['name']) : "";
	$.ajax({
		url: "/index.php/evenements/is_name_taken/"+numEvent+"/"+docName+extension,
		success: function (isTaken){
			nameIsTaken = isTaken == "true";
			console.log(nameIsTaken);
			if (nameIsTaken) { $("#error_message").html("Ce nom est déja pris"); $("#docName").css('border-color', '#cc0000');}
			else $("#error_message").html("");
		},
		error: function(error){
			console.log(error);
		}
	})
	  }

function getFileExtension(filename){
	var extension = filename.split('.');
	extension.splice(0, 1);
	extension.join('');
	return "."+extension; 
}