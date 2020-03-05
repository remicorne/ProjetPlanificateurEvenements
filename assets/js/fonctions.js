function init(){
	informerPassUpdate();
}

function valider_modif_nom_prenom(form){
	return confirm("Etes-vous sur de vouloir modifier votre nom et votre prenom par : "+form.elements['nom'].value+" "+form.elements['prenom'].value);
}

function verifier_les_mots_de_passe(form){
	if(form.elements['motDePasse1'].value !== form.elements['motDePasse2'].value){
		alert("Attention les mots de passe sont différents.");
		return false;
	}		
	if (!confirm("Confirmer la modification du mot de passe"))
		return false;

	document.cookie = 'updatePass=true; path=/'; 
	return true;
}

function informerPassUpdate(){
	if(readCookie('updatePass')!==null)
	{
		alert('Le mot de passe à été modifié avec succé.');
		document.cookie = "updatePass=; expires=Mon, 02 Oct 2000 01:00:00 GMT; path=/";
	}
}

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



function verifier_les_emails(form){
	if(form.elements['email1'].value !== form.elements['email2'].value){
		alert("Attention les emails sont différents.");
		return false;
	}		
	return confirm("Confirmer la modification de l'email.");
}