!#/bin/bash
export HOME=$(pwd)
apt update -y && sudo apt upgrade -y
apt install -y python3 dnsmasq nginx php8.*-fpm ca-certificates curl swaks nginx openssl
# Add Docker's official GPG key:
#docker install
install -m 0755 -d /etc/apt/keyrings

chmod a+r /etc/apt/keyrings/docker.asc
# Add the repository to Apt sources:
tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/debian
Suites: $(. /etc/os-release && echo "$VERSION_CODENAME")
Components: stable
Signed-By: /etc/apt/keyrings/docker.asc
EOF
apt update -y
apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
cd mailhog
docker compose up -d --build
sleep 5
cd ..
./send_mails.sh
#vygeneruje self-siged certifikát pro nginx server
openssl req -x509 -nodes -days 18250 -newkey rsa:6144 -keyout key.pem -out cert.pem -sha256 -config san.cnf

cd virus_server
python3 ./start.py &
cd ..

cp cert.pem /etc/ssl/certs/bad_cert.pem && cp key.pem /etc/ssl/private/bad_key.pem

chown www-data:www-data /etc/ssl/certs/bad_cert.pem
chown www-data:www-data /etc/ssl/private/bad_key.pem
cd ~/backend/
#vytvori soubory pro webovky a zkopíruje je do /var/www/
cp -r data/nginx_stranky/* /var/www/
rm -rfd /var/www/html

cp nginx_stranky/* /var/www/
rm /etc/nginx/sites-available/*
rm /etc/nginx/sites-enabled/*

cp data/nginx_conf/*  /etc/nginx/sites-available/

ln -s /etc/nginx/sites-available/* /etc/nginx/sites-enabled/

systemctl restart nginx

#dns servere bind9