!#/bin/bash
apt update -y && sudo apt upgrade -y
apt install -y python3 dnsmasq nginx php8.1-fpm ca-certificates curl sshd
# Add Docker's official GPG key:
#docker install
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/debian/gpg -o /etc/apt/keyrings/docker.asc
chmod a+r /etc/apt/keyrings/docker.asc
# Add the repository to Apt sources:
tee /etc/apt/sources.list.d/docker.sources <<EOF
Types: deb
URIs: https://download.docker.com/linux/debian
Suites: $(. /etc/os-release && echo "$VERSION_CODENAME")
Components: stable
Signed-By: /etc/apt/keyrings/docker.asc
EOF
apt update
apt install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
docker compose up -d --build
sleep 5
./send_mails.sh
#vygeneruje self-siged certifikát pro nginx server
openssl req -x509 -newkey rsa:6144 -keyout key.pem -out cert.pem -sha256 -days 18250 -nodes -subj "/C=CS/O=Let's Encrypt/CN=E8"
virus_server/start.sh &
cp cert.pem /etc/ssl/certs/bad_cert.pem
cp key.pem /etc/ssl/private/bad_key.pem
#vytvori soubory pro webovky a zkopíruje je do /var/www/
cd /var/www/

mkdir chatter
mkdir falesny_web
mkdir microsoft_support
mkdir transfer

cd ~

cp nginx_stranky/* /var/www/


cp data/nginx_conf/*  /etc/nginx/sites-available/
ln -s /etc/nginx/sites-available/* /etc/nginx/sites-enabled/
