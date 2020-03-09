<?php
class Evenements extends Controller {
  public function index() {
    $this->tableau_de_bord();
  }

  
  public function tableau_de_bord() {
    if ($this->redirect_unlogged_user()) return;
  	$this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord']);
  }

  public function monCompte() {
    if ($this->redirect_unlogged_user()) return;
    $photo = $this->users->get_photo($this->sessions->logged_user()->numUser);
    $this->loader->load('monCompte', ['title'=>'mon compte', 'photo'=>$photo]);
  }

  public function photos_get($numUser) {
    try {
        $numUser = filter_var($numUser);
        if (isset($_GET['thumbnail'])) { $data = $this->users->get_thumbnail($this->sessions->logged_user()->numUser); }
        else { $data =  $this->users->get_photo($this->sessions->logged_user()->numUser); }
        header("Content-Type: image/jpeg"); // modification du header pour changer le format des données retourné au client
        echo $data;                          // écriture du binaire de l'image vers le client
      } catch (Exception $e) {}
  }

  private function redirect_unlogged_user() {
    if (!$this->sessions->user_is_logged()) {
      header('Location: /index.php/sessions/sessions_new');
      return true;
    }
    return false;
  }


  public function sondages_new(){

    if ($this->redirect_unlogged_user()) return;
      $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']);
  }


  public function sondages_add(){

    var_dump($_POST['horaireF'][0]);
    var_dump($_POST['horaireD'][0]);

    var_dump($_POST['titre']);

     
    if ($this->redirect_unlogged_user()) return;
    try {
      

      if(isset($_POST['date']) &&  isset($_POST['horaireD']) && isset($_POST['horaireF']))  {

        $date=$_POST['date'];
        $horaireD=$_POST['horaireD'];
        $horaireF=$_POST['horaireF'];

        $titre = filter_input(INPUT_POST, 'titre');
        $lieu = filter_input(INPUT_POST, 'lieu');
        $message= filter_input(INPUT_POST, 'message');

        var_dump($titre);
       

     

       $this->evenements->create_sondage($titre,$lieu,$message,$date,$horaireD,$horaireF);
       header('Location: /index.php'); 
     }
    } catch (Exception $e) {
      $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']);

      
    }
  }



    
  
} 
  
     




    
  
