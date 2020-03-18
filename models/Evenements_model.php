<?php 
class Evenements_model extends Model{
	const str_error_nomGroupe_format = 'Le nom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
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
    $this->check_format_with_regex($nomGroupe, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_nomGroupe_format);
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

  public function create_sondage($titre,$lieu,$message,$dates,$horaireD,$horaireF){
    try {
      $count=0;
      foreach($dates as $date){

       $statement = $this->db->prepare("insert into Dates(date_reunion, heureD, heureF) VALUES (:date_reunion, :heureD, :heureF)");
       $statement->execute(['date_reunion'=> $date, 
                          'heureD'=>$horaireD[$count],
                          'heureF'=>$horaireF[$count]]);
                          $count++;
        $statement = $this->db->prepare("insert into Evenements(titre, lieu, descri) VALUES (:titre, :lieu, :descri)");
        $statement->execute(['titre'=> $titre, 
                            'lieu'=>$lieu,
                            'descri'=>$message]);
      }
      return $this->db->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception(self::str_error_database);
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

  public function ajouter_participants($participants){
    try {
      foreach($participants as $participant){
        $statement = $this->db->prepare("insert into Participants(numUser) VALUES (:numUser)");
        $statement->execute(['numUser'=> $participant]); 
      } 
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }

}