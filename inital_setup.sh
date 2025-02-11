#!/bin/bash

# Check if the script is run as root
if [[ $EUID -ne 0 ]]; then
    echo "This script must be run as root to modify /etc/hosts"
    exit 1
fi

# Check if the non-root user is provided as a parameter
if [ -z "$1" ]; then
    echo "Usage: sudo $0 <non_root_user>"
    exit 1
fi

non_root_user="$1"

# Load the .env file
ENV_FILE="$(dirname "$0")/.env"
if [[ -f $ENV_FILE ]]; then
    source $ENV_FILE
else
    echo ".env file not found!"
    exit 1
fi

# Check if APP_URL is set
if [[ -z "$APP_URL" ]]; then
    echo "APP_URL is not set in the .env file!"
    exit 1
fi

# Extract the base domain from APP_URL
BASE_DOMAIN=$(echo "$APP_URL" | sed -e 's|https://||' -e 's|/$||')

# Define the IP address
IP_ADDRESS="192.168.62.101"

# Define the domains using the base domain
declare -A domains_and_ips=(
    ["$BASE_DOMAIN"]="$IP_ADDRESS"
    ["admin.$BASE_DOMAIN"]="$IP_ADDRESS"
    ["partner.$BASE_DOMAIN"]="$IP_ADDRESS"
)

# Add domains to /etc/hosts
for domain in "${!domains_and_ips[@]}"; do
    ip="${domains_and_ips[$domain]}"
    # Check if the exact IP-domain pair already exists in /etc/hosts
    if ! grep -q "^$ip[[:space:]]*$domain$" /etc/hosts; then
        echo "$ip    $domain" >> /etc/hosts
        echo "Added $domain with IP $ip to /etc/hosts"
    else
        echo "$domain with IP $ip already exists in /etc/hosts"
    fi
done

