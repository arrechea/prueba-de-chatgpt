# Install Memcached

 apt-get update && apt-get install -y \
    libpq-dev \
    libmemcached-dev \
    curl

curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

# Installing cron

apt-get update -qq && apt-get install cron -yqq
/etc/init.d/cron start
(crontab -l 2>/dev/null; echo "* * * * * php /home/site/wwwroot/artisan schedule:run >> /home/site/wwwroot/schedule.log")|crontab
/etc/init.d/cron force-reload

# Instalar supervisord

sh /home/site/wwwroot/bin/initServer.sh