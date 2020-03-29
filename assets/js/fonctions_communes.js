/////////////////// Fonctions communes à toutes les pages. ////////////////////////////////////////////

function init() { }
/**
*Fonction pour lire un cookie de nom donné en paramètre .
*/
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

/**
*Fonction pour ajouter une colonne à un tableau.
*/
function addColumn(tdOrTh, contenu, classe) {
	var col = document.createElement(tdOrTh);
	col.innerHTML = contenu;
	if (classe != null)
		col.classList.add(classe);
	return col;
}

/**
*Fonction pour créer une connexion asynchrone.
*voir utilisation dans fonction findPerson.
*/
function xhrGET(url, typeRep) {
	var xhr = new XMLHttpRequest();
	//ouvrir la connexion et choisir type d'envoie 
	xhr.open('GET', url);
	//preciser le type de retour attendu
	xhr.responseType = typeRep;
	//envoie de la requete   
	xhr.send();
	// return l'objet XMLHttpRequest
	return xhr;
}

/////////////////// Fonctions communes au pages voir_les_groupes, creer_un_groupe, ajouter_des_paticipants  ////////////////////////////////////////////:

function construireTableauDePersonne(idTab, personnes) {
	if (personnes == null) return;
	var tab = document.getElementById(idTab);
	personnes.forEach(function (personne) {
		var newRow = document.createElement("tr");
		var numUser = personne['numUser'];
		var src = "/index.php/evenements/photos_get/" + numUser + "?thumbnail";
		newRow.appendChild(addColumn("td", '<img src="' + src + '" alt="photo de profil" height="100" width="70" />'));
		newRow.appendChild(addColumn("td", personne['nom']));
		newRow.appendChild(addColumn("td", personne['prenom']));
		newRow.appendChild(addColumn("td", personne['email'], 'email'));
		newRow.appendChild(addColumn("td", personne['numUser']));
		newRow.lastChild.style.display = 'none';
		tab.appendChild(newRow);
	});
	return tab;
}

/**
*Fonction qui recherche les utilisateurs dont les premières lettre du nom correspondent à la chaine.
*return la reponse de la requete.
*/
function chercherDesPersonnes(input) {
	var str = input.value.split(' ');
	var nom = str[0];
	var prenom = str.length > 1 && str[1] != "" ? str[1] : null;
	//encodeage pour eviter les caractères interdits dans une url
	var en_nom = encodeURIComponent(nom);
	var en_prenom = encodeURIComponent(prenom);
	if (prenom != null)
		var m_url = "/index.php/evenements/users_from_nom_prenom_js/" + en_nom + "/" + en_prenom;
	else
		var m_url = "/index.php/evenements/users_from_nom_js/" + en_nom;

	var requete = $.ajax({
		url: m_url//adresse à laquelle la requête doit être envoyée
	});
	return requete
}



