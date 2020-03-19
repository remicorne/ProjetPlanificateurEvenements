<?php

class Mailer_model extends Model{
                                // l'envoie d'email se sert de phpMailer, une libraitrie qui sert à ca
                                // pour installer cette librairie il faut composer, un outil qui sert à installer des librairies
                                // TODO : send code, goto enter code page, if code right, change password page

    public function build_password_reset_email($mailer, $email, $name, $password) 
    {
        try {
            $mailer->addAddress($email, $name);
            $content = file_get_contents('assets/PHPMailer/emailHTML/password_reset.html');
            $mailer->msgHTML("$content $password");
            $mailer->Subject = "Réinitialisation mot de passe";
            return $password;
        } catch (Exception $e) {
            throw new Exception('Impossible de construire l\'email');
        }
    }

    public function send_email($mailer) //pas de try/catch car si echec pas d'exception, renvoie false
    {
            if (!$mailer->send()) throw new exception ("Echec de l'envoi : $mailer->ErrorInfo");
    }

}