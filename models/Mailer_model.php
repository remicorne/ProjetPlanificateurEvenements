<?php
class Mailer_model extends Model{

    public function build_password_reset_email($email, $name, $password) {
        try {
            $this->$mailer->addAddress($email, $name);
            $content = file_get_contents('assets/PHPMailer/emailHTML/password_reset.html');
            $this->$mailer->msgHTML("$content $password");
            $this->$mailer->Subject = "Réinitialisation mot de passe";
        } catch (Exception $e) {
            throw new Exception('Impossible de construire l\'email');
        }
    }

    public function send_email(){ //pas de try/catch car si echec pas d'exception, renvoie false
        if (!$this->$mailer->send()) throw new Exception ("Echec de l'envoi : $mailer->ErrorInfo");
    }

    public function envoyer_mails_participants($emailsParts = [], $emailUser, $content, $subject){
        try {
            foreach ($emailsParts as $email) 
                if($email!=$emailUser)
                    $this->$mailer->addAddress($email);
            $this->$mailer->msgHTML($content);
            $this->$mailer->Subject = $subject;
            $this->$mailer->send();
        } catch (Exception $e) {
            throw new Exception('Impossible de construire l\'email'.$e);
        }   
    }

}