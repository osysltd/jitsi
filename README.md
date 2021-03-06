# Lumen Jitsi Meet

[Laravel Lumen](https://github.com/laravel/lumen) is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

[Jitsi Meet](https://github.com/jitsi/jitsi-meet/) is an open-source (Apache) WebRTC JavaScript application that uses [Jitsi Videobridge](https://jitsi.org/videobridge) to provide high quality, [secure](#security) and scalable video conferences.

[Prosody](https://prosody.im/) is a modern XMPP communication server. It aims to be easy to set up and configure, and efficient with system resources. Additionally, for developers it aims to be easy to extend and give a flexible system on which to rapidly develop added functionality, or prototype new protocols.

This is the project which implies all the mentioned above systems and technologies and allows hosts to create web conferences and meetings, set payment amount and allows hosts to receive payments from participants of the conferences and meetings. It can be applicable for studies, courses and other conferences and meetings for which the host wants to receive payments directly. Payment system is [Yandex Money](https://money.yandex.ru/) but the database schema and code can be adjusted to use any other payment system accordigly.

## Installation

### Prosody
```sh
sudo echo deb http://packages.prosody.im/debian $(lsb_release -sc) main | sudo tee -a /etc/apt/sources.list.d/jitsi-stable.list
wget https://prosody.im/files/prosody-debian-packages.key -O- | sudo apt-key add -
sudo apt update
sudo apt install -y prosody
```

### Jitsi Meet
```sh
sudo echo 'deb https://download.jitsi.org stable/' >> /etc/apt/sources.list.d/jitsi-stable.list
wget -qO -  https://download.jitsi.org/jitsi-key.gpg.key | apt-key add -
sudo apt update
sudo apt install -y jitsi-meet lua-dbi-mysql mercurial mc mysql-client-core-5.7
```

### Cleanup process
Just in case you may want to reinstall everything from scratch.
```sh
sudo apt remove -y --purge jitsi-meet jitsi-meet-prosody jitsi-videobridge* jicofo jitsi-meet-web jitsi-meet-web-config  jitsi-meet-turnserver
sudo apt remove -y --purge nginx*
sudo apt remove -y --purge prosody
sudo apt autoremove -y --purge
rm -rf /etc/turnserver.conf /var/www/html/ /etc/nginx/ /usr/share/jitsi-meet/ /etc/prosody/ /var/lib/prosody/ /usr/lib/prosody/
```


### Optional
#### Assign permissions
```sh
sudo chown ubuntu:ubuntu -R /etc/prosody/conf.avail/ /etc/jitsi/meet/ /etc/nginx/sites-available/ /etc/nginx/sites-enabled/
sudo chown ubuntu:ubuntu /usr/lib/prosody/modules/
```

#### Prepare database
```sh
mysql --host=<host> --user=<user> --password=<password>
SHOW DATABASES;
CREATE DATABASE <DB>;
USE <DB>;
SHOW TABLES;
```

### Location of configuration files
```
/etc/prosody/conf.avail/
/etc/jitsi/meet/
/etc/nginx/sites-available/
/usr/lib/prosody/modules/
```

### Network configuration
* 80/tcp - frontend
* 443/tcp - frontend
* 4443/tcp - videobridge (fallback)
* 10000/udp - videobridge (media)
* 5269/tcp - XMPP federation
* 5222/tcp - XMPP
* 4446/tcp/udp - stun
* 443/udp - turns

### Install SSL certificate
```sh
sudo /usr/share/jitsi-meet/scripts/install-letsencrypt-cert.sh
```

### Jitsi Meet configuration
Following the guide for [Secure Domain](https://github.com/jitsi/jicofo#secure-domain)
```sh
sudo vim /etc/jitsi/meet/<host>-config.js
```
```javascript
anonymousdomain: 'guest.<host>',
enableUserRolesBasedOnToken: true,
defaultLanguage: 'ru'
stunServers: []
enableWelcomePage: false,
```
```sh
sudo vim /etc/jitsi/jicofo/sip-communicator.properties
org.jitsi.jicofo.auth.URL=XMPP:<host>
```

### Coturn configuration
```sh
vim /etc/turnserver.conf
realm=<host>
cert=/etc/letsencrypt/live/<host>/fullchain.pem
pkey=/etc/letsencrypt/live/<host>/privkey.pem
```

### Prosody configuration
```sh
sudo vim /etc/prosody/conf.avail/<host>.cfg.lua
```

#### SQL backend Jitsi Meet users
```sh
sudo sed -n 's/^ *JICOFO_AUTH_PASSWORD= *//p' /etc/jitsi/jicofo/config && sudo prosodyctl register focus auth.<host>
sudo sed -n 's/^ *JVB_SECRET= *//p' /etc/jitsi/videobridge/config && sudo prosodyctl register jvb auth.<host>
```

### Restart all services
```sh
sudo /etc/init.d/prosody restart && sudo /etc/init.d/jitsi-videobridge2 restart && sudo /etc/init.d/jicofo restart && sudo /etc/init.d/coturn restart && sudo /etc/init.d/nginx restart && sudo tail -f -n0 /var/log/prosody/prosody.log
```

## Lumen Installation
Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

### Install dependencies
```sh
sudo apt install -y php-fpm php-mysql php-mbstring php-xml composer 
```

### App deployment
Create [Personal Access Token](https://github.com/settings/tokens)
```sh
sudo git clone https://github.com/osysltd/jitsi/ /var/www/jitsi/
sudo chown -R ubuntu:www-data /var/www/jitsi/
sudo chmod -R 2775 /var/www/jitsi/
git config credential.helper store
git pull origin master
cp /var/www/jitsi/.env.example /var/www/jitsi/.env
composer install
composer dump-autoload --optimize
```

### Configuration .env
```php
APP_DEBUG=false
APP_TIMEZONE=Europe/Moscow

PROSODY_HOST=<host>
SESSION_SECURE_COOKIE=true
SESSION_LIFETIME=240

YANDEX_KEY=<yandex_key>
YANDEX_SECRET=<yandex_secret>
YANDEX_REDIRECT_URI=<yandex_redirect_uri>

DB_CONNECTION=mysql
DB_HOST=<db_host>
DB_PORT=3306
DB_DATABASE=<db_name>
DB_USERNAME=<db_user>
DB_PASSWORD=<db_password>
SESSION_DRIVER=database
```

### Nginx configuration
Upload Nginx configuration for host and enable it
```sh
sudo ln -s /etc/nginx/sites-available/<host>.conf /etc/nginx/sites-enabled/<host>.conf
```

### Coturn Turn Server configuration
Adjust path to certificate and key
```ssh
sudo vim /etc/turnserver.conf
```


## License
The project is not for distribution and commercial use.
