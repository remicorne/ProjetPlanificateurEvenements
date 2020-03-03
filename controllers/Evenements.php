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
        if (isset($_GET['thumbnail'])) { /*$data = $this->gallery->thumbnail($photo_id);*/ }
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

    if ($this->redirect_unlogged_user()) return;
    try {
      $titre = filter_input(INPUT_POST, 'titre');
      $lieu = filter_input(INPUT_POST, 'lieu');
      $message= filter_input(INPUT_POST, 'message');
      $date1= filter_input(INPUT_POST, 'date1');
      $date2= filter_input(INPUT_POST, 'date2');
      $date3= filter_input(INPUT_POST, 'date3');
      $horaireD1= filter_input(INPUT_POST, 'horaireD1');
      $horaireD2= filter_input(INPUT_POST, 'horaireD2');
      $horaireD3= filter_input(INPUT_POST, 'horaireD3');
      $horaireF1= filter_input(INPUT_POST, 'horaireF1');
      $horaireF2= filter_input(INPUT_POST, 'horaireF2');
      $horaireF3= filter_input(INPUT_POST, 'horaireF3');

      $this->evenements->create_sondage($titre,$lieu,$message,$date1,$date2,$date3,$horaireD1,$horaireD2,$horaireD3,$horaireF1,$horaireF2,$horaireF3);
      header('Location: /index.php'); 
    } catch (Exception $e) {
      $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']);
    }
  }
}