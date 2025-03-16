FROM mariadb:latest

RUN apt-get update && apt-get install -y mariadb-client

# Copy the script into the container
COPY ./ /app/

# Make the script executable
RUN chmod +x /app/db_setup.sh

WORKDIR /app

# Run the script
ENTRYPOINT ["/app/db_setup.sh"]
