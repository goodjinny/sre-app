#!/usr/bin/env bash
sleep 10; #wait some time until other services started
/var/www/app/bin/console messenger:consume async -vv >&1;