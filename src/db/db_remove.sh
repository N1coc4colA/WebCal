#/usr/bin/env bash

sudo mariadb --host=${MYSQL_HOST} --user=${MYSQL_USER} --password=${MYSQL_PASSWORD} webcal <<< "
drop table PENDING_DT;
drop table AR_DT;
drop table USR_DT;
"
