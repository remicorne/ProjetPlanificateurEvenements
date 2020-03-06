<?php 
  const str_error_database = 'Problème avec la base de données.';
class Evenements_model extends Model{


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