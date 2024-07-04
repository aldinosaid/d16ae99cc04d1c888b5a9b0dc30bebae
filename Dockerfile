FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql;

RUN apt-get update && \
    apt-get -y install tzdata cron;

#RUN apt-get -y remove tzdata
RUN rm -rf /var/cache/apk/*

# Copy cron file to the cron.d directory
COPY cron /etc/cron.d/cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/cron

# Apply cron job
RUN crontab /etc/cron.d/cron

# Create the log file to be able to run tail
RUN mkdir -p /var/log/cron

# Add a command to base-image entrypont scritp
RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground

RUN docker-php-ext-install pdo_mysql pgsql pdo_pgsql;

CMD php -i & apache2-foreground

COPY src/ /var/www/html