#!/bin/bash

# function to setup the environment
setup() {
    echo "===================================================================="
    echo "If any error occur, please run: 'sudo apt update && sudo apt upgrade'"
    echo ""
    echo "This script requiere nodejs and npm, make sure their are installed."
    echo "=> To install them, type: 'sudo apt install nodejs npm'"
    echo "===================================================================="
    echo ""

    echo "Setting up environment, it may take some time (~1min)..."
    npm i

    echo ""
    echo "===================================================================="
    echo "To build the website, type './setup.sh build'"
    echo "===================================================================="
}

build() {
    rm -rf ./dist
    npx tailwindcss -i ./src/css/input.css -o ./src/css/global.css
    mkdir ./dist
    cp -R ./src/* ./dist
    rm ./dist/css/input.css
    rm ./dist/composer.json ./dist/composer.lock
}

if [ "$1" = "" ]; then
    echo "Setting up the environement..."
    echo ""
    setup

elif [ "$1" = "build" ]; then
    echo "Building..."
    echo ""
    build

else
    echo "Unkown command, must be './setup.sh' or './setup.sh build'"
fi
