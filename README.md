# How To Setup

1. Clone the repository
2. go to the application folder
3. run `mv .env.template .env`
4. Then SET variables like DOCKER_USER, DOCKER_PASS, APP_URL and MYSQL_ROOT_PASSWORD in .env file 
   without these app will not come up
4. run `chmod +x inital_setup.sh`
5. run `sudo ./inital_setup.sh your_non_root_user`
5. run `vagrant up` 
6. Now You will see admin username and password inside the logs
