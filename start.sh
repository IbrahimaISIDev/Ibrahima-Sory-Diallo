# start.sh
#!/bin/sh

php-fpm &
nginx -g "daemon off;"