#!/bin/bash

# Database credentials
DB_HOST="localhost"
DB_USER="webcal-user"
DB_PASSWORD="webcal-pw"
DB_NAME="webcal"

# Path to the SQL script
SQL_FILE="db_setup.sql"

# Execute the SQL script using mysql
mysql -h $DB_HOST -u $DB_USER -p$DB_PASSWORD $DB_NAME < $SQL_FILE

# For PostgreSQL, you can use the following command:
# psql -h $DB_HOST -U $DB_USER -d $DB_NAME -f $SQL_FILE

echo "Database setup complete."
