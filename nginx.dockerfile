FROM nginx:latest

# Copy custom nginx configuration
COPY ./nginx/sites /etc/nginx/conf.d

# Create SSL directory and copy SSL certificates
RUN mkdir -p /etc/nginx/ssl
COPY ./ssl/localhost.crt /etc/nginx/ssl/
COPY ./ssl/localhost.key /etc/nginx/ssl/

# Set working directory
WORKDIR /var/www/html

# Expose ports 80 and 443
EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]