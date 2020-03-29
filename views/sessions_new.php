<div class="container" style="margin-top: 40px">
  <div class="row">
    <div class="col-sm-6 col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>Formulaire de connexion</strong>
        </div>
        <div class="panel-body">
          <form role="form" action="/index.php/sessions/sessions_create" method="post">
            <fieldset>
              <div class="row">
                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-envelope"></i>
                      </span><input class="form-control" placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required value="<?= set_value('email'); ?>" /><br>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-lock"></i>
                      </span> <input class="form-control" placeholder="Mot de passe" name="password" type="password" value=""><br>
                    </div>
                  </div>
                  <input type="submit" value="Se connecter" class=" btn btn-lg btn-primary btn-block">
                </div>
              </div>
            </fieldset>
          </form>
          <a href="/index.php/users/users_new">S'inscrire</a> <a href="/index.php/users/password_forgotten/<?= set_value('email'); ?>">Mot de passe oublié?</a>
        </div>
      </div>
    </div>
  </div>
</div>