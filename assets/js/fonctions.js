var nomDesGroupes;

function init(){
	informerPassUpdate();
	getNomsDesGroupes();
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

function verifier_les_emails(form){
	if(form.elements['email1'].value !== form.elements['email2'].value){
		alert("Attention les emails sont différents.");
		return false;
	}		
	return confirm("Confirmer la modification de l'email.");
}


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

function findPerson(input, prop){
	var nom = input.value;
	if(nom.length>1){
		//encodeage pour eviter les caractères interdits dans une url
		var valeur = encodeURIComponent(nom);
		//ouvrir la connexion et choisir type d'envoie 
		var url="http://localhost:8080/index.php/evenements/users_from_nom_js/"+nom;
		//ajout d'un listener qui ecoute le changement d'etat.
		var requete = xhrGET(url,'json');

		requete.addEventListener('readystatechange',function(){
			if (requete.readyState === XMLHttpRequest.DONE && requete.status==200) { // La constante DONE appartient à l'objet XMLHttpRequest, elle n'est pas globale
				var personnes = requete.response; 	
				addPersonsDataList("list_personnes", personnes);
				fillTabPersons("tab_persons", personnes, prop);
			}
		});
	}	
}

function addPersonsDataList(idList, personnes){
	var datalist = document.getElementById(idList);
	var options = '';
	if(personnes==null) return;
	personnes.forEach(function(personne){
		options += '<option value="'+personne['nom']+" "+personne['prenom']+'" />';
	});
	datalist.innerHTML = options;
}

function addColumn(tdOrTh, contenu){
	var col = document.createElement(tdOrTh);
	col.innerHTML=contenu;
	return col ;
}

function fillTabPersons(idTab, personnes, prop){
	var tab = document.getElementById(idTab);
	tab.innerHTML="";
	if(personnes==null) return;
	personnes.forEach(function(personne){
		//document.getElementById('iframe_persons').src='/index.php/evenements/test_iframe/'+personne['numUser'];
		var newRow = document.createElement("tr");
		var numUser = personne['numUser'];
		var src = "/index.php/evenements/photos_get/"+numUser+"?thumbnail";
		newRow.appendChild(addColumn("td", '<img src="'+src+'" alt="photo de profil" height="50" width="50" />'));
		newRow.appendChild(addColumn("td", personne['nom']));
		newRow.appendChild(addColumn("td", personne['prenom']));
		newRow.appendChild(addColumn("td", personne['email']));
		newRow.appendChild(addColumn("td", personne['numUser']));
		if (!verify_personne_deja_ajoute(personne) && personne['numUser']!=prop)
			newRow.appendChild(addColumn("td", '<button id="bouton" onClick="fillTabPersonsAjoutes(this.parentNode.parentNode)">ajouter</button> ' ));
		else if(personne['numUser']==prop)
			newRow.appendChild(addColumn("td", "proprietaire"));
		else
			newRow.appendChild(addColumn("td", "ajouté"));
		tab.appendChild(newRow);
	});
}

function fillTabPersonsAjoutes(row){
	var rowTabAjout = row.cloneNode(true);
	var tab = document.getElementById("tab_persons_ajoutes");
	var bouton = rowTabAjout.lastChild;
	bouton.innerHTML = '<button id="bouton_tab_ajoutes" onClick="supprimerPersonne(this.parentNode.parentNode)">retirer</button>';
	bouton.onClick=supprimerPersonne(row);
	tab.appendChild(rowTabAjout);
}

function supprimerPersonne(row){
	row.remove();
}

function getNomsDesGroupes(){
	var requete = xhrGET('http://localhost:8080/index.php/evenements/getNomsGroupes', 'json');
	requete.addEventListener('readystatechange',function(){
		if (requete.readyState === XMLHttpRequest.DONE && requete.status==200){ // La constante DONE appartient à l'objet XMLHttpRequest, elle n'est pas globale
			nomDesGroupes = requete.response;
		}
	});
}

function verif_nom_groupe(form){
	var nomValide = true;
	var nom_groupe = form.elements['nom_groupe'].value;
	console.log(nomDesGroupes);
	nomDesGroupes.forEach(function(nomGroupe){
		if(nomGroupe['nom']==nom_groupe){
			nomValide = false;
		}
	});
	return nomValide;
}

function ajouterGroupeBd(form){

	if(!verif_nom_groupe(form)){
		alert("Ce nom est déjà utilisé");
		return false;
	} 
	var input = document.createElement("input");
	input.type = "hidden";
	input.name = "utilisateurs";
	input.value = JSON.stringify(tabPersonneAjoutes());
	form.appendChild(input);
	return true;
}

// renvoie un tableau avec les emails des personnes ajoutés au groupe.
function tabPersonneAjoutes(){
	var tab_persons_ajoutes = document.getElementById("tab_persons_ajoutes");
	var tab = [];
	
	if (!tab_persons_ajoutes.hasChildNodes()) return null;

  	var children = tab_persons_ajoutes.childNodes;

  	for (var i = 0; i < children.length; i++) {
    	var enfantsRow = children[i].childNodes;
    	tab.push(enfantsRow[4].innerHTML);	
    }
    return tab ; 
}

function verify_personne_deja_ajoute(personne){
	var tab = tabPersonneAjoutes();
	var isAdd = false;

	if(tab==null) return false;

	tab.forEach(function(t){
		if(t==personne['numUser'])
			isAdd = true;
	});
	return isAdd;
}