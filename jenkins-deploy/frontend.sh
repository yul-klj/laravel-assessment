#! /bin/bash
set -e
set -x

###
# Expect code is being downloaded by jenkins on the job setup
###

DIR="$(pwd)"

# This is the ip of the server
PRIVATE_IP=$1
# This is to pass in `http://localhost:8080/api/v1` as an example
BASE_API_URL=$2

REMOTE_SERVER_USER=ubuntu
REMOTE_WORKDIR="/home/${REMOTE_SERVER_USER}/laravel-assessment/frontend"

cd $DIR/laravel-assessment/frontend;

# This is to replace the base api url
sed -i "s|http://localhost:8080/api/v1|$BASE_API_URL|g" ./src/http-common.js;

# This will build frontend into compiled version
yarn build;

rsync -ravzg \
    --groupmap=*:www \
    --cvs-exclude \
    --delete-after \
    -e ssh \
    build \
    ${REMOTE_SERVER_USER}@$PRIVATE_IP:${REMOTE_WORKDIR};