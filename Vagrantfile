Vagrant.configure(2) do |config|
    config.vm.define "controller" do |controller|
      controller.vm.box = "ubuntu/jammy64"
      controller.vm.hostname = "controller"
      controller.vm.box_check_update = false
      controller.vm.network "private_network", ip: "192.168.62.101"
      controller.vm.synced_folder "..", "/mnt/components/"
      controller.vm.provider "virtualbox" do |vb|
      vb.memory = "4096"
    end
    controller.vm.provision "shell", inline: <<-SHELL
      export DEBIAN_FRONTEND=noninteractive
      rm /etc/resolv.conf

      echo "nameserver 8.8.8.8" > /etc/resolv.conf

      sudo apt-get update

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

      sudo apt-get -y install nodejs jq docker-ce docker-compose gnupg2 pass sshpass



      if [ -f /mnt/components/application/.env ]; then
        source /mnt/components/application/.env
      else
        echo ".env file not found!"
        exit 1
      fi

      # Check if DOCKER_URL is set
      if [ -z "$DOCKER_URL" ]; then
        echo "DOCKER_URL is not set in the .env file!"
        exit 1
      fi

      if [ -z "$DOCKER_USER" ]; then
        echo "DOCKER_USER is not set in the .env file!"
        exit 1
      fi

      if [ -z "$DOCKER_PASS" ]; then
        echo "DOCKER_PASS is not set in the .env file!"
        exit 1
      fi

      docker volume create portainer_data

      docker run -d -p 8000:8000 -p 9000:9000 --name=portainer --restart=always -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer
      
      # Docker Login 
      sudo docker login $DOCKER_URL --username $DOCKER_USER --password $DOCKER_PASS

      sudo chmod 0777 -R /mnt/components/application/storage
      
      sudo mkdir -p /tmp/work && sudo chmod 0777 /tmp/work/

      cd /mnt/components/application && docker-compose up -d
      cat /mnt/components/application/test.pg

    SHELL
  end
end