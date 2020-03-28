<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Model
{

	private static $static_db;
	private static $static_mailer;

	protected $db;
	protected $mailer;

	public function __construct()
	{
		$this->db = self::$static_db;
		$this->$mailer = self::$static_mailer;
	}

	public static function init()
	{
		global $config;
		self::$static_db = new PDO('sqlite:models/database.sqlite');
		self::$static_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		self::$static_mailer = self::create_mailer();
	}

	public function inject_data($data)
	{
		return $data;
	}

	/**
	 * This example shows settings to use when sending via Google's Gmail servers.
	 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
	 * example to see how to use XOAUTH2.
	 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
	 */
	private static function create_mailer()
	{
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
}

Model::init();
