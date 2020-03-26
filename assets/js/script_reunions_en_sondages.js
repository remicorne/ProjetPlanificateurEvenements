//fonction auto appelante au chargement du script.
(function () {
	votePrisEnCompte();
})();

function votePrisEnCompte(){
	if(readCookie('vote')!==null)
	{
		alert('Votre vote a bien été enregistré.');
		document.cookie = "vote=; expires=Mon, 02 Oct 2000 01:00:00 GMT; path=/";
	}
}

function ecrire_ds_cookie_vote(){
	document.cookie = 'vote=true; path=/'; 
	return true;
}