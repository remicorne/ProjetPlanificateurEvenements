<?php
class Emails extends Controller // j'ai crée un nouveau controller mais je suis pas sur de
                                // l'architecture que j'ai utilisé, ca me parait pas propre
{                               // l'envoie d'email se sert de phpMailer, une libraitrie qui sert à ca
                                // pour installer cette librairie il faut composer, un outil qui sert à installer des librairies

    public function send_password_reset()
    { 
        try {
            require "assets/PHPMailer/create_mailer.php"; //construit un objet de type mailer dans $mailer selon le process indiqué dans la doc
            $email = filter_input(INPUT_POST, 'email');
            $user = $this->users->user_from_email($email); //trouve le user 
            if ($user == null) throw new Exception ('Pas de compte associé à cet e-mail');
            $name = "$user->prenom .$user->nom"; //construit le nom du user
            $this->build_password_reset_email($mailer, $email, $name); //construit l'email à envoyer
            $this->send_email($mailer);
            header('Location: /index.php/sessions/sessions_new');
        } catch (Exception $e) {
            var_dump($e->getMessage());
            $this->loader->load('password_forgotten', ['title'=>'Votre adresse email de récupération',
          'error_message' => $e->getMessage()]);
        }
    }

    public function build_password_reset_email($mailer, $email, $name) //utilise des fonction de l'outil PHPmailer
    {
        try {
            $mailer->addAddress($email, $name);
            $mailer->msgHTML(file_get_contents('assets/PHPMailer/emailHTML/password_reset.html'), __DIR__);
            $mailer->Subject = "Réinitialisation mot de passe";
            $mailer->AltBody = "Je sais pas a quoi sert ce champ mais dans le tuto il y était";
        } catch (Exception $e) {
            throw new Exception('Impossible de construire l\'email');
        }
    }

    public function send_email($mailer)
    {
        try {
            if (!$mailer->send()) throw new exception ('Echec de l\'envoi'); 
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}