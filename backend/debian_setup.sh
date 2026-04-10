!#/bin/bash
#nastaví dns resolver pro wsl a zakáže generování resolv.conf, aby se neztratily nastavení nameserveru
echo "generateResolvConf = false" | tee -a /etc/wsl.conf
echo "nameserver 9.9.9.9" | tee /etc/resolv.conf
#nainstaluje potřebné balíčky, včetně dockeru
apt update -y && sudo apt upgrade -y
apt install -y python3 nginx php8.*-fpm ca-certificates curl swaks nginx openssl python3-flask pip coreutils
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
#stáhne potřebné soubory pro CTF
#git clone https://github.com/theFivehead/CTF-interaktivni-osveta
#cd CTF-interaktivni-osveta

export HOME=$(pwd)

pip3 install python-whois --break-system-packages
cd whois_server/
nohup python3 whois_server.py > /dev/null 2>&1 &

cd backend/mailhog
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
rm -rfd /var/www/*
cp -r data/nginx_stranky/* /var/www/

rm /etc/nginx/sites-available/*
rm /etc/nginx/sites-enabled/*

cp data/nginx_conf/*  /etc/nginx/sites-available/

ln -s /etc/nginx/sites-available/* /etc/nginx/sites-enabled/
nginx -t

sleep 2
systemctl restart nginx
sudo apt remove pip
sudo apt autoremove
echo "Setup complete!"
nohup sleep 100000 &
