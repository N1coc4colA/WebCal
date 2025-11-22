#!/usr/bin/env bash

# Database credentials
DB_HOST=${MYSQL_HOST}
DB_USER=${MYSQL_USER}
DB_ROOT_PASSWORD=${MYSQL_PASSWORD}
DB_PASSWORD=${MYSQL_PASSWORD}
DB_NAME=${MYSQL_DATABASE}

# Path to the SQL script
SQL_FILE="/var/www/html/db/db_setup.sql"

echo "SQL user: $DB_USER"
echo "SQL host: $DB_HOST"

#CREATE DATABASE IF NOT EXISTS $DATABASE; 
until mariadb --skip-ssl -h"$DB_HOST" -u"$DB_USER" -p"$DB_ROOT_PASSWORD" -e "USE $DB_NAME"; do
  echo "Database is not up yet. Waiting..."
  sleep 5 # wait for 5 seconds before check again
done

# Create DB first & authorize
echo "Creating DB..."
mariadb --skip-ssl -h"$DB_HOST" -u"$DB_USER" -p"$DB_ROOT_PASSWORD" <<< "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# Execute the SQL script using mysql
echo "Building DB..."
mariadb --skip-ssl -h"$DB_HOST" -u"$DB_USER" -p"$DB_ROOT_PASSWORD" "$DB_NAME" < "$SQL_FILE"

# Grant privileges
echo "Granting privileges..."
#mariadb -h$DB_HOST -u$DB_USER -p$DB_ROOT_PASSWORD <<< "
#CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY '$DB_PASSWORD';
#GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%';
#FLUSH PRIVILEGES;"

echo "DB setup complete."

apache2-foreground
