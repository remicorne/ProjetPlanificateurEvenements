<?php
class Users extends Controller {

  public function index() {
    $this->loader->load();
  }
  
  public function users_new() {
    $this->loader->load('users_new', ['title'=>'S\'inscrire']);
  }
  
  public function users_create() {
    try {
      $nom = filter_input(INPUT_POST, 'nom');
      $prenom = filter_input(INPUT_POST, 'prenom');
      $email = filter_input(INPUT_POST, 'email');
      $password = filter_input(INPUT_POST, 'password');
      $user = $this->users->create_user($nom,$prenom,$email,$password);
      $this->sessions->login($user);
      header("Location: /index.php");
    } catch (Exception $e) {
      $data = ['error' => $e->getMessage(), 'title'=>'S\'inscrire'];
      echo 'users_create out try';
      $this->loader->load('users_new', $data );
    }
  }
}