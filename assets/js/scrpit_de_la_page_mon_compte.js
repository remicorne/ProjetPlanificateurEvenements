/////////////////// Fonctions la page mon compte. ////////////////////////////////////////////

//fonction auto appelante au chargement du script.
(function () {
	affAlertMdpModifie();
})();

/**
*Fonction qui informe l'utilisateur que le mot de passe à été mis à jour via une alert.
*pour cela la fonction va lire le cookie de nom updatePass.
*si celui-ci est différent de null c'est que le mot de passe à été changé.
*une fois lue le cookie est effacé. 
*Fonction utilsé sur la page monCompte.
*/
function affAlertMdpModifie(){
	if(readCookie('updatePass')!==null)
	{
		alert('Le mot de passe à été modifié avec succé.');
		document.cookie = "updatePass=; expires=Mon, 02 Oct 2000 01:00:00 GMT; path=/";
	}
}

/**
*Cette fonction est utilisé sur la page monCompte.
*/
function validerModifNomPrenom(form){
	return confirm("Etes-vous sur de vouloir modifier votre nom et votre prenom par : "+form.elements['nom'].value+" "+form.elements['prenom'].value);
}
/**
*Fonction qui vérifie que les deux mots de passe sont identiques quand on le modifie.
*Cette fonction est utilisé sur la page monCompte.
*/
function verifierLesMotsDePasse(form){
	if(form.elements['motDePasse1'].value !== form.elements['motDePasse2'].value){
		alert("Attention les mots de passe sont différents.");
		return false;
	}		
	if (!confirm("Confirmer la modification du mot de passe"))
		return false;

	document.cookie = 'updatePass=true; path=/'; 
	return true;
}

/**
*Fonction qui vérifie que les deux emails sont identiques quand on le modifie.
*Cette fonction est utilisé sur la page monCompte.
*/
function verifierLesEmails(form){
	if(form.elements['email1'].value !== form.elements['email2'].value){
		alert("Attention les emails sont différents.");
		return false;
	}		
	return confirm("Confirmer la modification de l'email.");
}