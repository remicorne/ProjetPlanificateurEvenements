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

  public function ajouter_un_participant($numUser, $numEvent, $statut){
    try {
      var_dump($numUser); echo "<br>";
      var_dump($numEvent); echo "<br>";
      var_dump($statut); echo "<br>";
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
       $numSond = $this->db->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' creer_un_sondage'.$e);
    }
  }

  public function creer_reponse($numSond, $numPart){
    try {
       $statement = $this->db->prepare("INSERT INTO Repondre(numSond, numPart) VALUES (?,?)");
       $statement->execute([$numSond, $numPart]);
       $numSond = $this->db->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database.' creer_reponse'.$e);
    }
  }

  public function users_information() {
    try {
      $statement = $this->db->query("select numUser,nom,prenom,email from utilisateurs");
      $result = $statement->fetchAll();
      return $result;
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

/*
  public function ajouter_participant($numUser){
    try {
      foreach($participants as $participant){
        $statement = $this->db->prepare("insert into Participants(numUser) VALUES (:numUser)");
        $statement->execute(['numUser'=> $participant]); 
      } 
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }
*/

}