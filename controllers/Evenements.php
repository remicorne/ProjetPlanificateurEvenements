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
        $numUser = $this->sessions->logged_user()->numUser;
        $this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord',
                                                'numUser'=>$numUser]);
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

    public function reunion($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $user = $this->sessions->logged_user();
            if (!$this->evenements->check_if_participant($user->numUser, $numEvent)) {
                throw new Exception('Tu fais pas parti de cette réunion gros, bien tenté mais on y a pensé');
            }
            $is_administrator = $this->evenements->check_if_administrator($user->numUser, $numEvent);
            $infos_reunion = $this->evenements->recuperer_informations_reunion($numEvent);
            $organisateurs = $this->evenements->recuperer_informations_organisateurs($numEvent);
            $this->loader->load('reunion', ['title'=>$infos_reunion['titre'],
                                                   'numEvent' => $numEvent,
                                                  'is_administrator' => $is_administrator,
                                                  'infos_reunion'=>$infos_reunion,
                                                'organisateurs' =>$organisateurs]);
        } catch (Exception $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                  'exception' => $e]);
        }
    }


    public function sondages_new()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $this->loader->load('sondages_new', ['title'=>'Créer un sondage de réunion']);
    }

    public function reunions_en_sondages()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numParts_user = $this->evenements->voir_numParts_utilisateur($this->sessions->logged_user()->numUser);
            // on recupère tous les evenement en sondages de l'utilisateur en cours.
            foreach ($numParts_user as $num) {
                $e = $this->evenements->voir_evenement_en_sondage($num['numPart']);
                // construction du tableau d'events avec le numEvent en index.
                if ($e!=null) {
                    $events[$e['numEvent']] = $e ;
                }
            }
            // si l'utilisateur a choisi un event en particulier on l'affiche sinon on affiche le premier.
            if (isset($_POST['numEvent'])) {
                $numEvent = filter_input(INPUT_POST, 'numEvent');
            } else {
                $numEvent = reset($events)['numEvent'];
            }
            // tab d'info de l'event visualisé sur la page.
            $event_visu = $events[$numEvent];
            // les sondages visualisé sur la page.
            $sondages_event = $this->evenements->voir_sondages_evenement($numEvent);
            // ajout des reps des participants et % de bonne rep a chaques sondage dans le tableau.
            foreach ($sondages_event as &$sondage) {
                $sondage['pourcentage'] = $this->evenements->voir_pourcentage_rep_sondage($sondage['numSond']);
                $sondage['reps'] = $this->evenements->voir_reponses_part_sond($numEvent, $sondage['numSond']);
            }
            unset($sondage);
            // reponse de l'utilisateur au sondage visualisé.
            $repUser = $this->evenements->voir_reponses_user_sond($numEvent, $event_visu['numPart']);
            $nbPart = $this->evenements->voir_nb_part_event($numEvent);
            $this->loader->load('reunions_en_sondages', ['title'=>'reunions en sondages',
                                                   'events' => $events,
                                                   'event_visu'=> $event_visu,
                                                   'sondages_event'=> $sondages_event,
                                                   'nbPart' => $nbPart,
                                                   'numPart' => $event_visu['numPart'],
                                                   'repUser' => $repUser ]);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('reunions_en_sondages', $data);
        }
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

    public function modifier_groupe($numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $numGroupe = filter_var($numGroupe);
        try {
            $membres = $this->evenements->voir_les_membres_groupe($numGroupe);
            $nomGroupe = $this->evenements->voir_nom_groupe($numGroupe);
            $this->loader->load('modifier_groupe', ['title'=>'modifier le groupe',
                                             'membres' => $membres,
                                             'nomGroupe' => $nomGroupe,
                                             'numGroupe' => $numGroupe]);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'modifier le groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function envoyer_mails_participants($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numEvent = filter_var($numEvent);
            header('Location: /index.php');
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'ajouter participants documents'];
            $this->loader->load('reunion', $data);
        }
    }

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

    public function vote_reunion_en_sondages($numEvent, $numPart)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $numEvent = filter_var($numEvent);
            $numPart = filter_var($numPart);
            if (isset($_POST['radio'])) {
                $numSond = filter_input(INPUT_POST, 'radio');
                $this->evenements->valider_date_event($numEvent, $numSond, $numPart);
            } else {
                $numsSonds=filter_input_array(INPUT_POST);
                $this->evenements->modifier_vote_sondage($numsSonds['checkbox'], $numPart);
            }
            header("Location: /index.php/evenements/reunions_en_sondages");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('reunions_en_sondages', $data);
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
    public function users_from_nom_prenom_js($nom, $prenom)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $nom = filter_var($nom);
        $prenom = filter_var($prenom);
        try {
            $res = $this->users->users_from_nom_prenom($nom, $prenom);
            echo json_encode($res);
        } catch (Exception $e) {
            echo json_encode(null);
        }
    }

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

    public function supprimer_groupe($numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $this->evenements->supprimer_groupe($numGroupe, $this->sessions->logged_user()->numUser);
            header("Location: /index.php");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'modifier groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
    }

    public function ajout_user_groupe($numUser, $numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $this->evenements->ajout_personnes_groupe($numGroupe, [$numUser], 0);
            header("Location: /index.php/evenements/modifier_groupe/$numGroupe");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'modifier groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
    }

    public function modifier_nom_groupe($numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $nomGroupe = filter_input(INPUT_POST, 'nomGroupe');
            $this->evenements->modifier_nom_groupe($numGroupe, $nomGroupe);
            header("Location: /index.php/evenements/modifier_groupe/$numGroupe");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'modifier groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
    }

    public function retirer_user_groupe($numUser, $numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $this->evenements->retirer_personne_groupe($numUser, $numGroupe);
            header("Location: /index.php/evenements/modifier_groupe/$numGroupe");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'modifier groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
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
            header("Location: /index.php/evenements/reunion/$numEvent");
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
            $this->loader->load('reunion', $data);
        }
    }

    public function user_deja_ajoute_au_groupe($numUser, $numGroupe)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $rep =  $this->evenements->user_deja_ajoute_au_groupe($numUser, $numGroupe);
            echo json_encode($rep);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title'=>'modifier groupe'];
            $this->loader->load('modifier_groupe', $data);
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
            $this->loader->load('reunion', $data);
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
            $this->loader->load('reunion', $data);
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
            $this->loader->load('reunion', $data);
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
            $this->loader->load('reunion', $data);
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
            $this->loader->load('reunion', $data);
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
            $this->loader->load('reunion', $data);
        }
    }

    public function add_document($numEvent)
    {
        try {
            $this->redirect_non_administrator($numEvent);
            if (!isset($_FILES['document'])) {
                throw new Exception('Vous devez choisir un document.');
            }
            if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Echec de l\'envoi.");
            }
            $tmp_file = $_FILES['document']['tmp_name'];
            $document_name = $_FILES['document']['name'];
            $directory_path = "uploads/" .$numEvent;
            $this->documents->add_document($tmp_file, $directory_path, $document_name);
            $this->evenements->add_document($tmp_file, $numEvent, $document_name);
            echo json_encode("success");
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function get_event_documents($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }

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

    public function redirect_non_administrator($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $user = $this->sessions->logged_user();
        if (!$this->evenements->check_if_administrator($user->numUser, $numEvent)) {
            throw new Exception('T\'es pas administrateur gros, bien tenté mais on y a pensé');
        }
    }

    public function reunions_a_venir()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $infos_reunions = $this->evenements->recuperer_infos_reunions_a_venir();
            $this->loader->load('reunions_a_venir', ['infos_reunions'=>$infos_reunions,'title'=>'Réunions à venir']);
        } catch (Exception $e) {
            $this->loader->load('reunions_a_venir', ['title'=>'Réunions à venir', 'error_message' => $e->getMessage()]);
        }
    }

    public function reunions_passees()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $infos_reunions = $this->evenements->recuperer_infos_reunions_passees();
            $this->loader->load('reunions_passees', ['infos_reunions'=>$infos_reunions,'title'=>'Réunions passées']);
        } catch (Exception $e) {
            $this->loader->load('reunions_passees', ['title'=>'Réunions passées', 'error_message' => $e->getMessage()]);
        }
    }



    public function participants($numReunion)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $infos_participants = $this->evenements->recuperer_informations_participants($numReunion);
            $this->loader->load('participants', ['infos_participants'=>$infos_participants,'title'=>"Participants de la reunion numéro  $numReunion"]);
        } catch (Exception $e) {
            $this->loader->load('participants', ['title'=>"participants de la reunion numéro $numReunion", 'error_message' => $e->getMessage()]);
        }
    }

    public function tableau_de_bord_data($numUser)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        switch ($_SERVER["REQUEST_METHOD"]) {
            case "GET":
                $events = $this->evenements->get_user_events($numUser, $_GET);
                break;
            case "POST": // Pas de methode post, on ne permet pas la création d'un evenement à partir du tableau de bord
            default:
                throw new Exception("Unexpected Method");
            break;
        }
        header("Content-Type: application/json");
        echo json_encode($events);
    }
}
