#!/bin/bash
# This is a nice hacky script that downloads a static snapshot of the website
# and stores it directly into the /docs directory, allowing Github to serve
# the website easily using Github.io. This has obvious issues, but was
# significantly quicker than upgrading the website to a more sensible
# templating engine.
#
# Ensure you have php, git, and wget installed!

if [ -z $(git status --porcelain) ];
then
    # Start the PHP server locally in the background
    php -S localhost:8000 router.php &
    serverPID=$!

    sleep 2  # Wait for server to start

    # Create a temporary directory to store the static website
    pushd .
    temporaryDir=$(mktemp -d)
    cd $temporaryDir

    # Recursively copy the website in a static manner
    wget --recursive \
         --page-requisites \
         --html-extension \
         --convert-links \
         --no-parent \
         --no-host-directories \
         http://localhost:8000

    # Kill the server
    popd
    kill $serverPID

    # Delete and create a new "gh-pages" branch
    currentBranch=$(git rev-parse --abbrev-ref HEAD)
    git branch -D gh-pages
    git checkout --orphan gh-pages

    # Clean the gh-pages branch
    git rm --cached -r .
    git clean -dxf

    # Move everything from the temp directory to here
    cp -a $temporaryDir/* .
    touch .nojekyll

    # Commit and push everything
    git add .
    git commit -am "Static snapshot of website."
    git push -u origin -f gh-pages
    git checkout $currentBranch
else
    echo "PLEASE COMMIT YOUR CHANGES FIRST!"
    echo git status
fi
