#!/bin/bash
DIR=/opt/lampp/htdocs/seguridad/resguardo
F=$(date +%Y-%m-%0e)
export PGUSER=postgres
export PGPASSWORD=sistemas2010
[!$DIR] && mkdir -p $DIR || :
LIST=$(psql -l | awk '{ print $1}' | grep -vE '^-
|^Listado|^notariado|template[0|1]')
#LIST="ventas produccion almacen"
for d in $LIST
do
pg_dump $d | gzip -c > $DIR/$d$F.out.gz
done
unset PGUSER
unset PGPASSWORD
