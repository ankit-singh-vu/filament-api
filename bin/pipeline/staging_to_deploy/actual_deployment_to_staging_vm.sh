#! /bin/bash

#deploy_to_staging_vm.sh

DOCKER_URL=$1
DOCKER_USER=$2
DOCKER_PASS=$3
DATABASE_PASS=$4
DATABASE_PASS_ROOT=$5
STAGING_APP_URL=$6

# Function to install Docker
install_docker_and_all() {
  echo "Installing Docker..."
  sudo apt-get update -y 
  sudo apt-get -y install \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg-agent \
        software-properties-common
  curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

  sudo apt-key fingerprint 0EBFCD88

  sudo add-apt-repository \
      "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
      $(lsb_release -cs) \
      stable"
  sudo apt-get update

  sudo apt-get -y install nodejs jq docker-ce docker docker-compose gnupg2 pass sshpass 
}




# Check if Docker is installed
if ! command -v docker &> /dev/null; then
  install_docker_and_all
else
  echo "Docker is already installed."
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
  install_docker_and_all
else
  echo "Docker Compose is already installed."
fi


cp code_ssh_key ~/.ssh/id_rsa && chmod 0600 ~/.ssh/id_rsa
sudo cp code_ssh_key /root/.ssh/id_rsa && chmod 0600 /root/.ssh/id_rsa

sudo mkdir -p /mnt/components && cd /mnt/components



if [ -d "/mnt/components/application" ]; then
  GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" sudo git clone git@xps1902.origin.encodiant.triophase.com:convesio/application.git 
else
  echo "The /mnt/components/application folder exists ."
fi


echo "sudo docker login $DOCKER_URL --username $DOCKER_USER --password $DOCKER_PASS";
sudo docker login $DOCKER_URL --username $DOCKER_USER --password $DOCKER_PASS
cd /mnt/components/application

sudo git fetch --all && sudo git pull --all 
sudo git checkout development -f
GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" sudo git pull


if [ -f "./.env" ]; then
  echo "The file exists."
else
  sudo cp .env.template .env
fi


sudo sed -i "s|\${APP_URL}|$STAGING_APP_URL|g" .env
sudo sed -i "s|\${DATABASE_PASS}|$DATABASE_PASS|g" .env
sudo sed -i "s|\${DATABASE_PASS_ROOT}|$DATABASE_PASS_ROOT|g" .env

sudo docker-compose down
sudo docker-compose up -d 



