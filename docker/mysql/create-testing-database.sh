#!/usr/bin/env bash
# Same behavior as vendor/laravel/sail/database/mysql/create-testing-database.sh
# Kept in-repo so Docker can bind-mount without relying on vendor/ (fixes WSL/Docker Desktop mount errors).

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS testing;
EOSQL

if [ -n "$MYSQL_USER" ]; then
mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    GRANT ALL PRIVILEGES ON \`testing\`.* TO '$MYSQL_USER'@'%';
EOSQL
fi
