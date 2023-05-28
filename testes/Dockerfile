FROM php:8.2-apache

RUN apt update && apt install -y libldap2-dev libapache2-mod-security2 tzdata && \
docker-php-ext-install ldap && \
ln -f /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
echo "America/Sao_Paulo" > /etc/timezone && \
mkdir "/etc/ldap" && \
echo "TLS_REQCERT never" > /etc/ldap/ldap.conf && \
chown -R www-data.www-data /var/www/html/ && \
a2enmod headers && \
a2enmod rewrite && \
apt-get clean && \
rm -rf /var/lib/apt/lists/* && \
rm -rf /tmp/* 


COPY src /var/www/html/

WORKDIR /app

RUN apt-get update
RUN pecl install xdebug && docker-php-ext-enable xdebug 
RUN apt-get install libonig-dev
# mbstring é necessário para phpunit
RUN docker-php-ext-install mbstring
RUN php -r "copy('https://phar.phpunit.de/phpunit-10.phar', '/app/phpunit.phar');"
# execute docker build da raiz do projeto, para não haver erros nos COPY:
# discussão em https://stackoverflow.com/questions/24537340/docker-adding-a-file-from-a-parent-directory
COPY src /app/src
COPY testes/src /app/testes/src


ENTRYPOINT ["php","-d","xdebug.mode=coverage","/app/phpunit.phar","--do-not-cache-result","--bootstrap","/app/src/Autoloader.php","--testdox","/app/testes/src","--coverage-html", "/app/testes/cobertura", "--coverage-filter", "/app/src"]