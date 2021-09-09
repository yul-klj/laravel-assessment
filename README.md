# Assessment Environment

A collection of script for assessment project.

### Structure Explanation
- `dependencies` is the ubuntu machine / cloud vm prerequisites installation script
- `docker` is the docker files and docker-compose file placed
  - `app` is the application container Dockerfile placed
  - `mysql` is the mysql container needed configuration files placed
  - `nginx` is the nginx webserver container needed configuration files placed
- `laravel` is the application sourcecode folder

### How to start
*This setup is based on ubuntu to develop, could try out on mac, might varries*

- Kindly install run the bash script `dependencies/prerequisites.sh` in your ubuntu machine / cloud vm.

- Clone this repositary locally

After cloning those repositories:
  - Go to docker folder: `cd docker`
  - Spin up the containers: `docker-compose up -d --build app-mysql app webserver`
  - Run below command for `-mysql` container for mysql database creation
  ```
  docker exec app-mysql mysql -psecret -e "create database laravel;"
  ```
  - Run below command for `app` container for composer dependencies install
  ```
  docker exec app composer install
  ```

### Hosting Details
- Access laravel api with `http://localhost:8080/api`

### Docker Compose Commands
- `docker-compose up -d --build ${app-name}` this will build the container which indicates in docker-compose.yml
  - `up` is to spin the container
  - `-d` is daemon mode, which wont hog your cli, and runs in background, for debug container purpose, can remove this
  - `--build` is to rebuild the container based on dev-ops config, if there is no other changes on this, can remove this
- `docker start ${app-name}` will start the container, not advice to use this if you do not know what you are doing, it will just start the existing container without building it, use command above
- `docker stop ${app-name}` will stops the container
- `docker exec -it ${app-name} bash` will able to ssh into the container, does not apply to nginx container, the alpine container does not have bash
- `docker logs -f ${app-name}` to view the container running logs and output