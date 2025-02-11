#!/bin/bash

ENV_NOW=$1;

if [ "$ENV_NOW" = "test" ]; then


    # Define the variables to be replaced

    # Copy .env.template to .env
    cp .env.template .env

    # Replace the variables in the .env file
    sed -i "s|\${APP_URL}|$APP_URL|g" .env
    sed -i "s|\${DATABASE_PASS}|$DATABASE_PASS|g" .env
    sed -i "s|\${DATABASE_PASS_ROOT}|$DATABASE_PASS_ROOT|g" .env
    sed -i "s|/var/lib/mysql|/mysqldbnow|g" docker-compose.yml

    echo ".env file created and variables replaced successfully."


    docker login ${DOCKER_URL} --username ${DOCKER_USER} --password ${DOCKER_PASS}

    apt update -y && apt install docker-compose jq -y


elif [ "$ENV_NOW" = "stag" ]; then
  echo "ENV_NOW is equal to 'stag'"

elif [ "$ENV_NOW" = "prod" ]; then
  echo "ENV_NOW is equal to 'prod'"

else
  echo "ENV_NOW is not equal to 'test', 'stag', or 'prod'"
fi

