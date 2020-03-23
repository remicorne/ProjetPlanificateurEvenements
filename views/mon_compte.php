<script type="text/javascript" src="/assets/js/script_de_la_page_mon_compte.js"></script>

<div class="container">
  <div>

    <div class="div_mon_compte">
      <?php if($photo!==null){ ?>
      <div>
        <a href="/index.php/users/delete_photo">×</a><br>
        <img src="/index.php/evenements/photos_get/<?=$logged_user->numUser?>" alt="photo">
      </div>
      <?php } ?>
    
      <form enctype="multipart/form-data" method="post" action="/index.php/users/photos_set">
        <p>modifier la photo de profil</p>
        <div >
          <input type="file" id="photo" name="photo" required>
        </div>
        <button type="submit">Modifier</button>
      </form>
    </div>
    
    <div class="div_mon_compte">
      <form method="post" action="/index.php/users/set_nom_prenom" onsubmit="return validerModifNomPrenom(this)">
        <p>Nom : </p>
        <input value="<?=$logged_user->nom?>" name="nom" type="text" required>  
        <p>Prenom : </p>
        <input value="<?=$logged_user->prenom?>" name="prenom" type="text" required>
        <button type="submit">Modifier</button>
      </form>
    </div>

    <div class="div_mon_compte">
      <form method="post" action="/index.php/users/set_email" onsubmit="return verifierLesEmails(this)">
        <p>modifier l'email : </p>
        <input value="<?=$logged_user->email?>" name="email1" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
        <p>confirmer l'email : </p>
        <input value="<?=$logged_user->email?>" name="email2" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
        <button type="submit">modifier</button>
      </form>
    </div>

    <div class="div_mon_compte">
      <form method="post" action="/index.php/users/motDepasse_set" onsubmit="return verifierLesMotsDePasse(this)">
        <p>modifier mot de passe : </p>
        <input name="motDePasse1" type="password" required>
        <p>confirmer le mot de passe : </p>
        <input name="motDePasse2" type="password" required>
        <button type="submit">Modifier</button>
      </form>
    </div>

  </div>

</div>