<?php
class Users extends Controller {

  public function index() {
    $this->loader->load();
  }
  
  public function users_new() {
    $this->sessions->logout();
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

  public function photos_add() {
    try {
      if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK)
        throw new Exception('Vous devez choisir une photo.');
      
      $tmp_file = $_FILES['photo']['tmp_name'];
      $this->users->add_photo($tmp_file, $this->sessions->logged_user()->numUser);
      header("Location: /index.php/evenements/monCompte");
    } catch (Exception $e) {
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    }
  }
}