<?php
class Users extends Controller {

  public function index() {
    $this->loader->load();
  }
  
  public function password_forgotten($email = ""){
    $this->loader->load('password_forgotten', ['title'=>'Entrez votre adresse email de récupération',
                                               'email'=>$email]);
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
      $this->loader->load('users_new', $data );
    }
  }

  public function photos_set() {
    try {
      if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK)
        throw new Exception('Vous devez choisir une photo.');
      
      $tmp_file = $_FILES['photo']['tmp_name'];
      $this->users->set_photo($tmp_file, $this->sessions->logged_user()->numUser);
      header("Location: /index.php/evenements/monCompte");
    } catch (Exception $e) {
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    }
  }

  public function set_nom_prenom(){
    try{
      $nom = filter_input(INPUT_POST,'nom');
      $prenom = filter_input(INPUT_POST,'prenom');
      $this->users->set_nom_prenom($nom, $prenom, $this->sessions->logged_user()->numUser);
      header('Location: /index.php/sessions/sessions_modify');
    } catch (Exception $e){
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    }
  }

  public function set_email(){
    try{
      $email1 = filter_input(INPUT_POST,'email1');
      $email2 = filter_input(INPUT_POST,'email2');
      if($email1!==$email2) throw new Exception('Les emails sont différents.');
      $this->users->set_email($email1, $this->sessions->logged_user()->numUser);
      header('Location: /index.php/sessions/sessions_modify');
    } catch (Exception $e){
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    }
  }

  public function motDePasse_set(){
    try{
      $motDePasse1 = filter_input(INPUT_POST,'motDePasse1');
      $motDePasse2 = filter_input(INPUT_POST,'motDePasse2');
      if($motDePasse1!==$motDePasse2) throw new Exception('Les mots de passe sont différents.');
      $this->users->motDePasse_set($motDePasse1, $this->sessions->logged_user()->numUser);
      header('Location: /index.php/sessions/sessions_modify');
    } catch (Exception $e){
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    }
  }

  public function delete_photo() {
    try {
     $this->users->delete_photo($this->sessions->logged_user()->numUser);
     header('Location: /index.php/evenements/monCompte');
    } catch (PDOException $e) {
      $this->loader->load('monCompte',['title'=>"mon compte", 
                          'error_message' => $e->getMessage()]);
    } 
  }
}