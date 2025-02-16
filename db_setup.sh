#!/bin/bash

# Database credentials
DB_HOST="localhost"
DB_USER="webcal-user"
DB_PASSWORD="webcal-pw"
DB_NAME="webcal"


# Path to the SQL script
SQL_FILE="db_setup.sql"

# Authorize user to access the DB
ACCESS_GRANT="
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'$DB_HOST' IDENTIFIED BY '$DB_PASSWORD';
FLUSH PRIVILEGES;"

# Create DB first & authorize
echo "Creating DB..."
sudo mysql --host=localhost --user=root --password=$DB_PASSWORD <<< "CREATE DATABASE IF NOT EXISTS webcal;"

# Execute the SQL script using mysql
echo "Building DB..."
sudo mysql --host=$DB_HOST --user=root --password=$DB_PASSWORD $DB_NAME < $SQL_FILE

echo "Granting privileges..."
sudo mysql --host=localhost --user=root --password=$DB_PASSWORD <<< "$ACCESS_GRANT"

# For PostgreSQL, you can use the following command:
# psql -h $DB_HOST -U $DB_USER -d $DB_NAME -f $SQL_FILE

echo "DB setup complete."
