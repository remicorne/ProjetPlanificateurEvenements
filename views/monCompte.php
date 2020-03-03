<div class="container">
  <div class="panel-body">
    <?php if($photo!==null){ ?>
    <div class="col-sm-12 col-md-12" >
      <img src="/index.php/evenements/photos_get/<?=$logged_user->numUser?>"
        alt="photo">
    </div>
    <?php } ?>
    <?php if (isset($error)) { ?>
      <div class="alert alert-warning" role="alert"><?= $error ?></div>
    <?php } ?>

    <form enctype="multipart/form-data" method="post" action="/index.php/users/photos_add?>">
      <div class="form-group">
        <input type="file" id="photo" name="photo" autofocus>
      </div>
      <input type="submit" class="btn btn-lg btn-primary btn-block" value="Modifier"/>
    </form>

    <form role="form" action="#" method="post">
      <fieldset>
        <label for="nom">Nom : </label>
        <input class="form-control" placeholder="<?=$logged_user->nom?>" id="nom" name="nom" type="text" autofocus/><br>
        <label for="nom">Prenom : </label>
        <input class="form-control" placeholder="Prenom" id="prenom" name="prenom" type="text"/><br>
        <label for="email">Email : </label>
        <input class="form-control" placeholder="Email" id="email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required/><br>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Modifier"/>
      </fieldset>
    </form>
  </div>

  <div>
    <a href="#">Modifier mot de passe</a>
  </div>
</div>