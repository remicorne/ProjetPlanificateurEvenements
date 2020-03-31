<div class="row">
  <div class="col-sm-6 col-md-6 col-md-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>Formulaire d'inscription</strong>
        <div>
          <form role="form" action="/index.php/users/users_create" method="post">
            <fieldset>
              <div class="row">
                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-user"></i>
                      </span><input class="form-control" placeholder="Nom" name="nom" type="text" autofocus /><br>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-user"></i>
                      </span><input class="form-control" placeholder="Prenom" name="prenom" type="text" /><br>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-envelope"></i>
                      </span><input class="form-control" placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required /><br>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i class="glyphicon glyphicon-lock"></i>
                      </span> <input class="form-control" placeholder="Mot de passe" name="password" type="password" value="" /><br>
                    </div>
                  </div>
                  <input type="submit" value="Valider mon inscription" class=" btn btn-primary btn-block" />
                </div>
              </div>
            </fieldset>
          </form>
          <a href="/index.php/sessions/sessions_new">Se connecter</a>
        </div>
      </div>
    </div>
  </div>
</div>