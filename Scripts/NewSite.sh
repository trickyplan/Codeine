rm -rf $1
mkdir $1
cp -r $Codeine/_Node/* $1
cd $1
mv Sites/Site.json Sites/$1.json
chmod -R 777 Temp
chmod -R 777 Data
