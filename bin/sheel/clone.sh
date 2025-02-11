#!/bin/bash


echo "--------------------------"
echo "$@";
echo "--------------------------"

echo "--------------------------"
for arg in "$@"; do
    echo "$arg"
done
echo "--------------------------"


# Function to check if tag exists
check_tag() {
    tag_exists=$(git ls-remote --tags "$1" "refs/tags/$2" | wc -l)
    if [ $tag_exists -gt 0 ]; then
        git checkout "$2"
        return 0
    fi
    return 1
}

# Function to check if branch exists
check_branch() {
    
    branch_exists=$(git branch --all --list | grep remotes/origin/$2 | wc -l)
    if [ $branch_exists -gt 0 ]; then
        git checkout -b "$2" "origin/$2"
        return 0
    fi
    return 1
}

# Check if enough arguments are provided
if [ $# -lt 2 ]; then
    echo "Usage: $0 <git_url> <tag>"
    exit 1
fi

# Assign arguments to variables
git_url=$1
tag=$2

COM="null"

if [ "$#" -eq 3 ]; then
    COM="git clone $1 $3"
else
COM="GIT_SSH_COMMAND='ssh -i $4 -o StrictHostKeyChecking=no' git clone $1 $3"
fi

# Clone the repository
echo $COM
eval "$COM"

# Check the exit status of the command
if [ $? -eq 0 ]; then
    echo "Clone successful"
else
    echo "Clone unsuccessful. your Git KEY Maynot Right Or REPO Not Public."
    exit 1
fi
# if $COM; then
#     cd $3
# else
#     echo git clone "$git_url" $3
#     echo "Clone unsuccessful. your Git URL Maynot Right Or Not Public. "
#     exit 1
# fi
# Check if tag is "latest"
CWD=$(pwd);
cd $3;
# cd /tmp/work/cebb7ed9-d7a0-4f6c-acd1-f7af1edaca55/;
if [ "$tag" = "latest" ]; then
    # Check if tag named "latest" exists, if yes, checkout to the tag
    if check_tag "$git_url" "$tag"; then
        echo "Checked out to tag $tag"
        exit 0
    fi

    # If tag "latest" doesn't exist, check if branch named "latest" exists
    if check_branch "$git_url" "$tag"; then
        echo "Checked out to branch $tag"
        exit 0
    fi

    # If neither tag nor branch found, clone the main branch
    git checkout main
    echo "Checked out to main branch"
    exit 0
fi

# If tag is not "latest", perform the usual tag and branch checks
# Check if tag exists, if yes, checkout to the tag
# if check_tag "$git_url" "$tag"; then
#     echo "Checked out to tag $tag"
#     exit 0
# fi

# If tag doesn't exist, check if branch exists, if yes, checkout to the branch
if check_branch "$git_url" "$tag"; then
    echo "Checked out to branch $tag"
    exit 0
fi
cd $CWD;

# If neither tag nor branch found, return false
echo "Tag or branch <b>'$tag'</b> not found"; exit 1
