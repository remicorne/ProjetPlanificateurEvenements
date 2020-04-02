/**
*Fonction qui ajoute les utilisateurs au tableau 'tab_persons' de la page ajout_des_Invites.
*les utilisateurs sont ajoutés au tableau en fonction de ce qui est tapé dans l'input 'input_personne'.
*/

$(document).ready(function () {
	$('#div_b_email').hide();
	remplirTabGroupesCherches("tab_groupes_cherches", numEvent);
	afficherInvitesEvent('tab_invites', numEvent);
	refreshDocTable(numEvent);
	$("#file_upload").hide();
	afficherParticipantsEvent()
	setInterval(afficherParticipantsEvent, '10000');
})


function remplirTabPersonsCherches(idTab, input, numEvent) {
	$('#' + idTab).html("");
	var nom = input.value;
	var requete = $.ajax({
		url: "/index.php/evenements/users_from_nom_js/" + nom
	});
	requete.done(function () {
		var persCherches = JSON.parse(requete.responseText);
		if (persCherches === null) return;
		// on remplis le tableau des personnes cherchés.
		tab = construireTableauDePersonne(idTab, persCherches);
		for (var i = 0; i < tab.children.length; i++)
			verifierSiPersDejaAjoute(persCherches[i]['numUser'], numEvent, tab.children[i]);
	});
}

function remplirTabGroupesCherches(idTab, numEvent) {
	$('#' + idTab).html("<tr> <th>nom groupe</th> <th>membres</th>");
	var requete = $.ajax({
		url: "/index.php/evenements/obtenir_les_groupes/" + numEvent
	});
	requete.done(function () {
		var groupes = JSON.parse(requete.responseText);
		console.log(groupes);
		groupes.forEach(function (groupe, index) {
			$('#' + idTab).append('<tr>' +
				'<td>' + groupe['nomGroupe'] + '</td> <td>' + groupe['nbMembre'] + '</td>' +
				'<td><button class="bAjoutGroup' + index + '" >ajouter</button></td>' +
				'<td><button class="bRetirerGroup' + index + '" >retirer</button></td>' +
				'</tr>');
			$('.bAjoutGroup' + index).click(function () { ajouterGroupeEventBd(groupe['numGroupe'], numEvent) })
			$('.bRetirerGroup' + index).click(function () { retirerGroupeEventBd(groupe['numGroupe'], numEvent) })
			prop_bouton_groupe(groupe['ajoute'], index);
		});
	});
}

function ajouterGroupeEventBd(numGroupe, numEvent) {
	var requete = $.ajax({
		url: "/index.php/evenements/ajouter_groupe_event/" + numGroupe + "/" + numEvent
	});
	requete.done(function () {
		$('#div_b_email').show();
		afficherInvitesEvent('tab_invites', numEvent);
		remplirTabGroupesCherches('tab_groupes_cherches', numEvent);
	});
}

function retirerGroupeEventBd(numGroupe, numEvent) {
	var requete = $.ajax({
		url: "/index.php/evenements/retirer_groupe_event/" + numGroupe + "/" + numEvent
	});
	requete.done(function () {
		afficherInvitesEvent('tab_invites', numEvent);
		remplirTabGroupesCherches('tab_groupes_cherches', numEvent);
	});
}

/**
*Verify si les personnes sont déjà ajoutés à la bd 'Particiants' et ajoute les boutons en fonction.
*/
function verifierSiPersDejaAjoute(numUser, numEvent, ligne) {
	var requete = $.ajax({
		url: "/index.php/evenements/participant_deja_ajoute/" + numUser + "/" + numEvent, //adresse à laquelle la requête doit être envoyée
		method: "GET"  //type de la requête, GET ou POST (GET par défaut).
	});
	requete.done(function () {
		if (requete.responseText === 'false') {
			$(ligne).append('<td><button class="bouton_part" >participant</button></td> ');
			$(ligne).children(':last').click(function () { ajouterParticipantBd(numUser, numEvent, $(this).text()) });
			$(ligne).append('<td><button class="bouton_adm" >administrateur</button></td> ');
			$(ligne).children(':last').click(function () { ajouterParticipantBd(numUser, numEvent, $(this).text()) });
		}
		else
			$(ligne).append('<td>ajouté</td>');
	})
}

function ajouterParticipantBd(numUser, numEvent, statut) {
	var requete = $.ajax({
		url: "/index.php/evenements/ajouter_participant_event/" + numUser + "/" + numEvent + "/" + statut //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function () {
		$('#tab_persons').html("");
		$('#div_b_email').show();
		afficherInvitesEvent('tab_invites', numEvent);
	});
}

function afficherInvitesEvent(nomTab, numEvent) {
	$('#tab_invites').html("");
	var requete = $.ajax({
		dataType: "json",
		url: "/index.php/evenements/afficher_participants_event/" + numEvent //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function () {
		var invites = JSON.parse(requete.responseText);
		construireTabInvites(nomTab, invites, numEvent);
	});
}

function construireTabInvites(nomTab, invites, numEvent) {
	$('#div_invites p').html(" <b> Invités : (" + invites.length + ")</b>");
	construireTableauDePersonne(nomTab, invites);
	$('#tab_invites').children().each(function (index) {
		$(this).append('<td>' + invites[index]['statut'] + '</td>');
		if (invites[index]['statut'] != 'createur' && isAdministrateur && !reunion_passe) {
			$(this).append('<td><button class="retirer">retirer</button></td>');
			$(this).children(':last').click(function () { retirerParticipantBd(invites[index]['numPart'], numEvent, nomTab) });
		}
	})
	$('#tab_invites .email').remove();
}

function retirerParticipantBd(numPart, numEvent, nomTab) {
	var requete = $.ajax({
		url: "/index.php/evenements/retirer_participant_event/" + numPart
	});
	requete.done(function () {
		$('#tab_persons').html("");
		afficherInvitesEvent(nomTab, numEvent);
	});
}

function uploadFile() {
	file = $("#file_upload")[0].files[0];
	if (file.size > 50000000)
		$("#error_message").html("Fichier trop grand (taille <50Mo)");
	fileUploadRequest(file);
}


function fileUploadRequest(file) {
	var formData = new FormData();
	formData.append("document", file, file['name']);
	$.ajax({
		type: "POST",
		url: "/index.php/evenements/add_document/" + numEvent,
		data: formData,
		processData: false, //par défaut .post() converti la data en string, ici il ne faut pas puisqu'on envoie un file (je crois)
		contentType: false, // Je sais plus pourquoi mais par défaut c'est vrai et ca bloque le transfert
		success: function (data) {
			response = JSON.parse(data);
			if (response == "success") {
				$("#error_message").html("");
				$("#file_upload").hide();
				refreshDocTable();
			}
			else if (data.includes("23000"))
				$("#error_message").html("Fichier déja ajouté");
			else
				$("#error_message").html("Ce document ne peut pas etre ajouté");
		},
		error: function (error) {
			$("#error_message").html("Ce document ne peut pas etre ajouté");
			console.log(error);
		}
	})
}

function deleteDocument(ligneDoc, numEvent, docName) {
	$.ajax({
		url: ("/index.php/evenements/delete_document/" + numEvent + "/" + docName),
		success: function () {
			ligneDoc.remove();
		},
		error: function (error) {
			alert("Impossible de supprimer ce document");
			console.log(error);
		}
	})
}



function refreshDocTable() {
	$.ajax({
		url: "/index.php/evenements/get_event_documents/" + numEvent,
		success: function (data) {
			documentList = [];
			$("#tab_documents").html("");
			JSON.parse(data).forEach(function (document) {
				documentList.push(document["nomDoc"]);
				var ligneDoc = $("<tr></tr>").attr({ "class": "ligne_document" });
				var lienDoc = $("<a></a>").html(document["nomDoc"].substring(0, 30)).attr({ "href": "/uploads/" + numEvent + "/" + document["nomDoc"], "class": "uploaded_documents" });
				var nomDoc = $("<td></td>").append(lienDoc).attr({ "class": "uploaded_document_cell" });
				if (isAdministrateur) {
					var supprimerDoc = $("<td></td>").append($("<button></button>").html("X"));
					supprimerDoc.attr({ "class": "supprimer_document" });
					supprimerDoc.click(function () { deleteDocument(ligneDoc, numEvent, document["nomDoc"]) })
				}
				$("#tab_documents").append(ligneDoc.append(nomDoc, supprimerDoc));
			})
		},
		error: function (error) {
			alert("Impossible de charger les documents ajoutés");
			console.log(error);
		},
	})
}

function showAddDocument() {
	$("#file_upload").show();
}

function afficherParticipantsEvent() {
	$('#tab_participants').html("");
	var requete = $.ajax({
		dataType: "json",
		url: "/index.php/evenements/afficher_participants_event/" + numEvent //adresse à laquelle la requête doit être envoyée
	});
	requete.done(function () {
		var invites = JSON.parse(requete.responseText);
		construireTabParticipants(invites);
	});
}

function construireTabParticipants(invites) {
	if (invites == null) return;
	var participants = [];
	invites.forEach(function (inv) {
		if (inv['participation'] == 1)
			participants.push(inv);
	});
	$('#div_participants p').html("<b>Participants : (" + participants.length + ")</b>");
	construireTableauDePersonne('tab_participants', participants);
	$("#tab_participants .email").remove();
}

function prop_bouton_groupe(ajoute, index) {
	if (ajoute == true) {
		$('.bAjoutGroup' + index).prop('disabled', true);
		$('.bAjoutGroup' + index).addClass("btn-default").removeClass("btn-success");
		$('.bRetirerGroup' + index).prop('disabled', false);
		$('.bRetirerGroup' + index).addClass("btn-danger").removeClass("btn-default");
	} else {
		$('.bAjoutGroup' + index).prop('disabled', false);
		$('.bAjoutGroup' + index).addClass("btn-success").removeClass("btn-default");
		$('.bRetirerGroup' + index).prop('disabled', true);
		$('.bRetirerGroup' + index).addClass("btn-default").removeClass("btn-danger");
	}
}

function confimerAnnulation() {
	return confirm('Etes-vous sûr de vouloir annuler cet événement ?');
}

