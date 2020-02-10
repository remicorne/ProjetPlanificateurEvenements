<div class="container" style="margin-top: 40px">
  <div class="row">
    <div class="col-sm-6 col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>Formulaire de connexion</strong>
        </div>
        <div class="panel-body">
          <?php if (isset($error)) { ?>
            <div class="alert alert-warning" role="alert"><?= $error ?></div>
          <?php } ?>
          <form role="form" action="/index.php/sessions/sessions_create" method="post">
            <fieldset>
              <div class="row">
                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i
                        class="glyphicon glyphicon-user"></i>
                      </span> <input class="form-control" placeholder="Nom d'utilisateur"
                        name="username" type="text" autofocus>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon"> <i
                        class="glyphicon glyphicon-lock"></i>
                      </span> <input class="form-control" placeholder="Mot de passe"
                        name="password" type="password" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-lg btn-primary btn-block"
                      value="Se connecter">
                  </div>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>