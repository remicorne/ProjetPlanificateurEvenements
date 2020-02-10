<?php
class User {
  public $id;
  public $username;
  private $password;
  
  public function __construct($id, $username, $password) {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
  }
  
  public static function from_array($array) {
    return new User($array['id'], $array['username'], $array['password']);
  }
  
  public function password_is_valid($password) {
    return password_verify($password, $this->password);
  }
}

class Users_model extends Model {
  const str_error_username_format = 'Le nom d\'utilisateur doit contenir entre 2 et 10  lettres et chiffres.';
  const str_error_password_format = 'Le mot de passe doit contenir entre 5 et 30 caractères non blancs';
  
 // $statement = $this->db->prepare("insert into albums(album_name) values (:album_name)"); 
  //$statement->execute(['album_name' => $album_name]); 
  public function create_user($username, $password) {
    try {
      $this->check_username($username);
      $this->check_password($password);
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $statement = $this->db->prepare("INSERT INTO users(username, password) VALUES(?, ?)");
      $statement->execute([$username, $hash]);
      $id = $this->db->lastInsertId();
      return new User($id, $username, $hash);
    } catch (PDOException $e) {
      throw new Exception('Impossible d\'inscrire l\'utilisateur.');
    }
  }
  
  public function user_from_id($id) {
    return $this->user_from_query('SELECT * FROM users WHERE id = ?', [$id]);
  }
  
  public function user_from_username($username) {
    $this->check_username($username);
    return $this->user_from_query('SELECT * FROM users WHERE username = ?', [$username]);
  }

  private function check_username($username) {
    $this->check_format_with_regex($username, '/^[0-9a-zA-Z]{2,10}$/', self::str_error_username_format);
  }
  
  private function check_password($password) {
    $this->check_format_with_regex($password, '/^[^\s]{5,10}$/', self::str_error_password_format);
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
}