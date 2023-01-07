#!/bin/bash

database="shows"
# Menu witch three options
PS3='Please enter your choice: '
options=("Lancer l'application web" "Créer un super-admin" "Quitter")
# If the user answer is 0, the web application is launched
select opt in "${options[@]}"
do
    case $opt in
        "Lancer l'application web")
            echo "Downloading nessessary packages"
            composer install
            # Replace vendor\easycorp\easyadmin-bundle\src\Resources\views\crud\field\email.html.twig by templates/easyAdmin/email.html.twig
            cp templates/easyAdmin/email.html.twig vendor/easycorp/easyadmin-bundle/src/Resources/views/crud/field/email.html.twig
            echo "Checking if the database is up to date"
            php bin/console doctrine:schema:update --force
            echo "Launching the web application"
            symfony serve
            ;;
        "Créer un super-admin")
            # Connect to the database ask for email and password and insert it in the database
            echo "Please enter the email of the super-admin"
            read email
            # Check if the email is valid
            if ! [[ $email =~ ^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$ ]]; then
                echo "This email is not valid"
                break
            fi
            echo "Please enter the password of the super-admin"
            read password
            # Hash the password
            password=$(php -r "echo password_hash('$password', PASSWORD_BCRYPT);")
            # JSon encode the roles
            roles=$(php -r "echo json_encode(['ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_USER']);")
            echo "INSERT INTO user (email, password, roles, admin, super_admin) VALUES ('$email', '$password', '$roles', '1', '1');" | mysql -u root -p $database
            echo "Super-admin created"
            ;;
        "Quitter")
            break
            ;;
        *) echo "invalid option $REPLY";;
    esac
done