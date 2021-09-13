#! /bin/bash
set -e
set -x

###
# Expect code is being downloaded by jenkins on the job setup
###

DIR="$(pwd)"

# This is the ip of the server
PRIVATE_IP=$1
# This is to pass in method `lite` or `heavy`
BUILD_METHOD=$2

REMOTE_SERVER_USER=ubuntu
REMOTE_WORKDIR="/home/${REMOTE_SERVER_USER}/laravel-assessment"

cd $DIR/laravel-assessment;

rsync -ravzg \
    --groupmap=*:www \
    --cvs-exclude \
    --delete-after \
    --exclude .composer/ \
    --exclude vendor/ \
    --exclude storage/ \
    --exclude bootstrap/cache/ \
    -e ssh \
    laravel \
    ${REMOTE_SERVER_USER}@$PRIVATE_IP:${REMOTE_WORKDIR};

if [ "$BUILD_METHOD" = "heavy" ];
then
    ssh ${REMOTE_SERVER_USER}@$PRIVATE_IP "docker exec app composer install; \
        docker exec app php artisan migrate:status; \
        docker exec app php artisan migrate;";
fi

# This will run regardless
ssh ${REMOTE_SERVER_USER}@$PRIVATE_IP "docker exec app php artisan optimize; \
    docker exec app php artisan queue:restart;";