#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

echo "creating cache folder"
cd /home/vagrant/jetlag
mkdir -p /home/vagrant/jetlag/bootstrap/cache
echo "installing composer dependencies"
composer install
echo "installing node dependencies"
npm install -g npm
npm install
echo "installing bower dependencies"
npm install -g bower
bower install
echo "installing ruby"
apt-get install ruby
echo "deploying"
gulp
echo "generating application key"
php artisan key:generate
echo "building DB"
php artisan migrate
echo "indexing articles"
php artisan index:articles
