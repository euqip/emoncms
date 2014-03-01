# Installation on Linux

## Install dependencies

You may need to start by updating the system repositories

    sudo apt-get update

Install all dependencies:

    sudo apt-get install apache2 mysql-server mysql-client php5 libapache2-mod-php5 php5-mysql php5-curl php-pear php5-dev php5-mcrypt git-core redis-server build-essential ufw ntp

Install pecl dependencies (serial, redis and swift mailer)

    sudo pear channel-discover pear.swiftmailer.org
    sudo pecl install channel://pecl.php.net/dio-0.0.6 redis swift/swift
    
Add pecl modules to php5 config
    
    sudo sh -c 'echo "extension=dio.so" > /etc/php5/apache2/conf.d/20-dio.ini'
    sudo sh -c 'echo "extension=dio.so" > /etc/php5/cli/conf.d/20-dio.ini'
    sudo sh -c 'echo "extension=redis.so" > /etc/php5/apache2/conf.d/20-redis.ini'
    sudo sh -c 'echo "extension=redis.so" > /etc/php5/cli/conf.d/20-redis.ini'

### Enable mod rewrite

Emoncms uses a front controller to route requests, modrewrite needs to be configured:

    $ sudo a2enmod rewrite
    $ sudo nano /etc/apache2/sites-enabled/000-default

Change (line 7 and line 11), "AllowOverride None" to "AllowOverride All".
That is the sections <Directory /> and <Directory /var/www/>.
[Ctrl + X ] then [Y] then [Enter] to Save and exit.

Restart the lamp server:

    $ sudo /etc/init.d/apache2 restart
    
### Install the emoncms application via git

Git is a source code management and revision control system but at this stage we use it to just download and update the emoncms application.

First cd into the var directory:

    $ cd /var/

Set the permissions of the www directory to be owned by your username:

    $ sudo chown $USER www

Cd into www directory

    $ cd www

Download emoncms using git:

    $ git clone -b rework https://github.com/emoncms/emoncms.git
    
Once installed you can pull in updates with:

    git pull
    
### Create a MYSQL database

    $ mysql -u root -p

Enter the mysql password that you set above.
Then enter the sql to create a database:

    mysql> CREATE DATABASE emoncms;

Exit mysql by:

    mysql> exit
    
## Create data repositories for emoncms feed engine's

    sudo mkdir /var/lib/phpfiwa
    sudo mkdir /var/lib/phpfina
    sudo mkdir /var/lib/phptimeseries

    sudo chown www-data:root /var/lib/phpfiwa
    sudo chown www-data:root /var/lib/phpfina
    sudo chown www-data:root /var/lib/phptimeseries

### Set emoncms database settings.

cd into the emoncms directory where the settings file is located

    $ cd /var/www/emoncms/

Make a copy of default.settings.php and call it settings.php

    $ cp default.settings.php settings.php

Open settings.php in an editor:

    $ nano settings.php

Enter in your database settings.

    $username = "USERNAME";
    $password = "PASSWORD";
    $server   = "localhost";
    $database = "emoncms";

Save (Ctrl-X), type Y and exit

### Install add-on emoncms modules

Install additional modules:
    
    cd /var/www/emoncms/Modules
    
    git clone -b redismetadata https://github.com/emoncms/raspberrypi.git
    git clone -b redismetadata https://github.com/emoncms/event.git
    git clone https://github.com/emoncms/openbem.git
    git clone https://github.com/emoncms/energy.git
    git clone https://github.com/emoncms/notify.git
    git clone https://github.com/emoncms/report.git
    git clone https://github.com/emoncms/packetgen.git
    git clone https://github.com/elyobelyob/mqtt.git
 
See individual module readme's for further information on individual module installation.
