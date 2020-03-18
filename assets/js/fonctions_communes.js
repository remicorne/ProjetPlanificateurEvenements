/////////////////// Fonctions communes à toutes les pages. ////////////////////////////////////////////

function init(){}
/**
*Fonction pour lire un cookie de nom donné en paramètre .
*/
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

/**
*Fonction pour ajouter une colonne à un tableau.
*/
function addColumn(tdOrTh, contenu){
	var col = document.createElement(tdOrTh);
	col.innerHTML=contenu;
	return col ;
}

/**
*Fonction pour créer une connexion asynchrone.
*voir utilisation dans fonction findPerson.
*/
function xhrGET(url, typeRep){
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



