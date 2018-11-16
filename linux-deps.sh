# needed apt repositories
sudo apt install node npm php mysql-server php-xdebug php-mysql curl php-cli php-pear php-mbstring git unzip
# yarn setup
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt install yarn
# composer setup
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
#
# then check your php.ini it should be correct with zend_extension=xdebug_path etc, optionally install swoole too
php -i | grep 'php.ini'