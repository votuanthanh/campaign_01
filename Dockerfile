FROM ubuntu:16.04

# Install apache, PHP, and supplimentary programs. openssh-server, curl, and lynx-cur are for debugging the container.
RUN apt-get update && apt-get -y upgrade && apt-get -y install \
 apache2 php libapache2-mod-php php-mcrypt php-mysql curl php-gd php-cli php-mbstring git unzip php-xml \
 libcurl4-openssl-dev pkg-config libssl-dev libsslcommon2-dev php-pear php-dev vim

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Enable apache mods.
RUN a2enmod rewrite
RUN a2enmod headers

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# install nodejs, npm, bower
# Install Nodejs
RUN curl -sL https://deb.nodesource.com/setup_7.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g gulp-cli bower eslint babel-eslint eslint-plugin-react yarn

RUN apt-get update
RUN apt-get -y install git
RUN apt-get -y install supervisor
RUN npm install -g bower
RUN npm install --global gulp-cli
RUN apt-get install -y ruby-full rubygems
RUN gem install sass

# Install PM2
RUN npm install -g pm2

# RUN pecl install mongodb
#ADD conf.d/mongodb.ini /etc/php/7.0/apache2/conf.d/30-mongodb.ini
#ADD conf.d/mongodb.ini /etc/php/7.0/cli/conf.d/30-mongodb.ini
#ADD conf.d/mongodb.ini /etc/php/7.0/mods-available//mongodb.ini

RUN mkdir /app
WORKDIR /app

# Expose apache.
EXPOSE 80

# Update the default apache site with the config we created.
ADD conf.d/vhosts.conf /etc/apache2/sites-enabled/000-default.conf
ADD conf.d/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

ADD conf.d/startup.sh /usr/bin/startup.sh
RUN chmod +x /usr/bin/startup.sh
# Execute the containers startup script which will start many processes/services

CMD ["/bin/bash", "/usr/bin/startup.sh"]
