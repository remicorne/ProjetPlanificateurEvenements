  <div class="row">
    <div class="col-sm-6 col-md-6 col-md-offset-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>Entrez votre adresse e-mail de récupération</strong>
          <form role="form" action="/index.php/users/send_reset_password" method="post">
            <fieldset>
              <input class="form-control" placeholder="Email" name="email" type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required value="<?= $email ?>" /><br>
              <input type="submit" class="btn btn-lg btn-primary btn-block" value="Envoyer un e-mail de récupération">
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>