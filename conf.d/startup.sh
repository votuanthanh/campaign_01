#!/bin/sh
# startup.sh

systemctl restart apache2
pm2 start Node/server.js
/usr/sbin/apache2ctl -D FOREGROUND
supervisord -c /etc/supervisor/supervisord.conf
supervisorctl -c /etc/supervisor/supervisord.conf
