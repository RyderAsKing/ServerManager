# Server Manager

Are you sick of having to log into hundreads of different website just to access your server? Well we got you, Server manager is a open source project made for people so that they can add the servers to one single place irrespective of their provider and manage it through one location. Feel free to setup a local instance of this, would be helpful to manage servers.

![Preview](https://raw.githubusercontent.com/RyderAsKing/ServerManager/main/review.gif)

## Supported softwares

| ID  | Softwares   | Supported |
| --- | ----------- | --------- |
| 1   | Virtualizor | Yes       |
| 2   | Pterodactyl | Yes       |

## Installation

The below installation steps are for Ubuntu OS only. Refer to other guides for installation on different OS.

### Dependencies

```bash
# Update
apt-get update && apt-get -y upgrade 

# Curl (used in several places during installation)
apt-get -y install curl 

# Installed apt-add-repository
apt -y install software-properties-common curl apt-transport-https ca-certificates gnupg

# Add Ondřej Surý PPA repository
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php

# Add Chris-lea redis server repository
add-apt-repository -y ppa:chris-lea/redis-server

# Download mariadb setup and install it
curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash

# Install Dependencies
apt -y install php8.0 php8.0-{cli,gd,mysql,pdo,mbstring,tokenizer,bcmath,xml,fpm,curl,zip} mariadb-server nginx tar unzip git redis-server

# Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Downloading files

```bash
mkdir -p /var/www/manager
cd /var/www/manager
git clone https://github.com/RyderAsKing/ServerManager.git ./
chmod -R 755 storage/* bootstrap/cache/
```

### Storage setup, API setup and Composer Installation

```bash
# Copy .env.example to .env
cp .env.example .env

# Composer install
composer install --no-dev --optimize-autoloader

# Only run the command below if you are installing this Panel for the first time
php artisan key:generate --force

# You should create a symbolic link from public/storage to storage/app/public
php artisan storage:link
```

### Database Setup

```bash
mysql -u root -p
CREATE DATABASE servermanager;
CREATE USER 'servermanager'@'127.0.0.1' IDENTIFIED BY 'USE_YOUR_OWN_PASSWORD';
GRANT ALL PRIVILEGES ON *.* TO 'servermanager'@'127.0.0.1' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```

### Configuration

```bash
nano .env
#Example .env vars
APP_NAME=Controlpanel
APP_URL=https://manager.yourdomain.com #The URL your site is located at

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=servermanager
DB_USERNAME=servermanager
DB_PASSWORD=USE_YOUR_OWN_PASSWORD
```

### Installing tables and setting up permission

```bash
php artisan migrate --seed --force
chown -R www-data:www-data /var/www/manager/*
```

# Example NGINX Config

```bash
nano /etc/nginx/sites-available/manager.conf
# Paste the code below in the file and then save and exit
server {
        listen 80;
        root /var/www/manager/public;
        index index.php index.html index.htm index.nginx-debian.html;
        server_name yourdomain.com; # Change this

        location / {
                try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        }

        location ~ /\.ht {
                deny all;
        }
}

# Enable NGINX Config
# You do not need to symlink this file if you are using CentOS.
sudo ln -s /etc/nginx/sites-available/manager.conf /etc/nginx/sites-enabled/manager.conf

# Check for nginx errors
sudo nginx -t

# You need to restart nginx regardless of OS. only do this you haven't received any errors
systemctl restart nginx
```

## Finishing up

Congratulations, you are now running a instance of server manager on your server.

### SSL (Optional but recommended)

```bash
# Make sure you have python3 installed
sudo add-apt-repository ppa:certbot/certbot
sudo apt-get update
sudo apt-get install python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

## Updating

### Enable Maintenance Mode

```bash
cd /var/www/manager
sudo php artisan down
```

### Downloading new files

```bash
sudo git stash
sudo git pull
sudo chmod -R 755 /var/www/manager
```

### Updating database

```bash
sudo php artisan migrate --seed --force
```

### Clear cache

```bash
sudo php artisan view:clear
sudo php artisan config:clear
```

### Updating dependencies

```bash
sudo composer install --no-dev --optimize-autoloader
```

### Updating permissions

```bash
sudo chown -R www-data:www-data /var/www/manager/*
```

### Restarting queue workers

```bash
sudo php artisan queue:restart
```

### Disable maintenance mode

```bash
sudo php artisan up
```

## Finishing up

Congratulations, you have successfully updated and are now running the latest instance of server manager on your server.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

The MIT License ([MIT](https://choosealicense.com/licenses/mit/))

Copyright (c) 2021 Server Manager by Ryder Asking

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
