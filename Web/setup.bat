:: This script setup the node environement and build the website
ECHO OFF
IF "%1" == "" GOTO :Setup
IF "%1" == "build" GOTO :Build
ELSE ECHO unkown parameter: %1, must be empty or 'build'


:Setup
ECHO Setting up the environement...
IF EXIST dist RMDIR /s dist 
npm install
ECHO Environement ready!
EXIT

:Build
ECHO Building the website...
IF EXIST dist (RMDIR /s /Q dist)
MKDIR dist
CALL npx tailwindcss -i ./src/css/input.css -o ./src/css/global.css
XCOPY /s /Y /Q src\\*.* dist\\
DEL .\\dist\\css\\input.css .\\dist\\composer.*
ECHO Website is ready in the dist folder