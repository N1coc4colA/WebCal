#/usr/bin/env bash

sudo mysql --host=${MYSQL_HOST} --user=root --password=${MYSQL_ROOT_PASSWORD} webcal <<< "
drop table PENDING_DT;
drop table AR_DT;
drop table USR_DT;
"
