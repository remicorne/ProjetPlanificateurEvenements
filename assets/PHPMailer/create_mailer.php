<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'assets/PHPMailer/autoload.php';

$mailer = new PHPMailer;
$mailer->isSMTP();
// $mailer->SMTPDebug = SMTP::DEBUG_SERVER; //utiliser celle ci pour avoir le debug
$mailer->SMTPDebug = 0; //utiliser celle la pour que ca soit silencieux
$mailer->Host = 'smtp.gmail.com';
$mailer->Port = 587;
$mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mailer->SMTPAuth = true;
<<<<<<< HEAD:assets/composer/create_mailer.php
$mailer->Username = 'projetevenementsCCI@gmail.com';
$mailer->Password = 'ProjetEvenements.2020';
$mailer->setFrom('projetevenementsCCI@gmail.com', 'First Last');
=======
$json = file_get_contents("assets/PHPMailer/identifiants_messagerie.json");
$identifiants = json_decode($json, true);
$mailer->Username = $identifiants["identifiant"];
$mailer->Password = $identifiants["password"];
$mailer->setFrom('projetevenementsCCI@gmail.com', 'Planificateur d\'evenements');
>>>>>>> 9e349377af3301163d92c1fce0244aa1b04329ba:assets/PHPMailer/create_mailer.php
