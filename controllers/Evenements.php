<<<<<<< HEAD
  
=======
<?php
class Evenements extends Controller {
  public function index() {
    $this->tableau_de_bord();
  }
  
  public function tableau_de_bord() {
    if ($this->redirect_unlogged_user()) return;
  	$this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord']);
  }

  private function redirect_unlogged_user() {
    if (!$this->sessions->user_is_logged()) {
      header('Location: /index.php/sessions/sessions_new');
      return true;
    }
    return false;
  }
}
>>>>>>> 907f33cfe9d872a4d0cdd5dd81e05f015b5b0e04
