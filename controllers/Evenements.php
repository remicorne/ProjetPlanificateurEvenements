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
        if ($this->redirect_unlogged_user()) return;
        $this->loader->load('tableau_de_bord', ['title' => 'Tableau de bord']);
    }

    public function mon_compte()
    {
        if ($this->redirect_unlogged_user()) return;
        $photo = $this->users->get_photo($this->sessions->logged_user()->numUser);
        $this->loader->load('mon_compte', ['title' => 'mon compte', 'photo' => $photo]);
    }

    public function creer_un_groupe()
    {
        if ($this->redirect_unlogged_user()) return;
        $this->loader->load('creer_un_groupe', ['title' => 'Creer un groupe']);
    }

    public function ajouter_participants_documents($numEvent)
    {
        if ($this->redirect_unlogged_user()) return;
        $this->loader->load('ajouter_participants_documents', [
            'title' => 'Ajouter des participants',
            'numEvent' => $numEvent
        ]);
    }

    public function sondages_new()
    {
        if ($this->redirect_unlogged_user()) return;
        $this->loader->load('sondages_new', ['title' => 'Créer un sondage de réunion']);
    }

    public function reunions_en_sondages()
    {
        if ($this->redirect_unlogged_user()) return;
        try {
            // on recupère tous les evenement en sondages de l'utilisateur en cours.
            $donneesEvents = $this->evenements->voir_evenements_en_sondage_from_numUser($this->sessions->logged_user()->numUser);
            // construction du tableau d'events avec le numEvent en index.
            foreach ($donneesEvents as $e)
                if ($e != null) $events[$e['numEvent']] = $e;

            // si l'utilisateur a choisi un event en particulier on l'affiche sinon on affiche le premier.
            if (isset($_POST['numEvent']))
                $numEvent = filter_input(INPUT_POST, 'numEvent');
            else
                $numEvent = reset($events)['numEvent'];
            // tab d'info de l'event visualisé sur la page.
            $event_visu = $events[$numEvent];

            // les sondages visualisé sur la page.
            $sondages_event = $this->evenements->voir_sondages_evenement($numEvent);
            // ajout des reps des participants et % de bonne rep a chaques sondage dans le tableau.
            foreach ($sondages_event as &$sondage) {
                $sondage['pourcentage'] = $this->evenements->voir_pourcentage_rep_sondage($sondage['numSond']);
                $sondage['reps'] = $this->evenements->voir_reponses_parts_sond($numEvent, $sondage['numSond']);
            }
            unset($sondage);
            // reponse de l'utilisateur au sondage visualisé.
            $repUser = $this->evenements->voir_reponses_user_sond($numEvent, $event_visu['numPart']);
            $nbPart = $this->evenements->voir_nb_part_event($numEvent);
            $this->loader->load('reunions_en_sondages', [
                'title' => 'reunions en sondages', 'events' => $events,
                'event_visu' => $event_visu,
                'sondages_event' => $sondages_event,
                'nbPart' => $nbPart,
                'numPart' => $event_visu['numPart'],
                'repUser' => $repUser
            ]);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('reunions_en_sondages', $data);
        }
    }

    public function reunion($numEvent)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        $numEvent = filter_var($numEvent);
        try {
            $user = $this->sessions->logged_user();
            $this->evenements->check_if_participant($user->numUser, $numEvent);
            $is_administrator = $this->evenements->check_if_administrator($user->numUser, $numEvent);
            $infos_event = $this->evenements->voir_evenement_from_numEvent($numEvent);
            // si date fixé variable date sinon sondages.
            if ($infos_event['numSond'] != null) {
                $dates = $this->evenements->voir_sondage($infos_event['numSond']);
                $sondage = 0;
            } else {
                $dates = $this->evenements->voir_sondages_evenement($numEvent);
                $sondage = 1;
            }
            $organisateur = $this->evenements->recuperer_informations_organisateur($numEvent);
            $participation = $this->evenements->voir_si_user_participe_event($user->numUser, $numEvent);
            $this->loader->load('reunion', [
                'title' => 'Evenement',
                'numEvent' => $numEvent,
                'is_administrator' => $is_administrator,
                'infos_event' => $infos_event,
                'organisateur' => $organisateur,
                'dates' => $dates,
                'sondage' => $sondage,
                'participation' => $participation
            ]);
        } catch (Exception $e) {
            $this->loader->load('error', [
                'title' => "Page d'erreur",
                'exception' => $e
            ]);
        }
    }


    public function voir_les_groupes()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $groupes = $this->construire_tableau_des_groupes();
            $this->loader->load('voir_les_groupes', ['title' => 'voir les groupes', 'groupes' => $groupes]);
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
            $this->loader->load('modifier_groupe', [
                'title' => 'modifier le groupe',
                'membres' => $membres,
                'nomGroupe' => $nomGroupe,
                'numGroupe' => $numGroupe
            ]);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'modifier le groupe'];
            $this->loader->load('modifier_groupe', $data);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function envoyer_mails_date_evenement($numEvent)
    {
        $infosEvent = $this->evenements->voir_evenement_from_numEvent($numEvent);
        $date = $this->evenements->voir_sondage($infosEvent['numSond']);
        $content = 'L\'evenement ' . $infosEvent['titre'] . ' prendra lieu le ' . $date[0]['date_sond'] . '
                 de ' . $date[0]['heureD'] . ' à ' . $date[0]['heureF'] . ".<br>
                 Rendez-vous sur la page réunions à venir pour confirmer votre participation.";
        $subject = 'Evenement : ' . $infosEvent['titre'];
        $participants = $this->evenements->afficher_les_participants_event($numEvent);
        foreach ($participants as $part)
            $emailsParts[] = $part['email'];
        $this->envoyer_mails_participants($numEvent, $emailsParts, $subject, $content);
    }

    public function envoyer_mails_invitation_sondage($numEvent)
    {
        if ($this->redirect_unlogged_user()) return;
        $numEvent = filter_var($numEvent);
        $infosEvent = $this->evenements->voir_evenement_from_numEvent($numEvent);

        if ($infosEvent['numSond'] == 0) {
            $subject = 'Sondage evenement : ' . $infosEvent['titre'];
            $content = "Vous avez été invité à l'evenement : " . $infosEvent['titre'] . ".<br>
                  Rendez-vous la page reunion en sondage pour repondre au sondage.";
        } else {
            $date = $this->evenements->voir_sondage($infosEvent['numSond']);
            $subject = 'Invitation à l\'evenement : ' . $infosEvent['titre'];
            $content = "Vous avez été invité à l'evenement : " . $infosEvent['titre'] . ".<br>
                  L'evenement " . $infosEvent['titre'] . " aura lieu le " . $date[0]['date_sond'] . "
                  de " . $date[0]['heureD'] . " à " . $date[0]['heureF'] . ".";
        }
        $participants = $this->evenements->afficher_les_participants_event($numEvent);
        // on recupere les participants et on regarde si un email leur a déjà été envoyé.
        foreach ($participants as $part)
            if ($part['emailEnvoye'] != 1) {
                $emailsParts[] = $part['email'];
                $numsParts[] = $part['numPart'];
            }
        $this->envoyer_mails_participants($numEvent, $emailsParts, $subject, $content);
        $this->evenements->modifier_emailEnvoye_parts($numsParts);
        header('Location: /index.php');
    }

    private function envoyer_mails_participants($numEvent, $emailsParts, $subject, $content)
    {
        try {
            $this->evenements->check_if_createur_ou_administrateur_event($this->sessions->logged_user()->numUser, $numEvent);
            $infosUser = $this->users->user_from_numUser($this->sessions->logged_user()->numUser);
            $this->mailer->envoyer_mails_participants($emailsParts, $infosUser->email, $content, $subject);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'ajouter participants documents'];
            $this->loader->load('ajouter_participants_documents', $data);
        }
    }

    private function construire_tableau_des_groupes()
    {
        // obtenir tous les groupes ou l'utilisateurs est de
        $groupes = $this->evenements->voir_les_groupes_user($this->sessions->logged_user()->numUser);
        // ajouter le nombre de membre au tableau $groupes
        foreach ($groupes as &$groupe)
            $groupe['nbMembre'] = $this->evenements->compter_les_membres_groupe($groupe['numGroupe'])['cpt'];
        unset($groupe);
        return $groupes;
    }

    public function vote_reunion_en_sondages($numEvent, $numPart)
    {
        if ($this->redirect_unlogged_user()) return;
        try {
            $numEvent = filter_var($numEvent);
            $numPart = filter_var($numPart);
            if (isset($_POST['radio'])) {
                $numSond = filter_input(INPUT_POST, 'radio');
                $this->evenements->valider_date_event($numEvent, $numSond, $numPart);
                $this->envoyer_mails_date_evenement($numEvent);
            } else {
                $numsSonds = filter_input_array(INPUT_POST);
                $this->evenements->modifier_vote_sondage($numsSonds['checkbox'], $numPart);
            }
            header("Location: /index.php/evenements/reunions_en_sondages");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'voir les groupes'];
            $this->loader->load('reunions_en_sondages', $data);
        }
    }

    // fonction appelé en js par la page voir_les_groupes.
    public function voir_membres_groupe($numGroupe)
    {
        if ($this->redirect_unlogged_user()) return;
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
        if ($this->redirect_unlogged_user()) return;
        try {
            echo json_encode($this->evenements->getNomsGroupes());
        } catch (Exception $e) {
        }
    }

    // fonction appelée asynchronement en js par la page creer_un_groupe.
    public function users_from_nom_prenom_js($nom, $prenom)
    {
        if ($this->redirect_unlogged_user()) return;
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
        if ($this->redirect_unlogged_user()) return;
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
        if ($this->redirect_unlogged_user()) return;
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
            $data = ['error' => $e->getMessage(), 'title' => 'modifier groupe'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'modifier groupe'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'modifier groupe'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'modifier groupe'];
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
            $donnees = filter_input_array(INPUT_POST);
            // creation de l'évènement.
            $numEvent = $this->evenements->creer_un_evenement($donnees['titre'], $donnees['lieu'], $donnees['descri']);
            // ajout du createur a la table participants.
            $numPart = $this->evenements->ajouter_un_participant($this->sessions->logged_user()->numUser, $numEvent, 'createur');
            // creation des sondages liés à l'évènement.
            for ($i = 0; $i < count($donnees['dates']); $i++) {
                $numSond = $this->evenements->creer_un_sondage($numEvent, $donnees['dates'][$i], $donnees['horairesD'][$i], $donnees['horairesF'][$i]);
                $this->evenements->creer_reponse($numSond, $numPart);
            }
            header("Location: /index.php/evenements/reunion/$numEvent");
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage(), 'title' => 'Creer_un_sondage'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'modifier groupe'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $data = ['error' => $e->getMessage(), 'title' => 'Ajouter les participants'];
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
            $directory_path = "uploads/" . $numEvent;
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
            $this->loader->load('error', [
                'title' => "Page d'erreur",
                'exception' => $e
            ]);
        }
    }

    public function delete_document($numEvent, $docName)
    {
        try {
            $this->redirect_non_administrator($numEvent);
            $this->documents->delete_document($numEvent, $docName);
            $this->evenements->delete_document($numEvent, $docName);
        } catch (Exception $e) {
            $this->loader->load('error', [
                'title' => "Page d'erreur",
                'exception' => $e
            ]);
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
            $this->loader->load('reunions_a_venir', ['infos_reunions' => $infos_reunions, 'title' => 'Réunions à venir']);
        } catch (Exception $e) {
            $this->loader->load('reunions_a_venir', ['title' => 'Réunions à venir', 'error_message' => $e->getMessage()]);
        }
    }

    public function reunions_passees()
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $infos_reunions = $this->evenements->recuperer_infos_reunions_passees();
            $this->loader->load('reunions_passees', ['infos_reunions' => $infos_reunions, 'title' => 'Réunions passées']);
        } catch (Exception $e) {
            $this->loader->load('reunions_passees', ['title' => 'Réunions passées', 'error_message' => $e->getMessage()]);
        }
    }


    public function participants($numReunion)
    {
        if ($this->redirect_unlogged_user()) {
            return;
        }
        try {
            $infos_participants = $this->evenements->recuperer_informations_participants($numReunion);
            $this->loader->load('participants', ['infos_participants' => $infos_participants, 'title' => "Participants de la reunion numéro  $numReunion"]);
        } catch (Exception $e) {
            $this->loader->load('participants', ['title' => "participants de la reunion numéro $numReunion", 'error_message' => $e->getMessage()]);
        }
    }

    public function ajout_groupe_bd()
    {
        if ($this->redirect_unlogged_user()) return;
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
            $data = ['error' => $e->getMessage(), 'title' => 'creer_un_groupe'];
            $this->loader->load('creer_un_groupe', $data);
        }
    }

    public function modifier_participation_event($numEvent, $participation)
    {
        if ($this->redirect_unlogged_user()) return;
        $numEvent = filter_var($numEvent);
        $participation = filter_var($participation);
        $this->evenements->modifier_participation_event($numEvent, $this->sessions->logged_user()->numUser, $participation);
        header("Location: /index.php/evenements/reunion/$numEvent");
    }
}
