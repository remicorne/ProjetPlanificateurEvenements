<?php 
class Evenements_model extends Model{
	const str_error_nomGroupe_format = 'Le nom du groupe doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_titre_evenement_format = 'Le titre de l\'evenement doit contenir entre 2 et 50  lettres et chiffres.';
  const str_error_descri_evenement_format = 'La descri de l\'evenement doit contenir entre 2 et 150  lettres et chiffres ,!?. .';
  const str_error_lieu_evenement_format = 'Le lieu de l\'evenement doit contenir entre 2 et 30  lettres et chiffres ,!?. .';
  const str_error_database = 'Problème avec la base de données.';

	public function ajout_groupe_bd($nomGroupe){
		$this->check_nomGroupe($nomGroupe);
	    try{
	    	$statement = $this->db->prepare("INSERT INTO Groupes(nomGroupe) VALUES(?)");
      		$statement->execute([$nomGroupe]);
      		return $this->db->lastInsertId();
	    }catch(PDOException $e){
	      throw new Exception(self::str_error_database);  
	    }
  }

	public function ajout_personnes_groupe($numGroupe, $utilisateurs = [], $proprietaire){
		foreach ($utilisateurs as $utilisateur) {
			try{
	    	$statement = $this->db->prepare("INSERT INTO Appartenir(numUser, numGroupe, proprietaire) VALUES(?,?,?)");
      		$statement->execute([$utilisateur, $numGroupe, $proprietaire]);
	    }catch(PDOException $e){
	      throw new Exception(self::str_error_database);  
	    }
		}
	}

  public function voir_les_groupes_user($numUser){
    try{
      $statement = $this->db->prepare("SELECT G.numGroupe, nomGroupe FROM Appartenir A JOIN Groupes G ON A.numGroupe=G.numGroupe WHERE numUser=? AND proprietaire=1");
      $statement->execute([$numUser]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database);
    }
  }

  public function voir_les_membres_groupe($numGroupe){
    try{
      $statement = $this->db->prepare("SELECT U.numUser, nom, prenom, email FROM Appartenir A JOIN Utilisateurs U ON A.numUser=U.numUser WHERE numGroupe=?");
      $statement->execute([$numGroupe]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database);
    }
  }

  public function voir_numPart_membres_groupe($numGroupe, $numEvent){
    try{
      $statement = $this->db->prepare("SELECT numPart FROM (Appartenir A JOIN Utilisateurs U ON A.numUser=U.numUser) JOIN Participants P ON P.numUser=U.numUser WHERE numGroupe=? AND numEvent=?");
      $statement->execute([$numGroupe, $numEvent]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database);
    }
  }  

  public function voir_numParts_utilisateur($numUser){
    try{
      $statement = $this->db->prepare("SELECT numPart FROM Participants WHERE numUser=?");
      $statement->execute([$numUser]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_numParts_utilisateur'.$e);
    }
  }

  public function voir_evenement_en_sondage($numPart){
    try{
      $statement = $this->db->prepare("SELECT P.numPart, statut, aVote ,E.numEvent, titre, lieu, descri FROM Evenements E JOIN Participants P ON E.numEvent=P.numEvent WHERE numPart=? AND numSond IS NULL");
      $statement->execute([$numPart]);
      return $statement->fetch();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_evenements_en_sondages'.$e);
    } 
  }

  public function voir_sondages_evenement($numEvent){
    try{
      $statement = $this->db->prepare("SELECT numSond, date_sond, heureD, heureF FROM Sondages WHERE numEvent=? ORDER BY numSond");
      $statement->execute([$numEvent]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_sondages_evenement'.$e);
    }  
  }

  public function voir_reponses_part_sond($numEvent, $numSond){
    try{
      $statement = $this->db->prepare("SELECT P.numPart, nom, prenom, statut, aVote, reponse FROM ((Repondre R JOIN Sondages S ON R.numSond=S.numSond) JOIN Participants P ON R.numPart=P.numPart) JOIN Utilisateurs U ON P.numUser=U.numUser  WHERE S.numEvent=? AND S.numSond=? ORDER BY P.numPart");
      $statement->execute([$numEvent, $numSond]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_reponses_part_sond_event'.$e);
    }  
  }

  public function voir_reponses_user_sond($numEvent, $numPart){
    try{
      $statement = $this->db->prepare("SELECT reponse FROM Repondre R JOIN Sondages S ON R.numSond=S.numSond WHERE numEvent=? AND R.numPart=? ORDER BY R.numSond");
      $statement->execute([$numEvent, $numPart]);
      return $statement->fetchAll(PDO::FETCH_COLUMN, 0);
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_reponses_part_sond'.$e);
    }  
  }

  public function voir_nb_part_event($numEvent){
    try{
      $statement = $this->db->prepare("SELECT COUNT(*) FROM Participants WHERE numEvent=?");
      $statement->execute([$numEvent]);
      return $statement->fetchColumn();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_nb_part_event'.$e);
    }   
  }
/*
  public function voir_participants_event($numEvent){
    try{
      $statement = $this->db->prepare("SELECT nom, prenom, aVote, reponse FROM (Repondre R JOIN Participants P ON R.numPart=P.numPart) JOIN Utilisateurs U ON P.numUser=U.numUser WHERE numEvent=? AND P.numPart=?");
      $statement->execute([$numEvent]);
      return $statement->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database.' voir_reponses_part_event'.$e);
    }  
  }
*/
  public function compter_les_membres_groupe($numGroupe){
    try{
      $statement = $this->db->prepare('SELECT COUNT(*) cpt FROM Appartenir WHERE numGroupe=? ');
      $statement->execute([$numGroupe]);
      return $statement->fetch();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database);
    } 
  }

	public function getNomsGroupes(){
    try{
      return $this->db->query('SELECT nomGroupe FROM Groupes')->fetchAll();
    }catch(PDOException $e){
      throw new Exception(self::str_error_database);
    }
	}

  private function check_nomGroupe($nomGroupe) {
    $this->check_format_with_regex($nomGroupe, '/^[0-9a-zA-Z]{1,10}$/', self::str_error_nomGroupe_format);
  }

  private function check_titre_evenement($titre) {
    $this->check_format_with_regex($titre, '/^[0-9a-zA-Z]{1,50}$/', self::str_error_titre_evenement_format);
  }

  private function check_descri_evenement($descri) {
    $this->check_format_with_regex($descri, '/^[0-9a-zA-Z.?!, ]{0,150}$/', self::str_error_descri_evenement_format);
  }

  private function check_lieu_evenement($lieu) {
    $this->check_format_with_regex($lieu, '/^[0-9a-zA-Z ]{1,30}$/', self::str_error_lieu_evenement_format);
  }

  private function check_format_with_regex($variable, $regex, $error_message) {
    $result = filter_var ( $variable, FILTER_VALIDATE_REGEXP, array (
        'options' => array (
            'regexp' => $regex
        )
    ) );
    if ($result === false || $result === null) {
      throw new Exception ( $error_message );
    }
  }

  public function creer_un_evenement($titre,$lieu,$descri){
    $this->check_titre_evenement($titre);
    $this->check_lieu_evenement($lieu);
    $this->check_descri_evenement($descri);
    try {
        $statement = $this->db->prepare("INSERT INTO Evenements(titre, lieu, descri) VALUES (:titre, :lieu, :descri)");
        $statement->execute(['titre'=> $titre, 
                            'lieu'=>$lieu,
                            'descri'=>$descri]);
        return $this->db->lastInsertId();
    }catch (PDOException $e) {
        throw new Exception(self::str_error_database.' creer_un_evenement'.$e);
    }
  }

  public function valider_date_event($numEvent, $numSond, $numPart){
    try {
        $statement = $this->db->prepare("UPDATE Evenements SET numSond=? WHERE numEvent=?");
        $statement->execute([$numSond, $numEvent]);
        $statement = $this->db->prepare("UPDATE Participants SET aVote='oui' WHERE numPart=?");
        $statement->execute([$numPart]);
    }catch (PDOException $e) {
        throw new Exception(self::str_error_database.' valider_date_event'.$e);
    } 
  }

  public function ajouter_un_participant($numUser, $numEvent, $statut){
    try {
       $statement = $this->db->prepare("INSERT INTO Participants(numEvent, numUser, statut) VALUES (?,?,?)");
       $statement->execute([$numEvent, $numUser, $statut]);
       return $this->db->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' ajouter_un_participant'.$e);
    }
  }

  public function creer_un_sondage($numEvent, $date, $horaireD, $horaireF){
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

  public function obtenir_les_sondages($numEvent){
    try {
       $statement = $this->db->prepare("SELECT * FROM Sondages WHERE numEvent=?");
       $statement->execute([$numEvent]);
       return $statement->fetchAll();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' obtenir_les_sondages'.$e);
    }
  }

  public function modifier_vote_sondage($numSond, $numPart){
    try {
       $statement = $this->db->prepare("UPDATE Repondre SET reponse=1 WHERE numSond=?");
       $statement->execute([$numSond]);

       $statement = $this->db->prepare("UPDATE Repondre SET reponse=0 WHERE numPart=? AND numSond!=?");
       $statement->execute([$numPart, $numSond]);

       $statement = $this->db->prepare("UPDATE Participants SET aVote='oui' WHERE numPart=?");
       $statement->execute([$numPart]);
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' modifier_vote_sondage'.$e);
    } 
  }

  public function creer_reponse($numSond, $numPart){
    try {
       $statement = $this->db->prepare("INSERT INTO Repondre(numSond, numPart) VALUES (?,?)");
       $statement->execute([$numSond, $numPart]);
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' creer_reponse'.$e);
    }
  }

  public function retirer_reponse($numPart){
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
  public function participant_deja_ajoute($numUser, $numEvent){
    try {
       $statement = $this->db->prepare("SELECT numUser FROM Participants WHERE numUser=? AND numEvent=?");
       $statement->execute([$numUser, $numEvent]);
       return count($statement->fetchAll())!=0;
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' participant_deja_ajoute'.$e);
    }
  }

  public function ajouter_participant_bd($numUser, $numEvent, $statut){
    try {
       $statement = $this->db->prepare("INSERT INTO Participants(numUser, numEvent, statut) VALUES (?,?,?)");
       $statement->execute([$numUser, $numEvent, $statut]);
       return $this->db->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' ajouter_participant_bd'.$e);
    } 
  }

  public function retirer_participant_bd($numPart){
    try {
       $statement = $this->db->prepare("DELETE FROM Participants WHERE numPart=? AND statut!='createur'");
       $statement->execute([$numPart]);
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' retirer_participant_bd'.$e);
    } 
  }

  public function afficher_les_participants_event($numEvent){
    try{
        $statement = $this->db->prepare("SELECT numPart, U.numUser, nom, prenom, email, statut FROM Participants P JOIN Utilisateurs U ON P.numUser=U.numUser WHERE numEvent=?");
        $statement->execute([$numEvent]);
        return $statement->fetchAll();
      }catch(PDOException $e){
        throw new Exception(self::str_error_database.' afficher_les_participants_event'.$e);
      }
  }
}