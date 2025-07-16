#!/bin/bash

# Script to deploy content changes with date as commit message
# Usage: ./scripts/deploy-content.sh

# Get the current date in a readable format
DATE=$(date +"%Y-%m-%d %H:%M:%S")

# Change to the project root directory
cd "$(dirname "$0")/.."

# Add all changes in the content/ directory
echo "Adding content changes..."
git add content/

# Check if there are any changes to commit
if git diff --cached --quiet; then
    echo "No changes to commit in content/ directory."
    exit 0
fi

# Commit with the current date as the message
echo "Committing changes with date: $DATE"
git commit -m "Content update: $DATE"

# Push to the remote repository
echo "Pushing to remote repository..."
git push

echo "Content deployment completed successfully!"
