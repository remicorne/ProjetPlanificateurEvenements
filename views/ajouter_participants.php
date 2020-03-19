<!DOCTYPE html>
<html>
<body>
 <br /><br />
  <div class="container" style="width:600px;">
   <br /><br />
   <form action="/index.php/evenements/participants_add" method="post" >
    <div class="form-group">
      <div>
        <label>Ajouter les participants</label>
     </div>
     <select id="participants" name="participants[]" multiple class="form-control" required >  

     <?php foreach($users_informations as $user_informations){ 

       if($user_informations['numUser'] === $logged_user->numUser){?>
        
        <option value=<?=$user_informations['numUser']?> selected="selected"><?=$user_informations['nom']?> <?=$user_informations['prenom']?> ( <?=$user_informations['email']?> ) [Organisateur]</option>
       <?php } else { ?>
        <option value=<?=$user_informations['numUser']?>><?=$user_informations['nom']?> <?=$user_informations['prenom']?> ( <?=$user_informations['email']?> )</option>
      <?php }} ?>
     </select>
    </div>

    <div class="form-group">
     <input type="submit" class="btn btn-info" name="submit" value="Terminer" />
    </div>

   </form>
   <br/>
  </div>
</body>
</html>


<script>
  
$(document).ready(function(){   //Attendre la disponibilité du DOM (obligatoire comme le main)

 $('#participants').multiselect({
  nonSelectedText: 'Select participants',  // affichage  Select participant lorsque aucune selection n'est faite
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,   //activer la barre de recherche rapide 
  buttonWidth:'400px'                     // largeur de la barre de selection
 });
 
 $('#submit').on('submit', function(event){ // lorsque on clique sur submit on fait appelle à cette fonction
  event.preventDefault();  //Empêche le comportement par défaut des navigateurs (comme l'envoi d'un formulaire'), mais n'empêche pas l'événement de se propager dans le DOM. (sécurité)
  var form_data = $(this).serialize();  // cette méthode crée une chaîne de texte codée URL en sérialisant les valeurs du formulaire.

  
  $.ajax({
   url:"/index.php/evenements/participants_add", //adresse à laquelle la requête doit être envoyée
   method:"POST",  //type de la requête, GET ou POST (GET par défaut).
   data:form_data,  //données à envoyer au serveur.
   success:function(data) // fonction à appeler si la requête aboutit. (inutile dans notre cas, car on change de page lorsque on clique sur submit)
   {
    $('#participants option:selected').each(function(){  //c'est comme une boucle  : parcourir les cases cochées 
     $(this).prop('selected', false);  //Ajouter (modifier) la propriété  de selection ( décoché les cases cochées un par un)
    });
    $('#participants').multiselect('refresh'); // rafrachir la barre de selection  (mettre à jour les résultats)
    alert(data); // pour débuguer seulement 
   }
  });
 });
 
 
});
</script>