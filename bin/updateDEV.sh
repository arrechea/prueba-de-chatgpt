#!/usr/bin/env bash

echo "Estas seguro que quieres continuar? (y/n)"
read respuestaAlCommit
if [ "$respuestaAlCommit" != 'y' ];then
    exit
fi

composer install
php artisan migrate:fresh
php artisan db:seed --class=DatabaseTestSeeder


#Comandos para el API
#echo "Quieres generar credenciales para el API? (y/n)"
#read respuestaAlCommit
#if [ "$respuestaAlCommit" != 'y' ];then
#    exit
#fi
#
#php artisan passport:client --password
#php artisan passport:install
