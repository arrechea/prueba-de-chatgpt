#!/usr/bin/env bash

if hash supervisorctl 2>/dev/null; then
        echo 'Comprobando supervisor...'
    else
#    Install supervisor
        apt-get install supervisor -q -y && service supervisor start
    fi

DIRECTORY=$(cd `dirname $0` && pwd)
GAFAFIT_DIRECTORY="$(dirname "$DIRECTORY")"

#Limpiar carpeta supervisor
rm -rf /etc/supervisor/conf.d/
mkdir /etc/supervisor/conf.d/

#Copiar y pegar archivos supervisor
cp -R ${DIRECTORY}/supervisor/. /etc/supervisor/conf.d/

command_notification="${GAFAFIT_DIRECTORY}/artisan"
log_notification="${GAFAFIT_DIRECTORY}/worker-notifications.log"
log_log="${GAFAFIT_DIRECTORY}/worker-log.log"
supervisor_user=root

#modificar archivo notification para command
sed -i "s|command_notification|${command_notification}|" /etc/supervisor/conf.d/gafafit-notification.conf

#modificar archivo notification para user
sed -i "s|user_notification|${supervisor_user}|" /etc/supervisor/conf.d/gafafit-notification.conf

#modificar archivo notification para log
sed -i "s|log_notification|${log_notification}|" /etc/supervisor/conf.d/gafafit-notification.conf

#modificar archivo log para command
sed -i "s|command_notification|${command_notification}|" /etc/supervisor/conf.d/gafafit-log.conf

#modificar archivo log para user
sed -i "s|user_notification|${supervisor_user}|" /etc/supervisor/conf.d/gafafit-log.conf

#modificar archivo log para log
sed -i "s|log_notification|${log_log}|" /etc/supervisor/conf.d/gafafit-log.conf

#Releer y reiniciar
supervisorctl reread
supervisorctl update
supervisorctl restart all
