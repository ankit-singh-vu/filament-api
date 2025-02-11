#!/bin/bash

ENV_NOW=$1;
echo $ENV_NOW
if [ "$ENV_NOW" = "test" ]; then
    docker-compose down

elif [ "$ENV_NOW" = "stag" ]; then
  echo "ENV_NOW is equal to 'stag'"

elif [ "$ENV_NOW" = "prod" ]; then
  echo "ENV_NOW is equal to 'prod'"

else
  echo "ENV_NOW is not equal to 'test', 'stag', or 'prod'"
fi
