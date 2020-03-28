<?php

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer_model extends Model{
    
	private static $static_mailer;

	protected $mailer;

	public function __construct() {
        $this->mailer = self::$static_mailer;
        parent::__construct();

	}
    
    public static function init() {
		self::$static_mailer = self::create_mailer();
        parent::init();

	}
    
    /**
	 * This example shows settings to use when sending via Google's Gmail servers.
	 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
	 * example to see how to use XOAUTH2.
	 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
	 */
	private static function create_mailer(){
		require 'assets/PHPMailer/autoload.php';
		$mailer = new PHPMailer;
		$mailer->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP
		// $mailer->SMTPDebug = SMTP::DEBUG_SERVER; //utiliser celle ci pour avoir le debug
		$mailer->SMTPDebug = 0; //utiliser celle la pour que ca soit silencieux
		$mailer->Host = 'smtp.gmail.com'; // Spécifier le serveur SMTP
		$mailer->Port = 587;
		$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mailer->SMTPAuth = true; // Activer authentication SMTP
		$json = file_get_contents("assets/PHPMailer/identifiants_messagerie.json");
		$identifiants = json_decode($json, true);
		$mailer->Username = $identifiants["identifiant"];
		$mailer->Password = $identifiants["password"];
		$mailer->setFrom('projetevenementsCCI@gmail.com', 'Planificateur d\'evenements');
		return $mailer;
	}
    
    public function build_password_reset_email($email, $name, $password) {
        try {
            $this->mailer->addAddress($email, $name);
            $content = file_get_contents('assets/PHPMailer/emailHTML/password_reset.html');
            $this->mailer->msgHTML("$content $password");
            $this->mailer->Subject = "Réinitialisation mot de passe";
        } catch (Exception $e) {
            throw new Exception('Impossible de construire l\'email');
        }
    }

    public function send_email(){ //pas de try/catch car si echec pas d'exception, renvoie false
        if (!$this->mailer->send()) throw new Exception ("Echec de l'envoi : $mailer->ErrorInfo");
    }

}

Mailer_model::init();
