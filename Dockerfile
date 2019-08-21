FROM opositatest/php-fpm:7.2

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -  && \
    apt-get install -y nodejs build-essential redis-server && \
    npm install -g yarn


ADD . /var/www/html/

RUN mkdir -p /root/.ssh/ && \

    ssh-keyscan github.com >> ~/.ssh/known_hosts && \
    ssh-keyscan bitbucket.org  >> ~/.ssh/known_hosts && \

    APP_ENV=prod REDIS_HOST=127.0.0.1 composer install --optimize-autoloader --no-interaction --no-ansi --no-dev  && \

    chown -R www-data:www-data var  && \

    APP_ENV=prod bin/console assets:install --no-debug



EXPOSE 9000
CMD ["php-fpm"]
