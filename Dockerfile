# Use the specified base image
FROM registry.docker.convesio.com:5005/v4/application/v4-controller-base-image:laravel.1.0

LABEL maintainer="Dyutiman Chakraborty"

# Set the working directory
WORKDIR /var/www/html

# Copy the run.sh script into the container
COPY ./bin/run.sh /var/www/html/bin/run.sh

# Ensure the script is executable
RUN chmod +x /var/www/html/bin/run.sh

# Set the default command to execute the script
ENTRYPOINT ["./bin/run.sh"]