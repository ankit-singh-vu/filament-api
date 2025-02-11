#!/bin/bash

cd $1
git config --global --add safe.directory /var/www/html

git fetch --all
git pull --all --ff
# Get all branch names in the repository
branches=$(git branch -r --format="%(refname:lstrip=2)" | grep -v 'origin/HEAD' | sed 's/origin\///')

# Convert branch names to JSON format
json="{\"branches\": ["
count=0

# Loop through branch names and append to JSON
for branch in $branches
do
    if [ $count -eq 0 ]; then
        json="$json{\"name\":\"$branch\"},"
    else
        json="$json, {\"name\":\"$branch\"}"
    fi
done

json="$json]}"

# Output the JSON
echo $json
