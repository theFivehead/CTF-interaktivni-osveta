#!/bin/bash
sleep 10
nginx -t
service php8.4-fpm start
service nginx start
cd /home/ctf/whois_server
nohup python3 whois_server.py > /dev/null 2>&1 &
cd /home/ctf
./send_mails.sh
cd /home/ctf/virus_server
python3 ./start.py