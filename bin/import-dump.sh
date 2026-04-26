#!/usr/bin/env bash
# Import a MariaDB/MySQL dump with fixes for Playa Alta / uniCenta imports.
# Usage: ./bin/import-dump.sh /path/to/dump.sql DB_NAME [DB_USER] [DB_HOST] [DB_PORT]
set -euo pipefail

DUMP="${1:?Usage: $0 dump.sql database [user] [host] [port]}"
DB_NAME="${2:?database name required}"
DB_USER="${3:-root}"
DB_HOST="${4:-127.0.0.1}"
DB_PORT="${5:-3306}"

WORKDIR=$(mktemp -d)
FIXED="$WORKDIR/fixed.sql"
cp "$DUMP" "$FIXED"

# Remove cross-database FK prefix pos3. -> same schema
sed -i 's/REFERENCES `pos3`\.`/REFERENCES `/g' "$FIXED"

# Avoid InnoDB row size limit with many VARCHAR(255) columns
sed -i 's/ROW_FORMAT=COMPACT/ROW_FORMAT=DYNAMIC/g' "$FIXED"

echo "Importing into $DB_NAME on $DB_HOST:$DB_PORT as $DB_USER ..."
read -r -s -p "Database password for $DB_USER: " DB_PASS
echo
export MYSQL_PWD="$DB_PASS"
mariadb -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" --default-character-set=utf8 "$DB_NAME" < "$FIXED"
unset MYSQL_PWD
rm -rf "$WORKDIR"
echo "Done."
