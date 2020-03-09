<?php 
  const str_error_database = 'Problème avec la base de données.';
class Evenements_model extends Model{
	const str_error_database = 'problem avec la bd.' ; 
	const str_error_nomGroupe_format = 'Le nom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';

	public function ajout_groupe_bd($nomGroupe){
		$this->check_nomGroupe($nomGroupe);
	    try{
	    	$statement = $this->db->prepare("INSERT INTO Groupes(nom) VALUES(?)");
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
	      		$statement->execute([$numGroupe, $utilisateur, $proprietaire]);
		    }catch(PDOException $e){
		      throw new Exception(self::str_error_database);  
		    }
  		}
  	}

  	public function getNomsGroupes(){
  		return $this->db->query('SELECT nom FROM Groupes')->fetchAll();
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

             var_dump($date);

             $statement->execute(['date_reunion'=> $date, 
                                'heureD'=>$horaireD[$count],
                                'heureF'=>$horaireF[$count]]);
                                
                                $count++;

            }
           
        
            return $this->db->lastInsertId();
          } catch (PDOException $e) {
            throw new Exception(self::str_error_database);







    }
}
}