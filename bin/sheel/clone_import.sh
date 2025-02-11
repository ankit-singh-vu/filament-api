#!/bin/bash
check_tag() {
    tag_name=$1

    # Check if the tag exists
    if git rev-parse -q --verify "$tag_name" >/dev/null; then
        echo "Tag '$tag_name' exists. Checking out to '$tag_name'..."
        git checkout "$tag_name"
        return 0
    else
        echo "Tag '$tag_name' does not exist."
        return 1
    fi
}
check_branch() {
    branch_name=$1

    # Check if the branch exists
    if git rev-parse -q --verify "refs/heads/$branch_name" >/dev/null; then
        git checkout "$branch_name"
        return 0
    else
        return 1
    fi
}

if [ $# -lt 5 ]; then
    echo "Usage: $0 <git_url> <tag>"
    exit 1
fi

# Assign arguments to variables
git=$1
tag=$2
username=$3
password=$4
temp_repo=$(echo "$5" | sed "s/'/''/g");

if git clone "https://$username:$password@$git" $temp_repo; then
   cd $(echo "$5" | sed "s/'/''/g") 
   git config --global --add safe.directory $(echo "$5" | sed "s/'/''/g")
else
    echo "Clone unsuccessful. your Git URL Maynot Right Or Not Public. "
    exit 1
fi

# Check if tag is "latest"
if [ "$tag" = "latest" ]; then
    # Check if tag named "latest" exists, if yes, checkout to the tag
    if check_tag  "$tag"; then
        echo "Checked out to tag $tag"
        exit 0
    fi

    # If tag "latest" doesn't exist, check if branch named "latest" exists
    if check_branch  "$tag"; then
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
if check_tag  "$tag"; then
    echo "Checked out to tag $tag"
    exit 0
fi

# If tag doesn't exist, check if branch exists, if yes, checkout to the branch
if check_branch  "$tag"; then
    echo "Checked out to branch $tag"
    exit 0
fi

# If neither tag nor branch found, return false
echo "Tag or branch not found"
exit 1
