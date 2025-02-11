#!/bin/bash

ENV_NOW=$1

if [ "$ENV_NOW" = "test" ]; then

    docker-compose up -d

    # Function to check logs
    check_logs() {
        docker logs application_application_1 2>&1 | grep -q "0.0.0.0:80"
    }

    # Initialize $timeout and interval
    timeout=300  # $timeout in seconds (5 minutes)
    interval=10  # Check every 10 seconds

    # Start timer
    SECONDS=0

    # Loop until the application is up or $timeout is reached
    while (( SECONDS < timeout )); do
        if check_logs; then
            echo "Application is Up"
            break
        else
            echo "Application is not Up, checking again in $interval seconds..."
            sleep $interval
        fi
    done

    if (( $SECONDS >= $timeout )); then
        echo "Application did not start within $timeout seconds."
        exit 1
    fi

    # Proceed with database creation and testing
    docker exec controller_pxc_1 mysql -h "localhost" -u root -pkjhkfuiwegfwbiuwgwuifgwuigfbcuyweriu -e "CREATE DATABASE IF NOT EXISTS testing;"

    if docker exec application_application_1 php artisan test --coverage; then
        echo "TEST PASSED"
        exit 0
    else
        echo "TEST FAILED"
        exit 1
    fi

elif [ "$ENV_NOW" = "stag" ]; then
    echo "ENV_NOW is equal to 'stag'"
    chmod +x ./bin/pipeline/staging_to_deploy/initiate_deploy_to_staging.sh
    ./bin/pipeline/staging_to_deploy/initiate_deploy_to_staging.sh

elif [ "$ENV_NOW" = "prod" ]; then
    echo "ENV_NOW is equal to 'prod'"

else
    echo "ENV_NOW is not equal to 'test', 'stag', or 'prod'"
    exit 1
fi