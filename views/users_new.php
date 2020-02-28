<div class="container" style="margin-top: 40px">
  <strong>Formulaire d'inscription</strong>
    <div class="panel-body">
    <?php if (isset($error)) { ?>
      <div class="alert alert-warning" role="alert"><?= $error ?></div>
    <?php } ?>
    <form role="form" action="/index.php/users/users_create" method="post">
      <fieldset>
        <input class="form-control" placeholder="Nom" name="nom" type="text" autofocus/><br>
        <input class="form-control" placeholder="Prenom" name="prenom" type="text"/><br>
        <input class="form-control" placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required/><br>
        <input class="form-control" placeholder="Mot de passe" name="password" type="password" value=""/><br>
        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Valider mon inscription"/>
      </fieldset>
    </form>
    <a href="/index.php/sessions/sessions_new">Se connecter</a>
  </div>
</div>