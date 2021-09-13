#!/bin/sh

type prerequisites >/dev/null 2>&1 || {
    sudo apt-get update -y;

    sudo apt-get install -y \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common \
    python \
    nscd \
    pv \
    make \
    mongo-tools \
    mongodb-clients \
    awscli \
    mysql-client-5.7 \
    redis-tools \
    build-essential;

    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -;
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -;

    sudo add-apt-repository \
    "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
    $(lsb_release -cs) \
    stable";

    sudo apt-get update -y;

    sudo apt-get install -y docker-ce;

    sudo usermod -aG docker $(whoami);

    sudo nscd -i group;

    sudo service docker restart;

    sudo curl -L https://github.com/docker/compose/releases/download/1.19.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose;

    sudo chmod +x /usr/local/bin/docker-compose;

    # Precausions: this command prunes all your container
    docker system prune --force --all;

    touch ~/.profile

    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash

    export NVM_DIR="$HOME/.nvm"

    . ~/.nvm/nvm.sh
    . ~/.profile
    . ~/.bashrc

    nvm install 12.22.5;
    npm install --global yarn;
}

