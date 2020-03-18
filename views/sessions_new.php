<div class="container" style="margin-top: 40px">
  <strong>Formulaire de connexion</strong>
  
  <?php if (isset($error)) { ?>
    <div class="alert alert-warning" role="alert"><?= $error ?></div>
  <?php } ?>
  
  <form role="form" action="/index.php/sessions/sessions_create" method="post">
    <fieldset>
      <input placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required value="<?=set_value('email');?>"/><br>
      <input placeholder="Mot de passe" name="password" type="password" value=""><br>
      <input type="submit" value="Se connecter">
    </fieldset>
  </form>
  <a href="/index.php/users/users_new">S'inscrire</a>  <a href="/index.php/users/password_forgotten/<?=set_value('email');?>">Mot de passe oublié?</a>
</div>