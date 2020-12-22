export WORKING_DIRECTORY=/var/www/html
chown ubuntu:www-data $WORKING_DIRECTORY -R
chmod 750 $WORKING_DIRECTORY -R
chmod g+s $WORKING_DIRECTORY -R
chmod g+w $WORKING_DIRECTORY/logs/log
