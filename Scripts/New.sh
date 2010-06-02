echo Создаём сайт $1
svn export http://testplace.ru:8080/svn/skeleton/ $1
cd $1
chmod 777 -R Data
echo Создаём MySQL базу $1
mysql -u root --password=$2 --execute="CREATE DATABASE $1;
USE $1;
source Install.sql"