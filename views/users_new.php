<div class="container" style="margin-top: 40px">
  <strong>Formulaire d'inscription</strong>
    <div >
    <form role="form" action="/index.php/users/users_create" method="post">
      <fieldset>
        <input placeholder="Nom" name="nom" type="text" autofocus/><br>
        <input placeholder="Prenom" name="prenom" type="text"/><br>
        <input placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required/><br>
        <input placeholder="Mot de passe" name="password" type="password" value=""/><br>
        <input type="submit" value="Valider mon inscription"/>
      </fieldset>
    </form>
    <a href="/index.php/sessions/sessions_new">Se connecter</a>
  </div>
</div>