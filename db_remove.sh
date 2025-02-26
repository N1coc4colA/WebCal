#/bin/bash

sudo mysql --host=localhost --user=root --password=webcal-pw webcal <<< "
drop table PENDING_DT;
drop table AR_DT;
drop table INFO_DT;
drop table USR_DT;
"
