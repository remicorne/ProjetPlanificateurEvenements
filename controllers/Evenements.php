<?php
class Evenements extends Controller
{
    public function index()
    {
        $this->tableau_de_bord();
    }

    /////////////////////////////////////////// methodes de redirection/////////////////////////////////////////////////////// TODO : redirect unauthorized user (il ne s'agit pas juste d'etre logged)
    public function tableau_de_bord()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord']);
    }

    public function mon_compte()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $photo = $this->users->get_photo($this->sessions->logged_user()->numUser);
        $this->loader->load('mon_compte', ['title'=>'mon compte', 'photo'=>$photo]);
    }

    public function creer_un_groupe()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $this->loader->load('creer_un_groupe', ['title'=>'Creer un groupe']);
    }

    public function ajouter_participants_documents($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $this->loader->load('ajouter_participants_documents', ['title'=>'Ajouter des participants',
                                                   'numEvent' => $numEvent]);
    }

    public function sondages_new()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']);
    }

    public function voir_les_groupes()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $groupes = $this->construire_tableau_des_groupes();
            $this->loader->load('voir_les_groupes', ['title'=>'voir les groupes', 'groupes'=>$groupes]);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('voir_les_groupes', $data);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function construire_tableau_des_groupes()
    {
        // obtenir tous les groupes ou l'utilisateurs est de
        $groupes = $this->evenements->voir_les_groupes_user($this->sessions->logged_user()->numUser);
        // ajouter le nombre de membre au tableau $groupes
        foreach ($groupes as &$groupe) {
            $groupe['nbMembre'] = $this->evenements->compter_les_membres_groupe($groupe['numGroupe'])['cpt'];
        }
        unset($groupe);
        return $groupes;
    }

    // fonction appelé en js par la page voir_les_groupes.
    public function voir_membres_groupe($numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            echo json_encode($this->evenements->voir_les_membres_groupe($numGroupe));
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('voir_les_groupes', $data);
        }
    }

    // fonction appelée asynchronement en js par la page creer_un_groupe.
    public function getNomsGroupes()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            echo json_encode($this->evenements->getNomsGroupes());
        } catch (Exception $e) {
        }
    }

    // fonction appelée asynchronement en js par la page creer_un_groupe.
    public function users_from_nom_js($nom)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $nom = filter_var($nom);
        try {
            $res = $this->users->users_from_nom($nom);
            echo json_encode($res);
        } catch (Exception $e) {
            echo json_encode(null);
        }
    }

    public function photos_get($numUser)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numUser = filter_var($numUser);
            if (isset($_GET['thumbnail'])) {
                $data = $this->users->get_thumbnail($numUser);
            } else {
                $data =  $this->users->get_photo($numUser);
            }
            header("Content-Type: image/jpeg"); // modification du header pour changer le format des données retourné au client
        echo $data;                          // écriture du binaire de l'image vers le client
        } catch (Exception $e) {
        }
    }

    public function ajout_groupe_bd()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $utilisateurs = filter_input(INPUT_POST, 'utilisateurs');
            $utilisateurs = json_decode($utilisateurs);
            $prop = filter_input(INPUT_POST, 'proprietaire');
            $nomGroupe = filter_input(INPUT_POST, 'nom_groupe');
            $numGroupe = $this->evenements->ajout_groupe_bd($nomGroupe);
            $this->evenements->ajout_personnes_groupe($numGroupe, $utilisateurs, 0);
            $this->evenements->ajout_personnes_groupe($numGroupe, [$prop], 1);
            header('Location: /index.php');
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'creer_un_groupe'];
            $this->loader->load('creer_un_groupe', $data);
        }
    }


    private function redirect_unlogged_user()
    {
        if (!$this->sessions->user_is_logged() || $this->users->user_from_email($this->sessions->logged_user()->email) == null) {
            header('Location: /index.php/sessions/sessions_new');
            return true;
        }
        return false;
    }

    public function creer_sondages_event()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            if (!isset($_POST['titre']) && !isset($_POST['lieu']) && !isset($_POST['dates']) &&  !isset($_POST['horairesD']) && !isset($_POST['horairesF'])) {
                throw new Exception("Le titre, le lieu, la descri, la date, heure de debut et heure de fin doivent être renseignés.");
            }
            // recuperation des données dans un tableau.
            $donnees=filter_input_array(INPUT_POST);
            // creation de l'évènement.
            $numEvent = $this->evenements->creer_un_evenement($donnees['titre'], $donnees['lieu'], $donnees['descri']);
            // ajout du createur a la table participants.
            $numPart = $this->evenements->ajouter_un_participant($this->sessions->logged_user()->numUser, $numEvent, 'createur');
            // creation des sondages liés à l'évènement.
            for ($i=0; $i<count($donnees['dates']); $i++) {
                $numSond = $this->evenements->creer_un_sondage($numEvent, $donnees['dates'][$i], $donnees['horairesD'][$i], $donnees['horairesF'][$i]);
                $this->evenements->creer_reponse($numSond, $numPart);
            }

            header("Location: /index.php/evenements/ajouter_participants_documents/$numEvent");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Creer_un_sondage'];
            $this->loader->load('sondages_new', $data);
        }
    }


    // indique si le participant à deja été ajouté à l'evenements.
    public function participant_deja_ajoute($numUser, $numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $rep =  $this->evenements->participant_deja_ajoute($numUser, $numEvent);
            echo json_encode($rep);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_doncuments', $data);
        }
    }

    public function ajouter_groupe_event($numGroupe, $numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numGroupe = filter_var($numGroupe);
            $numEvent = filter_var($numEvent);
            $membres = $this->evenements->voir_les_membres_groupe($numGroupe);
            foreach ($membres as $membre) {
                $rep = $this->evenements->participant_deja_ajoute($membre['numUser'], $numEvent);
                if (!$rep) {
                    $this->ajouter_participant_event($membre['numUser'], $numEvent, 'participant');
                }
            }
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    public function retirer_groupe_event($numGroupe, $numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numGroupe = filter_var($numGroupe);
            $numEvent = filter_var($numEvent);
            $numParts = $this->evenements->voir_numPart_membres_groupe($numGroupe, $numEvent);
            foreach ($numParts as $numPart) {
                $this->retirer_participant_event($numPart['numPart']);
            }
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    public function ajouter_participant_event($numUser, $numEvent, $statut)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numUser = filter_var($numUser);
            $numEvent = filter_var($numEvent);
            $statut = filter_var($statut);

            $numPart = $this->evenements->ajouter_participant_bd($numUser, $numEvent, $statut);
            $sondages = $this->evenements->obtenir_les_sondages($numEvent);
            // on remplis la table repondre dans le futur connaitre les reponse du participants.
            foreach ($sondages as $sondage) {
                $this->evenements->creer_reponse($sondage['numSond'], $numPart);
            }
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    public function retirer_participant_event($numPart)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numPart = filter_var($numPart);
            $this->evenements->retirer_reponse($numPart);
            $this->evenements->retirer_participant_bd($numPart);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    // fonction appelé en js.
    public function afficher_participants_event($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numEvent = filter_var($numEvent);
            $rep = $this->evenements->afficher_les_participants_event($numEvent);
            echo json_encode($rep);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    // fonction appelé en js par la page 'ajouter_participants'
    public function obtenir_les_groupes()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            echo json_encode($this->construire_tableau_des_groupes());
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'Ajouter les participants'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    public function add_document($numEvent)
    { //TODO finir de retaper la fonction pour les fichiers
        try {
            $this->redirect_non_administrator($numEvent);
            if (!isset($_FILES['document'])) throw new Exception('Vous devez choisir un document.');
            if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) throw new Exception("Echec de l\'envoi.");
            $tmp_file = $_FILES['document']['tmp_name'];
            $document_name = $_FILES['document']['name'];
            $directory_path = "uploads/" .$numEvent;
            $this->documents->add_document($tmp_file, $directory_path, $document_name);
            $this->evenements->add_document($tmp_file, $numEvent, $document_name);
        } catch (Exception $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                          'exception' => $e]);
        }
    }

    public function get_event_documents($numEvent)
    {
        try {
            echo json_encode($this->evenements->get_event_documents($numEvent));
        } catch (Exception $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                          'exception' => $e]);
        }
    }

    public function delete_document($numEvent, $docName)
    {
        try {
            $this->redirect_non_administrator($numEvent);
            $this->documents->delete_document($numEvent, $docName);
            $this->evenements->delete_document($numEvent, $docName);
        } catch (Exception $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                          'exception' => $e]);
        }
    }

    public function is_name_taken($numEvent, $docName){
        try {
            echo json_encode($this->documents->is_name_taken($numEvent, $docName));    
        } catch (Exception $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                          'exception' => $e]);

        }
    }

    public function redirect_non_administrator($numEvent){
        $user = $this->sessions->logged_user();
        if (!$this->evenements->check_if_administrator($user->numUser, $numEvent))
            throw new Exception('Bien tenté mais on y a pensé');
    }

}
  




    
  
