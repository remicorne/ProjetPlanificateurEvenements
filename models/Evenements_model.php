<?php
class Evenements_model extends Model
{
    const str_error_nomGroupe_format = 'Le nom du groupe doit contenir entre 2 et 10  lettres et chiffres.';
    const str_error_titre_evenement_format = 'Le titre de l\'evenement doit contenir entre 2 et 50  lettres et chiffres.';
    const str_error_descri_evenement_format = 'La descri de l\'evenement doit contenir entre 2 et 150  lettres et chiffres ,!?. .';
    const str_error_lieu_evenement_format = 'Le lieu de l\'evenement doit contenir entre 2 et 30  lettres et chiffres ,!?. .';
    const str_error_database = 'Problème avec la base de données.';

    public function ajout_groupe_bd($nomGroupe)
    {
        $this->check_nomGroupe($nomGroupe);
        try {
            $statement = $this->db->prepare("INSERT INTO Groupes(nomGroupe) VALUES(?)");
            $statement->execute([$nomGroupe]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function ajout_personnes_groupe($numGroupe, $utilisateurs = [], $proprietaire)
    {
        foreach ($utilisateurs as $utilisateur) {
            try {
                $statement = $this->db->prepare("INSERT INTO Appartenir(numUser, numGroupe, proprietaire) VALUES(?,?,?)");
                $statement->execute([$utilisateur, $numGroupe, $proprietaire]);
            } catch (PDOException $e) {
                throw new Exception(self::str_error_database);
            }
        }
    }

    public function user_proprio_groupe($numGroupe, $numUser)
    {
        try {
            $statement = $this->db->prepare("SELECT proprietaire FROM Appartenir WHERE numUser=? AND numGroupe=?");
            $statement->execute([$numUser, $numGroupe]);
            if ($statement->fetchColumn()!=1) {
                throw new Exception("Vous n'êtes pas proprietaire du groupe.");
            }
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' user_proprio_groupe'.$e);
        }
    }

    public function supprimer_groupe($numGroupe, $numUser)
    {
        $this->user_proprio_groupe($numGroupe, $numUser);
        try {
            $statement = $this->db->prepare("DELETE FROM Appartenir WHERE numGroupe=?");
            $statement->execute([$numGroupe]);
            $statement = $this->db->prepare("DELETE FROM Groupes WHERE numGroupe=?");
            $statement->execute([$numGroupe]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function retirer_personne_groupe($numUser, $numGroupe)
    {
        try {
            $statement = $this->db->prepare("DELETE FROM Appartenir WHERE numUser=? AND numGroupe=? AND proprietaire!=1");
            $statement->execute([$numUser, $numGroupe]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' retirer_personne_groupe'.$e);
        }
    }

    public function modifier_nom_groupe($numGroupe, $nomGroupe)
    {
        $this->check_nomGroupe($nomGroupe);
        try {
            $statement = $this->db->prepare("UPDATE Groupes SET nomGroupe=? WHERE numGroupe=?");
            $statement->execute([$nomGroupe, $numGroupe]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' retirer_personne_groupe'.$e);
        }
    }

    public function voir_les_groupes_user($numUser)
    {
        try {
            $statement = $this->db->prepare("SELECT G.numGroupe, nomGroupe FROM Appartenir A JOIN Groupes G ON A.numGroupe=G.numGroupe WHERE numUser=? AND proprietaire=1");
            $statement->execute([$numUser]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function voir_nom_groupe($numGroupe)
    {
        try {
            $statement = $this->db->prepare("SELECT nomGroupe FROM Groupes WHERE numGroupe=?");
            $statement->execute([$numGroupe]);
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function voir_les_membres_groupe($numGroupe)
    {
        try {
            $statement = $this->db->prepare("SELECT U.numUser, nom, prenom, email, proprietaire FROM Appartenir A JOIN Utilisateurs U ON A.numUser=U.numUser WHERE numGroupe=? ORDER BY nom");
            $statement->execute([$numGroupe]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function voir_numPart_membres_groupe($numGroupe, $numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT numPart FROM (Appartenir A JOIN Utilisateurs U ON A.numUser=U.numUser) JOIN Participants P ON P.numUser=U.numUser WHERE numGroupe=? AND numEvent=?");
            $statement->execute([$numGroupe, $numEvent]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function voir_numParts_utilisateur($numUser)
    {
        try {
            $statement = $this->db->prepare("SELECT numPart FROM Participants WHERE numUser=?");
            $statement->execute([$numUser]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_numParts_utilisateur'.$e);
        }
    }

    public function voir_evenement_en_sondage($numPart)
    {
        try {
            $statement = $this->db->prepare("SELECT P.numPart, statut, aVote ,E.numEvent, titre, lieu, descri FROM Evenements E JOIN Participants P ON E.numEvent=P.numEvent WHERE numPart=? AND numSond IS NULL");
            $statement->execute([$numPart]);
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_evenements_en_sondages'.$e);
        }
    }

    public function voir_sondages_evenement($numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT numSond, date_sond, heureD, heureF FROM Sondages WHERE numEvent=? ORDER BY numSond");
            $statement->execute([$numEvent]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_sondages_evenement'.$e);
        }
    }

    public function voir_pourcentage_rep_sondage($numSond)
    {
        try {
            $statement = $this->db->prepare("SELECT AVG(reponse)*100 pourcentage FROM Repondre WHERE numSond=?");
            $statement->execute([$numSond]);
            return intval($statement->fetchColumn());
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_pourcentage_rep_sondage'.$e);
        }
    }

    public function voir_reponses_part_sond($numEvent, $numSond)
    {
        try {
            $statement = $this->db->prepare("SELECT P.numPart, nom, prenom, statut, aVote, reponse FROM ((Repondre R JOIN Sondages S ON R.numSond=S.numSond) JOIN Participants P ON R.numPart=P.numPart) JOIN Utilisateurs U ON P.numUser=U.numUser  WHERE S.numEvent=? AND S.numSond=? ORDER BY P.numPart");
            $statement->execute([$numEvent, $numSond]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_reponses_part_sond_event'.$e);
        }
    }

    public function voir_reponses_user_sond($numEvent, $numPart)
    {
        try {
            $statement = $this->db->prepare("SELECT reponse FROM Repondre R JOIN Sondages S ON R.numSond=S.numSond WHERE numEvent=? AND R.numPart=? ORDER BY R.numSond");
            $statement->execute([$numEvent, $numPart]);
            return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_reponses_part_sond'.$e);
        }
    }

    public function voir_nb_part_event($numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT COUNT(*) FROM Participants WHERE numEvent=?");
            $statement->execute([$numEvent]);
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' voir_nb_part_event'.$e);
        }
    }

    public function compter_les_membres_groupe($numGroupe)
    {
        try {
            $statement = $this->db->prepare('SELECT COUNT(*) cpt FROM Appartenir WHERE numGroupe=? ');
            $statement->execute([$numGroupe]);
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    public function getNomsGroupes()
    {
        try {
            return $this->db->query('SELECT nomGroupe FROM Groupes')->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database);
        }
    }

    private function check_nomGroupe($nomGroupe)
    {
        $this->check_format_with_regex($nomGroupe, '/^[0-9a-zA-Z ]{1,10}$/', self::str_error_nomGroupe_format);
    }

    private function check_titre_evenement($titre)
    {
        $this->check_format_with_regex($titre, '/^[0-9a-zA-Z ]{1,50}$/', self::str_error_titre_evenement_format);
    }

    private function check_descri_evenement($descri)
    {
        $this->check_format_with_regex($descri, '/^[0-9a-zA-Z.?!,éèà\' \r\n]{0,150}$/', self::str_error_descri_evenement_format);
    }

    private function check_lieu_evenement($lieu)
    {
        $this->check_format_with_regex($lieu, '/^[0-9a-zA-Z ]{1,30}$/', self::str_error_lieu_evenement_format);
    }

    private function check_format_with_regex($variable, $regex, $error_message)
    {
        $result = filter_var($variable, FILTER_VALIDATE_REGEXP, array(
        'options' => array(
            'regexp' => $regex
        )
    ));
        if ($result === false || $result === null) {
            throw new Exception($error_message);
        }
    }

    public function creer_un_evenement($titre, $lieu, $descri)
    {
        $this->check_titre_evenement($titre);
        $this->check_lieu_evenement($lieu);
        $this->check_descri_evenement($descri);
        try {
            $statement = $this->db->prepare("INSERT INTO Evenements(titre, lieu, descri) VALUES (:titre, :lieu, :descri)");
            $statement->execute(['titre'=> $titre,
                            'lieu'=>$lieu,
                            'descri'=>$descri]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' creer_un_evenement'.$e);
        }
    }

    public function valider_date_event($numEvent, $numSond, $numPart)
    {
        try {
            $statement = $this->db->prepare("UPDATE Evenements SET numSond=? WHERE numEvent=?");
            $statement->execute([$numSond, $numEvent]);
            $statement = $this->db->prepare("UPDATE Participants SET aVote='oui' WHERE numPart=?");
            $statement->execute([$numPart]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' valider_date_event'.$e);
        }
    }

    public function ajouter_un_participant($numUser, $numEvent, $statut)
    {
        try {
            $statement = $this->db->prepare("INSERT INTO Participants(numEvent, numUser, statut) VALUES (?,?,?)");
            $statement->execute([$numEvent, $numUser, $statut]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' ajouter_un_participant'.$e);
        }
    }

    public function creer_un_sondage($numEvent, $date, $horaireD, $horaireF)
    {
        try {
            $statement = $this->db->prepare("INSERT INTO Sondages(date_sond, heureD, heureF, numEvent) VALUES (:date_sond, :heureD, :heureF, :numEvent)");
            $statement->execute(['date_sond'=> $date,
                            'heureD'=>$horaireD,
                            'heureF'=>$horaireF,
                            'numEvent' =>$numEvent]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' creer_un_sondage'.$e);
        }
    }

    public function obtenir_les_sondages($numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM Sondages WHERE numEvent=?");
            $statement->execute([$numEvent]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' obtenir_les_sondages'.$e);
        }
    }

    public function modifier_vote_sondage($numsSonds, $numPart)
    {
        try {
            $statement = $this->db->prepare("UPDATE Repondre SET reponse=0 WHERE numPart=?");
            $statement->execute([$numPart]);
            foreach ($numsSonds as $numSond) {
                $statement = $this->db->prepare("UPDATE Repondre SET reponse=1 WHERE numPart=? AND numSond=?");
                $statement->execute([$numPart, $numSond]);
            }
            $statement = $this->db->prepare("UPDATE Participants SET aVote='oui' WHERE numPart=?");
            $statement->execute([$numPart]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' modifier_vote_sondage'.$e);
        }
    }

    public function creer_reponse($numSond, $numPart)
    {
        try {
            $statement = $this->db->prepare("INSERT INTO Repondre(numSond, numPart) VALUES (?,?)");
            $statement->execute([$numSond, $numPart]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' creer_reponse'.$e);
        }
    }

    public function retirer_reponse($numPart)
    {
        try {
            $statement = $this->db->prepare("DELETE FROM Repondre 
                                        WHERE numPart=? AND 'createur' NOT IN (SELECT statut 
                                                                          FROM Repondre R JOIN Participants P ON R.numPart=P.numPart
                                                                          WHERE statut='createur') ");
            $statement->execute([$numPart]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' retirer_reponse'.$e);
        }
    }

    // indique si le participant à deja été ajouté à l'evenements.
    public function participant_deja_ajoute($numUser, $numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT numUser FROM Participants WHERE numUser=? AND numEvent=?");
            $statement->execute([$numUser, $numEvent]);
            return count($statement->fetchAll())!=0;
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' participant_deja_ajoute'.$e);
        }
    }

    // indique si le participant à deja été ajouté à l'evenements.
    public function user_deja_ajoute_au_groupe($numUser, $numGroupe)
    {
        try {
            $statement = $this->db->prepare("SELECT numUser FROM Appartenir WHERE numUser=? AND numGroupe=?");
            $statement->execute([$numUser, $numGroupe]);
            return count($statement->fetchAll())!=0;
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' user_deja_ajoute_au_groupe'.$e);
        }
    }

    public function ajouter_participant_bd($numUser, $numEvent, $statut)
    {
        try {
            $statement = $this->db->prepare("INSERT INTO Participants(numUser, numEvent, statut) VALUES (?,?,?)");
            $statement->execute([$numUser, $numEvent, $statut]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' ajouter_participant_bd'.$e);
        }
    }

    public function retirer_participant_bd($numPart)
    {
        try {
            $statement = $this->db->prepare("DELETE FROM Participants WHERE numPart=? AND statut!='createur'");
            $statement->execute([$numPart]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' retirer_participant_bd'.$e);
        }
    }

    public function afficher_les_participants_event($numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT numPart, U.numUser, nom, prenom, email, statut FROM Participants P JOIN Utilisateurs U ON P.numUser=U.numUser WHERE numEvent=?");
            $statement->execute([$numEvent]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.' afficher_les_participants_event'.$e);
        }
    }

    public function recuperer_infos_reunions_a_venir()
    {
        try {
            $statement = $this->db->prepare(" SELECT E.numEvent,titre,lieu,descri,E.numSond,statut,
                                    U.numUser,nom,prenom,email,date_sond,heureD,heureF,COUNT(E.numEvent) as nombreParticipant
                                    FROM (Evenements E JOIN Participants P ON E.numEvent=P.numEvent) 
                                    JOIN Utilisateurs U ON U.numUser=P.numUser 
                                    JOIN Sondages S ON S.numSond=E.numEvent 
                                    WHERE date_sond >= :date_sond
                                    GROUP BY E.numEvent
                                    ORDER BY date_sond ASC");
                                                               
            $statement->execute(['date_sond'=> date('Y-m-d')]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.'recuperer_informations_sondages'.$e);
        }
    }

    public function recuperer_infos_reunions_passees()
    {
        try {
            $statement = $this->db->prepare(" SELECT E.numEvent,titre,lieu,descri,E.numSond,statut,
                                    U.numUser,nom,prenom,email,date_sond,heureD,heureF,COUNT(E.numEvent) as nombreParticipant
                                    FROM (Evenements E JOIN Participants P ON E.numEvent=P.numEvent) 
                                    JOIN Utilisateurs U ON U.numUser=P.numUser 
                                    JOIN Sondages S ON S.numSond=E.numEvent 
                                    WHERE date_sond < :date_sond
                                    GROUP BY E.numEvent
                                    ORDER BY date_sond ASC");
                                                               
            $statement->execute(['date_sond'=> date('Y-m-d')]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.'recuperer_infos_reunions_passees'.$e);
        }
    }

    public function recuperer_informations_reunion($numReunion)
    {
        try {
            $statement = $this->db->prepare(" SELECT  E.numEvent,titre,lieu,descri,
                                    date_sond,heureD,heureF,nom,prenom,email,U.numUser,statut,COUNT(E.numEvent) as nombreParticipant
                                    FROM (Evenements E JOIN Participants P ON E.numEvent=P.numEvent) 
                                    JOIN Utilisateurs U ON U.numUser=P.numUser 
                                    JOIN Sondages S ON S.numSond=E.numEvent 
                                    WHERE S.numSond = :numReunion AND statut = :stat");
                                                               
            $statement->execute(['numReunion'=>$numReunion,'stat'=>'createur']);
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.'recuperer_informations_sondages'.$e);
        }
    }

    public function recuperer_informations_participants($numReunion)
    {
        try {
            $statement = $this->db->prepare(" SELECT E.numEvent,statut,U.numUser,nom,prenom,email
                                    FROM (Evenements E JOIN Participants P ON E.numEvent=P.numEvent) 
                                    JOIN Utilisateurs U ON U.numUser=P.numUser 
                                    JOIN Sondages S ON S.numSond=E.numEvent 
                                    WHERE S.numSond = :numReunion");
                                                               
            $statement->execute(['numReunion'=>$numReunion]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.'recuperer_informations_participants'.$e);
        }
    }

    public function recuperer_informations_organisateurs($numReunion)
    {
        try {
            $statement = $this->db->prepare(" SELECT nom,prenom,email
                                    FROM (Evenements E JOIN Participants P ON E.numEvent=P.numEvent) 
                                    JOIN Utilisateurs U ON U.numUser=P.numUser 
                                    WHERE E.numEvent = :numReunion");
                                                               
            $statement->execute(['numReunion'=>$numReunion]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database.'recuperer_informations_participants'.$e);
        }
    }
    
    public function add_document($tmp_file, $numEvent, $nomDoc)
    {
        try {
            $statement = $this->db->prepare("INSERT INTO DocsEvent(numEvent, nomDoc) VALUES(?,?)");
            $statement->execute([$numEvent, $nomDoc]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database ."(add_document) : " .$e->getMessage());
        }
    }

    public function get_event_documents($numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT nomDoc FROM DocsEvent  WHERE numEvent = ?");
            $statement->execute([$numEvent]);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database ."(get_event_documents) : " .$e->getMessage());
        }
    }


    public function delete_document($numEvent, $docName)
    {
        try {
            $statement = $this->db->prepare("DELETE FROM DocsEvent WHERE numEvent=? AND nomDoc=?");
            $statement->execute([$numEvent, $docName]);
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database ."(delete_document) : " .$e->getMessage());
        }
    }

    public function check_if_administrator($numUser, $numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM Participants WHERE numUser=? AND numEvent=? AND (statut=? OR statut=?)");
            $statement->execute([$numUser, $numEvent, "administrateur", "createur"]);
            return $statement->fetchAll() != null;
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database ."(check_if_administrator) : " .$e->getMessage());
        }
    }

    public function check_if_participant($numUser, $numEvent)
    {
        try {
            $statement = $this->db->prepare("SELECT * FROM Participants WHERE numUser=? AND numEvent=?");
            $statement->execute([$numUser, $numEvent]);
            return $statement->fetchAll() != null;
        } catch (PDOException $e) {
            throw new Exception(self::str_error_database ."(check_if_participant) : " .$e->getMessage());
        }
    }
}
