#!/usr/bin/env bash

cd "`dirname "$0"`"
cd ..
Codeine=`pwd`
echo "Codeine Work Directory: $Codeine"
sudo ln -sfn $Codeine/etc/nginx/conf.d/* -t /etc/nginx/conf.d/
ls -la /etc/nginx/conf.d/
sudo ln -sfn "$Codeine/src" /usr/share/php/Codeine
ls -la /usr/share/php/Codeine
sudo ln -sfn "$Codeine/etc/init.d/codeined" /etc/init.d/codeined
ls -la /etc/init.d/codeined
sudo ln -sfn "$Codeine/usr/bin/*" -t /usr/bin/
ls -la /usr/bin/codeine*