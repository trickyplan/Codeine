#!/usr/bin/env bash

cd "`dirname "$0"`"
cd ..
Codeine=`pwd`
echo "Codeine Work Directory: $Codeine"
sudo ln -sf "$Codeine/etc/nginx/conf.d/codeine-development.nginx" /etc/nginx/conf.d/codeine-development.nginx
sudo ln -sf "$Codeine/etc/nginx/conf.d/codeine-production.nginx" /etc/nginx/conf.d/codeine-production.nginx
sudo ln -sf "$Codeine/etc/nginx/conf.d/access.conf" /etc/nginx/conf.d/access.conf
ls -la /etc/nginx/conf.d/
sudo ln -sf "$Codeine/src" /usr/share/php/Codeine
ls -la /usr/share/php/Codeine
sudo ln -sf "$Codeine/etc/init.d/codeined" /etc/init.d/codeined
ls -la /etc/init.d/codeined