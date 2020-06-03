#!/bin/bash
set -e

COLOR="\e[1;32m"
COLOR_END="\e[00m"

printf "$COLOR:: DB Migrate...$COLOR_END\n"
php artisan migrate --force

printf "$COLOR:: Finish DB setup !$COLOR_END\n"
