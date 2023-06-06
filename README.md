# MALOC-API
web service


# Configuration d'environement 
    - Cet projet utilise une version de php >= 8.0. Plus de détails sur la doc officiel de laravel.
    - J'utilise maildev. veuillez mettre a jours par votre config dans le .env
# Démarage du projet :
    - composer install
    - php artisan migrate:fresh --seed
    - php artisan optimize:clear
    - php artisan storage:link
    - php artisan serve

# compte admin : 
    - admin@maloc.com/password
    - editeur@maloc.com/password
    - lecteur@maloc.com/password
# compte user : 
    - jane@maloc.com/password 
    - john@maloc.com/passwor
