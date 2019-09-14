#!/bin/bash

mysql -u root --execute="DROP DATABASE IF EXISTS ust";
mysql -u root --execute="CREATE DATABASE IF NOT EXISTS ust";
php artisan migrate:refresh --seed
