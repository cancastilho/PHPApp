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


COPY docker/cert.crt /usr/local/share/ca-certificates/cert.crt 
RUN update-ca-certificates

COPY src /var/www/html/
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

##TODO

# COPY docker/php.ini /usr/local/etc/php/php.ini
# COPY docker/security.conf /etc/apache2/conf-enabled/security.conf
# COPY docker/apache2.conf /etc/apache2/apache2.conf

##TODO
# Abaixo segue a estratégia para mapear o UID dos logs escritos no volume montado no container
# este usuário deve estar criado no host que executa o container
# ARG host_user=docker-app
# uid do usuário docker-app no host que executa o container
# ARG host_uid=1001
# ARG host_gid=1001
# RUN useradd -g $host_gid -u $host_uid $host_user
# RUN usermod www-data -a -G $host_user

# ARG host_logs_path=/var/log/apache2/app/
# RUN mkdir $host_logs_path
# RUN chown -R $host_user:$host_user $host_logs_path

# Executa container como usuário não root.
# USER $user
EXPOSE 80 443

CMD ["apache2-foreground"]