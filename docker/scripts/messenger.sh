#!/usr/bin/env bash
sleep 10; #wait some time until other services started
if [ ! -d /var/lib/mysql/sre-app ] ; then
    /var/www/app/bin/console doctrine:schema:create
fi
/var/www/app/bin/console messenger:consume async -vv >&1;