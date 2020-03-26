//fonction auto appelante au chargement du script.
(function () {
	alertModifpriseEnCompte();
})();

function alertModifpriseEnCompte(){
	if(readCookie('modif')!==null)
	{
		alert('Le nom du groupe à bien été modifié.');
		document.cookie = "modif=; expires=Mon, 02 Oct 2000 01:00:00 GMT; path=/";
	}
}

function ecrire_ds_cookie_modif(){
	document.cookie = 'modif=true; path=/'; 
	return true;
}

function alert_suppression(){
	return confirm("Etes-vous sur de vouloir supprimer le groupe.");
}

function remplirTabPersonsCherches(idTab,input,numGroupe){
	$('#'+idTab).html("");
	var requete = chercherDesPersonnes(input);
	requete.done(function() {
		var persCherches = JSON.parse(requete.responseText); 
		if(persCherches===null) return;
		// on remplis le tableau des personnes cherchés.
		tab = construireTableauDePersonne(idTab, persCherches);
		for (var i = 0; i < tab.children.length; i++) 
			verifierSiPersDejaAjoute(persCherches[i]['numUser'], numGroupe, tab.children[i]);
	});
}

function verifierSiPersDejaAjoute(numUser, numGroupe, ligne){
	var requete = $.ajax({
		url:"/index.php/evenements/user_deja_ajoute_au_groupe/"+numUser+"/"+numGroupe,
		method:"GET"  //type de la requête, GET ou POST (GET par défaut).
	});
	requete.done(function(){
		if(requete.responseText==='false'){
			$(ligne).append('<td><button>ajouter</button></td> ');
			$(ligne).children(':last').click( function() { window.location.replace("/index.php/evenements/ajout_user_groupe/"+numUser+"/"+numGroupe) } );
		}
		else
			$(ligne).append('<td>deja ajouté</td>');
	})
}



