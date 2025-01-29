FROM sinfallas/basephpcomposer:8.1

WORKDIR /var/www

# Add user for laravel application
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

COPY script_queue.sh /usr/bin/
RUN chmod 777 /usr/bin/script_queue.sh

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
RUN chown -R www:www /var/www

# Change current user to www
USER www

RUN mkdir -p /var/www/vendor

CMD ["php-fpm"]
