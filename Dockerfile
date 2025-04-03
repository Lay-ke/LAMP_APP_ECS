# Use an official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Install CloudWatch Agent to capture logs
RUN apt-get update && \
    apt-get install -y wget jq 


# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

RUN mkdir -p /var/www/html/crud_app

# Copy the application files into the container
COPY ./crud_app/ /var/www/html/crud_app/

# Set permissions for the application files
RUN chown -R www-data:www-data /var/www/html/crud_app
RUN chmod -R 755 /var/www/html/crud_app

# Configure Apache to log to stdout and stderr
RUN echo "ErrorLog /proc/self/fd/2" >> /etc/apache2/apache2.conf && \
    echo "CustomLog /proc/self/fd/1 combined" >> /etc/apache2/apache2.conf

# Copy startup script into container
COPY startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Expose port 80 for Apache
EXPOSE 80

# Run startup script on container start
CMD ["/usr/local/bin/startup.sh"]
