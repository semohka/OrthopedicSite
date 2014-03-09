#!/bin/bash

exiterr() {
    if [ "$1" -gt 0 ]; then
        if [ ! -z "$2" ]; then
            echo $2
        fi
        exit $1
    fi
}

##
# Установочные функции
##
# функция проверки, установлен ли пакет
installed() {
    if [ -z "$2" ]; then #если второй параметр $2 пустой
        if [ -f "/var/www/vagrant/var/installed-$1" ]; then
            return 0 # если есть файл, пакет установлен, выходим и возвращаем 0
        fi
        return 1 # файл не существует, пакет не установлен, выходим и возвращаем 1
    fi

    touch /var/www/vagrant/var/installed-$1 # создаем файл
}

# функция установки ПО
install() {
  installed $1
  if [ "$?" -gt 0 ]; then
    sudo apt-get install -q -y $1 || exiterr $? "$1 installation failed"
    installed $1 ok
  fi
}

install-phpunit() {
    installed phpunit
    if [ "$?" -gt 0 ]; then
        pear channel-discover pear.phpunit.de
        pear channel-discover components.ez.no
        pear channel-discover pear.symfony.com
        pear install --alldeps phpunit/PHPUnit
        pear install --alldeps phpunit/DbUnit
        pear install --alldeps phpunit/PHPUnit_Story
        pear install --alldeps phpunit/PHPUnit_Selenium
        installed phpunit ok
    fi
}


##
# Конфигурационные функции
##
configured() {
    if [ -z "$2" ]; then
        if [ -f /var/www/vagrant/var/configured-$1 ]; then
            return 0;
        fi
        return 1;
    fi

    touch /var/www/vagrant/var/configured-$1
}

configure-apc() {
    configured apc
    if [ "$?" -gt 0 ]; then
        sudo echo "apc.shm_size = 32" >> /etc/php5/apache2/conf.d/apc.ini
        sudo service apache2 restart
        configured apc ok
    fi
}

configure-php() {
    configured php54
    if [ "$?" -gt 0 ]; then
        sudo cp /var/www/vagrant/php.ini /etc/php5/apache2/
        configured php54 ok
    fi
}

configure-mysql() {
    configured mysql
    if [ "$?" -gt 0 ]; then
        sudo cp /var/www/vagrant/my.cnf /etc/mysql/
        configured mysql ok
    fi
}

configure-locale() {
    configured locale
    if [ "$?" -gt 0 ]; then
        echo "Europe/Moscow" | sudo tee /etc/timezone
        Europe/Moscow
        sudo dpkg-reconfigure --frontend noninteractive tzdata
        configured locale ok
    fi
}

configure-mod-rewrite() {
    configured mod-rewrite
    if [ "$?" -gt 0 ]; then
        sudo cp /var/www/vagrant/apache2.conf /etc/apache2/apache2.conf
        configured mod-rewrite ok
    fi
}


##
# Прочие функции
##
update-apt() {
    configured apt-update
    if [ "$?" -gt 0 ]; then
        sudo apt-get update && sudo DEBIAN_FRONTEND=noninteractive apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" --force-yes -fuy upgrade
        configured apt-update ok
#       --force-confdef: Это поведение по умолчанию dpkg и этот параметр в основном используется в сочетании с --force-confold.
#       -force-confold: не изменять текущую конфигурацию в файл, новая версия устанавливается с .dpkg-dist suffix.
#       С помощью этой опции в покое, даже конфигурационные файлы, которые вы не изменяли, остануться нетронутыми.
#       Вам надо объединить его с --force-confdef, чтобы позволить dpkg перезаписать файлы конфигурации, которые вы не изменяли.
    fi
}

load-migrations() {
    /var/www/protected/yiic migrate up || exiterr $? "Failed to load migrations!"
}

add-php54-repository() {
    configured php54
    if [ "$?" -gt 0 ]; then
        sudo add-apt-repository -y ppa:ondrej/php5-oldstable && apt-get update
        configured php54 ok
    fi
}


### Выполнение ###
update-apt
configure-locale
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password 1111'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 1111'
install mysql-server
install mysql-client
install update-manager
install virtualbox-guest-utils
install python-software-properties
add-php54-repository
install apache2
install curl
install php5
install php5-mysql
install php5-json
install php5-gd
install php-apc
install php-pear
install php5-xdebug
install apache2-utils

pear upgrade pear
pear upgrade
install-phpunit

configure-apc
configure-mysql
configure-php
configure-mod-rewrite

#load-migrations

sudo service apache2 restart && echo -e "\napache is ready!"
sudo service mysql restart && echo "mysql is ready!"

exit 0