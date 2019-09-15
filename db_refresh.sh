#!/bin/bash

mysql -u root --execute="DROP DATABASE IF EXISTS ust";
mysql -u root --execute="CREATE DATABASE IF NOT EXISTS ust";

echo pam | sudo -Siu postgres dropdb ust;
echo pam | sudo -Siu postgres createdb ust;

rm database/database.sqlite;
touch database/database.sqlite;

php artisan migrate:refresh --seed
