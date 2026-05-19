# Utiliser l'image PHP 8 avec Apache
FROM php:8-apache

# Copier les fichiers de l'application dans le conteneur
COPY Index.html /var/www/html/index.html
COPY save.php /var/www/html/save.php

# Créer le répertoire pour les résultats et définir les permissions
RUN mkdir -p /var/www/html/data && \
    chmod 777 /var/www/html/data

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
