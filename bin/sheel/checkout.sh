#!/bin/bash

# Check if the number of arguments is correct
if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <path_to_repository> <branch_name>"
    exit 1
fi

# Assign parameters to variables
repo_path=$1
branch_name=$2

# Check if the provided path exists and is a directory
if [ ! -d "$repo_path" ]; then
    echo "Error: Path does not exist or is not a directory."
    exit 1
fi

# Navigate to the repository directory
cd "$repo_path" || exit

# Checkout the specified branch
git checkout "$branch_name"