<?php
class User {
  public $numUser;
  public $nom;
  public $prenom;
  public $email;
  private $motDePasse;
  
  public function __construct($numUser, $nom, $prenom, $email, $motDePasse) {
    $this->numUser = $numUser;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->email = $email;
    $this->motDePasse = $motDePasse;
  }
  
  public static function from_array($array) {
    return new User($array['numUser'], $array['nom'],$array['prenom'],$array['email'],$array['motDePasse']);
  }
  
  public function password_is_valid($motDePasse) {
    return password_verify($motDePasse, $this->motDePasse);
  }
}

class Users_model extends Model {
  const str_error_nom_format = 'Le nom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_prenom_format = 'Le prenom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_email_format = 'Email invalide.';
  const str_error_motDePasse_format = 'Le mot de passe doit contenir entre 5 et 30 caractères non blancs';
  const str_error_photo_does_not_exist = 'La photo n\'existe pas.';
  
  public function create_user($nom, $prenom, $email, $motDePasse) {
    try {
      $this->check_nom($nom);
      $this->check_prenom($prenom);
      $this->check_email($email);
      $this->check_motDePasse($motDePasse);
      $hash = password_hash($motDePasse, PASSWORD_DEFAULT);
      $statement = $this->db->prepare("INSERT INTO Utilisateurs(nom,prenom,email,motDePasse) VALUES(?, ?, ?, ?)");
      $statement->execute([$nom,$prenom, $email, $hash]);
      $id = $this->db->lastInsertId();
      var_dump($id);
      return new User($id, $nom, $prenom, $email, $hash);
    } catch (PDOException $e) {
      throw new Exception('Impossible d\'inscrire l\'utilisateur.');
    }
  }
  
  public function user_from_id($numUser) {
    return $this->user_from_query('SELECT * FROM Utilisateurs WHERE numUser = ?', [$id]);
  }
  
  public function user_from_email($email) {
    $this->check_email($email);
    return $this->user_from_query('SELECT * FROM Utilisateurs WHERE email = ?', [$email]);
  }

  private function check_nom($nom) {
    $this->check_format_with_regex($nom, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_nom_format);
  }

  private function check_prenom($prenom) {
    $this->check_format_with_regex($prenom, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_prenom_format);
  }

  private function check_email($email) {
    $this->check_format_with_regex($email, '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/', self::str_error_prenom_format);
  }

  private function check_motDePasse($motDePasse) {
    $this->check_format_with_regex($motDePasse, '/^[^\s]{5,10}$/', self::str_error_motDePasse_format);
  }
  
  private function user_from_query($query, $array) {
    try {
      $statement = $this->db->prepare($query);
      $statement->execute($array);
      $users = $statement->fetchAll();
      if (count($users)==0) return null;
      return User::from_array($users[0]);
    } catch (PDOException $e) {
      throw new Exception('Impossible d\'effectuer la demande.');
    }
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

  public function delete_photo($numUser) {
    try {
      $statement = $this->db->prepare("delete from Utilisateurs where numUser = :numUser");
      $statement->execute(['numUser' => $numUser]);
    } catch (PDOException $e) {
      exit;
      throw new Exception(self::str_error_database);
    }
  }

  public function get_photo($numUser) {
    try {
      $statement = $this->db->prepare("select photo from Utilisateurs where numUser = :numUser");
      $statement->execute(['numUser' => $numUser]);
      $result = $statement->fetchAll();
      if (count($result) == 0) return null;
      return $result[0]['photo'];
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    }
  }  


  public function add_photo($tmp_file, $numUser) {
    try {
      $statement = $this->db->prepare("UPDATE Utilisateurs 
                                        SET photo = :photo
                                        WHERE numUser= :numUser");
      $statement->execute(["photo"=>$this->create_photo($tmp_file), 
                           "numUser"=>$numUser]);
      return $this->db->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception(self::str_error_database);
    } catch ( ImagickException $e ) {
      throw new Exception ( self::str_error_photo_format );
    }
  }

  private function create_photo($tmp_file) {
    $image = new Imagick ( $tmp_file );
    try {
      $image->setImageFormat("jpeg");
      $this->resize_photo ( $image );
      return $image->getimageBlob();
    }catch(Exception $e){
      echo $e->getMessage();
    } finally {
      $image->destroy ();
    }
  }
  
  private function resize_photo($image) {
    $geometry = $image->getImageGeometry ();
    if ($geometry ['width'] > $geometry ['height']) {
      $image->thumbnailImage ( 150, 0 );
    } else {
      $image->thumbnailImage ( 0, 150 );
    }
  }
}