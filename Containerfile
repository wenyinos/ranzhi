FROM docker.1panel.live/library/php:8.4-apache
RUN docker-php-ext-install pdo_mysql bcmath sockets \
    && a2enmod rewrite \
    && sed -i 's|/var/www/html|/var/www/html/www|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/www|g' /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/html\/www>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
