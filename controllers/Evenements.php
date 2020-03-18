<?php
class Evenements extends Controller {
  public function index() {
    $this->tableau_de_bord();
  }

  
  public function tableau_de_bord() {
    if ($this->redirect_unlogged_user()) return;
  	$this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord']);
  }

  public function mon_compte() {
    if ($this->redirect_unlogged_user()) return;
    $photo = $this->users->get_photo($this->sessions->logged_user()->numUser);
    $this->loader->load('mon_compte', ['title'=>'mon compte', 'photo'=>$photo]);
  }

  public function creer_un_groupe(){
    if ($this->redirect_unlogged_user()) return;
    $this->loader->load('creer_un_groupe',['title'=>'Creer un groupe']);
  }

  public function voir_les_groupes(){
    if ($this->redirect_unlogged_user()) return;
    
    try{
      // obtenir tous les groupes ou l'utilisateurs est de
      $groupes = $this->evenements->voir_les_groupes_user($this->sessions->logged_user()->numUser);
      // ajouter le nombre de membre au tableau $groupes
      foreach ($groupes as &$groupe)
        $groupe['nbMembre'] = $this->evenements->compter_les_membres_groupe($groupe['numGroupe'])['cpt'];      
      unset($groupe);

      $this->loader->load('voir_les_groupes',['title'=>'voir les groupes', 'groupes'=>$groupes]);
    
    }catch (Exception $e){
      $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
      $this->loader->load('voir_les_groupes',$data);
    }
  }

  // fonction appelé en js par la page voir_les_groupes.
  public function voir_membres_groupe($numGroupe){
    if ($this->redirect_unlogged_user()) return;
    try{
      echo json_encode($this->evenements->voir_les_membres_groupe($numGroupe));
    }catch (Exception $e){
      $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
      $this->loader->load('voir_les_groupes',$data);
    }
  }

  // fonction appelée asynchronement en js par la page creer_un_groupe.
  public function getNomsGroupes(){
    if ($this->redirect_unlogged_user()) return;
    try{
      echo json_encode($this->evenements->getNomsGroupes());
    }catch(Exception $e){}
  }

  // fonction appelée asynchronement en js par la page creer_un_groupe.
  public function users_from_nom_js($nom){
    if ($this->redirect_unlogged_user()) return;
    $nom = filter_var($nom);
    try{
      $res = $this->users->users_from_nom($nom);
      echo json_encode($res);
      //echo $res;
    }catch(Exception $e){
      echo "erreur requete";
    }
  }

  public function photos_get($numUser) {
    if ($this->redirect_unlogged_user()) return;
    try {
        $numUser = filter_var($numUser);
        if (isset($_GET['thumbnail'])) { $data = $this->users->get_thumbnail($numUser); }
        else { $data =  $this->users->get_photo($numUser); }
        header("Content-Type: image/jpeg"); // modification du header pour changer le format des données retourné au client
        echo $data;                          // écriture du binaire de l'image vers le client
      } catch (Exception $e) {}
  }

  public function ajout_groupe_bd(){
    if ($this->redirect_unlogged_user()) return;
    try{
      $utilisateurs = filter_input(INPUT_POST, 'utilisateurs');
      $utilisateurs = json_decode($utilisateurs); 
      $prop = filter_input(INPUT_POST, 'proprietaire');
      $nomGroupe = filter_input(INPUT_POST, 'nom_groupe'); 
      $numGroupe = $this->evenements->ajout_groupe_bd($nomGroupe);
      $this->evenements->ajout_personnes_groupe($numGroupe, $utilisateurs, 0);
      $this->evenements->ajout_personnes_groupe($numGroupe, [$prop], 1);
      header('Location: /index.php');
    }catch(Exception $e){
      $data = ['error' => $e->getMessage(), 'title'=>'creer_un_groupe'];
      $this->loader->load('creer_un_groupe', $data );
    }
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
        if(isset($_POST['date']) &&  isset($_POST['horaireD']) && isset($_POST['horaireF']))  {

          $date=$_POST['date'];
          $horaireD=$_POST['horaireD'];
          $horaireF=$_POST['horaireF'];
          $titre = filter_input(INPUT_POST, 'titre');
          $lieu = filter_input(INPUT_POST, 'lieu');
          $message= filter_input(INPUT_POST, 'message');
          $this->evenements->create_sondage($titre,$lieu,$message,$date,$horaireD,$horaireF);
          header("Location: /index.php/evenements/ajouter_participants"); 
     } else $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']); 

      } catch (Exception $e) {
        $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']); 
    }
  }



  public function ajouter_participants(){
    if ($this->redirect_unlogged_user()) return;
      $users_informations= $this->evenements->users_information();
      $this->loader->load('ajouter_participants', ['users_informations'=>$users_informations ,'title'=>'L Ajout des participants']);
  }


  public function participants_add(){

    $participants=$_POST["participants"];
    if(isset($participants))
      $this->evenements->ajouter_participants($participants);
    header('Location: /index.php');
  }
  }
  
     




    
  
