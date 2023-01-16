FROM ubuntu:16.04

RUN apt-get update -qq && apt-get install -y curl apt-transport-https software-properties-common

RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssql-release.list
#RUN curl https://packages.microsoft.com/config/ubuntu/16.04/mssql-server.list > /etc/apt/sources.list.d/mssql-server.list
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update -qq

ENV DEBIAN_FRONTEND=noninteractive
ENV ACCEPT_EULA=Y

RUN apt-get update -y && apt-get install -y php7.2 libapache2-mod-php7.2 \
            php7.2-mbstring php7.2-common php-pear \
            php7.2-intl php7.2-zip unzip sqlite3 \
            php7.2-sqlite3 php7.2-json php7.2-xml php7.2-tidy php7.2-opcache php7.2-curl \
            php7.2-pgsql php7.2-mysql php-xml \
            php7.2-dev apache2 msodbcsql mssql-tools unixodbc-dev gcc build-essential g++ git

RUN pecl install sqlsrv-4.0.7 pdo_sqlsrv-4.0.7 && \
    echo "extension=/usr/lib/php/20170718/sqlsrv.so" >> /etc/php/7.2/apache2/php.ini && \
    echo "extension=/usr/lib/php/20170718/pdo_sqlsrv.so" >> /etc/php/7.2/apache2/php.ini && \
    echo "extension=/usr/lib/php/20170718/sqlsrv.so" >> /etc/php/7.2/cli/php.ini && \
    echo "extension=/usr/lib/php/20170718/pdo_sqlsrv.so" >> /etc/php/7.2/cli/php.ini
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN update-alternatives --set php /usr/bin/php7.2
RUN phpenmod mbstring && phpenmod mbstring

RUN apt-get install -y locales
RUN locale-gen en_US.UTF-8

RUN setcap 'cap_net_bind_service=+ep' /usr/sbin/apache2
RUN getcap /usr/sbin/apache2

ENV APACHE_RUN_USER  www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR   /var/log/apache2
ENV APACHE_PID_FILE  /var/run/apache2/apache2.pid
ENV APACHE_RUN_DIR   /var/run/apache2
ENV APACHE_LOCK_DIR  /var/lock/apache2
ENV APACHE_LOG_DIR   /var/log/apache2

RUN mkdir -p $APACHE_RUN_DIR
RUN mkdir -p $APACHE_LOCK_DIR
RUN mkdir -p $APACHE_LOG_DIR

RUN rm -fr /var/www
RUN mkdir /var/www && mkdir /var/www/html
RUN chown -R www-data:www-data /var/www
RUN chown -R www-data:www-data /var/log/apache2
RUN chown -R www-data:www-data /var/run/apache2
RUN chown -R www-data:www-data /var/lock/apache2

RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN update-rc.d -f apache2 remove

RUN echo "<Directory /var/www/html>" > /etc/apache2/conf-enabled/rewrite.conf
RUN echo "	Options Indexes FollowSymLinks" >> /etc/apache2/conf-enabled/rewrite.conf
RUN echo "	AllowOverride All" >> /etc/apache2/conf-enabled/rewrite.conf
RUN echo "	Require all granted" >> /etc/apache2/conf-enabled/rewrite.conf
RUN echo "</Directory>" >> /etc/apache2/conf-enabled/rewrite.conf

WORKDIR /var/www

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN find /var/www/html -group 33 -exec chgrp -h www-data {} \;
RUN find /var/www/html -user 33 -exec chown -h www-data {} \;
RUN find /var/log/apache2 -user 33 -exec chown -h www-data {} \;
RUN find /var/log/apache2 -group 33 -exec chgrp -h www-data {} \;
RUN find /var/run/apache2 -user 33 -exec chown -h www-data {} \;
RUN find /var/run/apache2 -group 33 -exec chgrp -h www-data {} \;
RUN find /var/lock/apache2 -user 33 -exec chown -h www-data {} \;
RUN find /var/lock/apache2 -group 33 -exec chgrp -h www-data {} \;

USER www-data

ENTRYPOINT ["/usr/sbin/apache2", "-D", "FOREGROUND"]

EXPOSE 80

