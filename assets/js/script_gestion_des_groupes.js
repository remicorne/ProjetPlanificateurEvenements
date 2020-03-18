/////////////////// Fonctions communes au pages voir_les_groupes et creer_un_groupe. ////////////////////////////////////////////:

function construireTableauDePersonne(idTab, personnes){
	if(personnes==null) return;

	var tab = document.getElementById(idTab);
	tab.innerHTML="";
	
	personnes.forEach(function(personne){
		var newRow = document.createElement("tr");
		var numUser = personne['numUser'];
		var src = "/index.php/evenements/photos_get/"+numUser+"?thumbnail";
		newRow.appendChild(addColumn("td", '<img src="'+src+'" alt="photo de profil" height="50" width="50" />'));
		newRow.appendChild(addColumn("td", personne['nom']));
		newRow.appendChild(addColumn("td", personne['prenom']));
		newRow.appendChild(addColumn("td", personne['email']));
		newRow.appendChild(addColumn("td", personne['numUser']));
		newRow.lastChild.style.display = 'none';
		tab.appendChild(newRow);
	});
	return tab;
}

/////////////////// Fonctions la page voir_les_groupe. ////////////////////////////////////////////:
/**
*Fonction qui recherche les membres d'un groupe.
*/
function afficherLesMembresGroupe(numGoupe, nomGroupe){
	//ouvrir la connexion et choisir type d'envoie 
	var url="http://localhost:8080/index.php/evenements/voir_membres_groupe/"+numGoupe;
	//ajout d'un listener qui ecoute le changement d'etat.
	var requete = xhrGET(url,'json');

	requete.addEventListener('readystatechange',function(){
		if (requete.readyState === XMLHttpRequest.DONE && requete.status==200) { // La constante DONE appartient à l'objet XMLHttpRequest, elle n'est pas globale
			var membres = requete.response; 
			//on met à jour le paragraphe 'p_membres' avec le nom du groupe.
			p_membres = document.getElementById('p_membres');
			p_membres.innerHTML="membres du groupe : "+nomGroupe;
			//on remplis le tableau des personnes cherchés.
			construireTableauDePersonne("tab_groupe", membres);
		}
	});
}	


/////////////////// Fonctions la page creer_un_groupe. ////////////////////////////////////////////:
/**
*Fonction qui recherche les utilisateurs dont les premières lettre du nom correspondent à la chaine
*entrée dans l'input 'input_personne' de la page creer_un_groupe.
*/
function chercherDesPersonnes(input, prop){
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
				var PersCherches = requete.response; 
				// on remplis le tableau des personnes cherchés.
				ajouterPersonsALaDataList("list_personnes", PersCherches);
				remplirTabPersonsCherches("tab_persons", PersCherches, prop);
			}
		});
	}	
}

/**
*Fontion qui ajoute les propositions de personnes sous l'input 'input_personne' de la page creer_un_groupe.
*/
function ajouterPersonsALaDataList(idList, PersCherches){
	var datalist = document.getElementById(idList);
	var options = '';
	if(PersCherches===null) return;
	PersCherches.forEach(function(personne){
		options += '<option value="'+personne['nom']+" "+personne['prenom']+'" />';
	});
	datalist.innerHTML = options;
}

/**
*Fonction qui ajoute les utilisateurs au tableau 'tab_persons' de la page creer_un_groupe.
*les utilisateurs sont ajoutés au tableau en fonction de ce qu'y est tapé dans l'input 'input_personne'.
*/
function remplirTabPersonsCherches(idTab, PersCherches, prop){
	tab = construireTableauDePersonne(idTab, PersCherches);
	for (var i = 0; i < tab.children.length; i++) {
		if (!verifierSiPersDejaAjoute(PersCherches[i]) && PersCherches[i]['numUser']!=prop){
			//console.log(tab.children[i].parentNode);
			tab.children[i].append(addColumn("td",'<button id="bouton" onClick="remplirTabPersonsAjoutes(this.parentNode.parentNode)" >ajouter</button> '));
		}else if(PersCherches[i]['numUser']==prop)
			tab.children[i].append(addColumn("td", "proprietaire"));
		else
			tab.children[i].append(addColumn("td", "ajouté"));
	}
}

/**
*Fonction qui ajoute les utlisateurs au tableau 'tab_persons_ajoutes' de la page creer_un_groupe.
*/
function remplirTabPersonsAjoutes(row){
	var rowTabAjout = row.cloneNode(true);
	var tab = document.getElementById("tab_persons_ajoutes");
	var bouton = rowTabAjout.lastChild;
	bouton.innerHTML = '<button id="bouton_tab_ajoutes" onClick="supprimerPersDuTab(this.parentNode.parentNode)">retirer</button>';
	tab.appendChild(rowTabAjout);
	//on supprime l'elements ajouté du tableau de ceux recherchés.
	supprimerPersDuTab(row);
}

/**
*Fonction qui supprime une personnes d'un tableau.
*/
function supprimerPersDuTab(row){
	row.remove();
}

/**
*Fonction qui ajoute un input au formulaire de la page creer_un_groupe.
*cette fonction utilise la fonction 'tabPersonneAjoutes()' pour recupèrer un tableau avec les numUser 
*des personnes ajoutés au groupe (celles qui se trouvent dans le tableau 'tab_persons_ajoutes').
*un objet json contenant ce tableau est créé est placé dans l'input.
*/
function ajouterGroupeALaBd(form){

	var input = document.createElement("input");
	input.type = "hidden";
	input.name = "utilisateurs";
	input.value = JSON.stringify(tabPersonneAjoutes());
	form.appendChild(input);
	return true;
}

// renvoie un tableau avec les numUser des personnes ajoutés au groupe.
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

/**
*Verify si les personnes sont déjà ajoutés à la table 'tab_persons_ajoutes'.
*/
function verifierSiPersDejaAjoute(personne){
	var pAj = tabPersonneAjoutes()
	var isAdd = false;

	if(pAj==null) return false;

	pAj.forEach(function(p){
		if(p==personne['numUser'])
			isAdd = true;
	});
	return isAdd;
}