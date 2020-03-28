<div class="container" style="margin-top: 40px">
  <strong>Entrez votre adresse e-mail de récupération</strong>
  <form role="form" action="/index.php/users/send_reset_password" method="post">
    <fieldset>
      <input class="form-control" placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required value="<?= $email ?>" /><br>
      <input type="submit" class="btn btn-lg btn-primary btn-block" value="Envoyer un e-mail de récupération">
    </fieldset>
  </form>
  <a href="/index.php/sessions/sessions_new">Se connecter</a> <a href="/index.php/users/users_new">S'inscrire</a>
</div>