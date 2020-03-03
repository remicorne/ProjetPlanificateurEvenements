<?php
class Sessions extends Controller {

  public function sessions_new() {
    $this->sessions->logout();
    $this->loader->load('sessions_new', ['title'=>'Connexion']);
  }
  
  public function sessions_create() {
    try {
      $email = filter_input(INPUT_POST, 'email');
      $password = filter_input(INPUT_POST, 'password');
      $user = $this->users->user_from_email($email);
      if ($user===null) throw new Exception("Ce compte n'existe pas.");
      if (!$user->password_is_valid($password))
        throw new Exception("Mot de passe incorrect.");
      //var_dump($user);
      $this->sessions->login($user);
      header("Location: /index.php");
    } catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'Se connecter'];
      $this->loader->load('sessions_new', $data );
    }
  }
  
  public function sessions_destroy() {
    $this->sessions->logout();
    header("Location: /index.php");
  }
}