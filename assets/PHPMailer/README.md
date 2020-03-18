Fonctionnement de PHPMailer :

- Installer Composer, l'outil qui permet d'installer des librairies PHP (https://getcomposer.org/) :
- Pour l'installation automatique : utiliser un fichier .sh avec le contenu suivant dans le dossier ou se situe le projet

#!/bin/sh

EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
then
    >&2 echo 'ERROR: Invalid installer checksum'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php --quiet
RESULT=$?
rm composer-setup.php
exit $RESULT

- L'installation genere un fichier composer.phar. Lancer la commande suivante pour installer la librairie PHPMailer :
- ./composer.phar require phpmailer/phpmailer

Ceci crée un dossier vendor contenant les fichiers necessaires au fonctionnement de PHPMailer
!! Dans le dossier phpMailer/phpMailer/examples se trouvent les infos necessaires pour une authentification et un envoi de mail via gmail !!
Pour résumer : 
- Inclure les fichiers necessaires au fonctionnement de PHPMailer (create_mailer.php l10 - 13)) puis
- Créer un objet PHPMailer et lui renseigner les propriétés necessaires pour établir la connexion avec le serveaur gmail 
- J'ai placé les indentifiants dans identifiants_messagerie.json qui est ingoré par .gitignore pour pas que nos ID se retrouvent en ligne, et j'ai du diminuer le niveau de sécurité du compte gmail pour pas que google bloque la connexion)

Ces 3 premieres instruction se font par le fichier create_mailer.php qui est required par la fonction send_password_reset()
du controller emails. Etant donné qu'on va devoir utiliser PHPMailer plusieurs fois, ca n'est probablement pas la facon 
optimale de faire ca, il faudrait probablement utiliser un architecture similaire à celle des Models ou le controller
aurait un Mailer comme il a un database mais j'ai pas réussi à le faire marcher (les commande use ... ne marchaient plus) 

Après ca il y a juste à ajouter à l'objet PHPMailer (stocké dans $mailer) les charactéristiques du mail à envoyer, notemment
le receveur, le contenu (il faut inlcure un fichier HTML) etc. 
Voir Emails.php