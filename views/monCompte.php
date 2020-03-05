<div class="container">
  <div>

    <?php if (isset($error)) { ?>
      <div class="alert alert-warning" role="alert"><?= $error ?></div>
    <?php } ?>

    <fieldset>
      <?php if($photo!==null){ ?>
      <div>
        <a class="close" href="/index.php/users/delete_photo">×</a><br>
        <img src="/index.php/evenements/photos_get/<?=$logged_user->numUser?>"
          alt="photo">
      </div>
      <?php } ?>
    
      <form enctype="multipart/form-data" method="post" action="/index.php/users/photos_set?>">
        <p>modifier la photo de profil</p>
        <div class="form-group">
          <input type="file" id="photo" name="photo" required>
        </div>
        <button type="submit">Modifier</button>
      </form>
    </fieldset>
    
    <form method="post" action="/index.php/users/set_nom_prenom" onsubmit="return valider_modif_nom_prenom(this)">
      <fieldset>
        <p>Nom : </p>
        <input value="<?=$logged_user->nom?>" class="nom" name="nom" type="text" required>  
        <p>Prenom : </p>
        <input value="<?=$logged_user->prenom?>" class="prenom" name="prenom" type="text" required>
        <button type="submit">Modifier</button>
      </fieldset>
    </form>

    <form method="post" action="/index.php/users/set_email" onsubmit="return verifier_les_emails(this)">
      <fieldset>
        <p>modifier l'email : </p>
        <input value="<?=$logged_user->email?>" class="email" name="email1" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
        <p>confirmer l'email : </p>
        <input value="<?=$logged_user->email?>" class="email" name="email2" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
        <button type="submit">modifier</button>
      </fieldset>
    </form>

    <form method="post" action="/index.php/users/motDepasse_set" onsubmit="return verifier_les_mots_de_passe(this)">
      <fieldset>
        <p>modifier mot de passe : </p>
        <input class="mot_de_passe" name="motDePasse1" type="password" required>
        <p>confirmer le mot de passe : </p>
        <input class="mot_de_passe" name="motDePasse2" type="password" required>
        <button type="submit">Modifier</button>
      </fieldset>
    </form>

  </div>

</div>