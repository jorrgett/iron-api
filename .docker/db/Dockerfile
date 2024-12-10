# pull official base image
FROM postgres:15.2-alpine

# run create.sql on init
ADD create.sql /docker-entrypoint-initdb.d

WORKDIR /home/db

COPY all_sequences.sql /home/db/all_sequences.sql
COPY ./data_dump/*.sql /home/db/
COPY ./functions/*.sql /home/db/
COPY ./store_procedures/*.sql /home/db/
COPY ./views/*.sql /home/db/

COPY run_db.sh /usr/bin/run_db.sh
RUN chmod +x /usr/bin/run_db.sh