#!/bin/bash

# function to setup the environment
setup() {
    echo "===================================================================="
    echo "If any error occur, please run: 'sudo apt update && sudo apt upgrade'"
    echo ""
    echo "This script requiere Python 3, venv and pip, make sure their are installed."
    echo "=> To install them, type: 'sudo apt install python3 python3-venv python3-pip'"
    echo "===================================================================="
    echo ""

    echo "Setting up server environment, it may take some time (~1min)..."
    python3 -m venv venv

    echo ""
    echo "Activating server environment..."
    source ./venv/bin/activate

    echo ""
    echo "Installing librairies..."
    pip install -r requierements.txt

    echo ""
    echo "===================================================================="
    echo "To run the server, type './setup.sh run'"
    echo "===================================================================="
}

run() {
    source ./venv/bin/activate
    python3 src/main.py
}

if [ "$1" = "" ]; then
    echo "Setting up the environement..."
    echo ""
    setup

elif [ "$1" = "run" ]; then
    echo "Running the server..."
    echo ""
    run

else
    echo "Unkown command, must be './setup.sh' or './setup.sh run'"
fi
