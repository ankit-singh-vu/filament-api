const { exec } = require('child_process');
const { Op } = require('sequelize');
var mariaDB = require("./models");
// require('dotenv').config();

function buildDockerImage(callback) {
    mariaDB.build_queues.findOne({
        where: {
            status: 'to_be_created'
        }
    }).then(function (resp) {
        if (resp) {
            if (resp.status == 'to_be_created') {
                mariaDB.user_profiles.findOne({
                    where: {
                        uuid: resp.client
                    },
                    order: [
                        ['id', 'ASC']
                    ]
                }).then(function (respo) {
                    console.log(resp.image_name);
                    var image_name = resp.image_name;
                    const arrayOfSubstrings = image_name.split(":");
                    const Dockerfile_name = resp.docker_file;
                    var path = resp.path;
                    var app_uuid = resp.app_uuid;
                    var userid = process.env.REGISTRY_USER;
                    var url = process.env.REGISTRY_DOMAIN;
                    const parts_url = url.split(':');
                    const project = resp.service;
                    var pass = process.env.REGISTRY_PASS;
                    var PRIVATETOKEN = process.env.PRIVATE_TOKEN;
                    var tenants = resp.client;
                    const dockerBuildCommand = 'cd ' + path + ' && docker build -t ' + image_name + ' -f ' + Dockerfile_name + ' .';
                    const dockerPushCommand = 'cd ' + path + ' && docker login ' + url + ' -u ' + userid + ' -p' + pass + ' && docker push ' + image_name;
                    const userCeck = "curl --location 'https://" + parts_url[0] + "/api/v4/users?username=" + tenants + "' --header 'PRIVATE-TOKEN: " + PRIVATETOKEN + "'";
                    const userdata = '{"name": "' + respo.first_name + '","email": "' + respo.email + '","username": "' + tenants + '","password": "' + tenants + '"}'
                    const userCreate = "curl --location 'https://" + parts_url[0] + "/api/v4//users' --header 'PRIVATE-TOKEN: " + PRIVATETOKEN + "' --header 'Content-Type: application/json' --data-raw  '" + userdata + "'";
                    mariaDB.build_queues.update(
                        { status: "running" },
                        {
                            where: {
                                id: resp.id,
                            },
                        }
                    );

                    exec(userCeck, (error, stdout, stderr) => {
                        if (error) {
                            console.error('failed:', error.message);
                            buildFaild(resp.id, error.message);
                        } else {
                            if (stdout == "[]") {
                                console.error("User Not Found");
                                console.log("Creating User...");
                                createUser(userCreate, resp.id, image_name, dockerPushCommand, dockerBuildCommand, parts_url[0], project, PRIVATETOKEN, tenants);
                            } else {
                                console.log('User found:', stdout);
                                let obj = JSON.parse(stdout);
                                isprojectExist(parts_url[0], project, resp.id, image_name, obj[0].id, PRIVATETOKEN, tenants, dockerPushCommand, dockerBuildCommand);
                            }

                        }
                    });
                });
            } else if (resp.status === "running") {
                console.log(resp.image_name + " is building....");
            }
        }
        else {
            console.log("there is no image to be build.... :(");
        }
    });

}

function isprojectExist(parts_url, project, id, image_name, user_id, PRIVATETOKEN, tenants, dockerPushCommand, dockerBuildCommand) {
    const paramz = "curl -k --location 'https://" + parts_url + "/api/v4/users/" + user_id + "/projects?search=" + project + "' --header 'PRIVATE-TOKEN: " + PRIVATETOKEN + "'";
    // console.log(paramz);
    exec(paramz, (error, stdout, stderr) => {
        if (error) {
            console.error('failed:', error.message);
            buildFaild(id, error.message);
        } else {
            if (stdout == "[]") {
                console.error("project Not Found");
                console.log("Creating project...");
                const data = '{"id": ' + user_id + ',"name": "' + project + '","description": "New Project is created for the UUID:' + tenants + ' "} ';
                const projectCreate = "curl -k --location 'https://" + parts_url + "/api/v4/projects/user/" + user_id + "' --header 'PRIVATE-TOKEN: " + PRIVATETOKEN + "' --header 'Content-Type: application/json' --data '" + data + "'";
                createProject(parts_url, project, id, image_name, user_id, PRIVATETOKEN, tenants, projectCreate, dockerPushCommand, dockerBuildCommand);
            } else {
                console.log('project found:', stdout);
                dockerBuild(dockerBuildCommand, id, image_name, dockerPushCommand);
            }

        }
    });
}



function dockerBuild(param, params, image_name, dockerPushCommand) {
    exec(param, (error, stdout, stderr) => {
        if (error) {
            console.error('Build failed:', error.message);
            buildFaild(params, error.message);
        } else {
            console.log('Image built successfully:', image_name);
            dockerPushImage(dockerPushCommand, params, image_name);

        }
    });
}

function dockerPushImage(param, params, image_name) {
    exec(param, (error, stdout, stderr) => {
        if (error) {
            console.error('push failed:', error.message);
            buildFaild(params, error.message);
        } else {
            console.log('Image Push successfully:', image_name);
            buildSucess(params);
        }
    });
}

function createProject(parts_url, project, id, image_name, user_id, PRIVATETOKEN, tenants, projectCreate, dockerPushCommand, dockerBuildCommand) {
    exec(projectCreate, (error, stdout, stderr) => {
        if (error) {
            console.error('Project Create failed:', error.message);
            buildFaild(id, error.message);
        } else {
            console.log("project created :", stdout);
            dockerBuild(dockerBuildCommand, id, image_name, dockerPushCommand);
        }
    });
}

function createUser(param, params, image_name, dockerPushCommand, dockerBuildCommand, parts_url, project, PRIVATETOKEN, tenants) {
    exec(param, (error, stdout, stderr) => {
        if (error) {
            console.error('User Create failed:', error.message);
            buildFaild(params, error.message);
        } else {
            console.log("User created :", stdout);
            let obj = JSON.parse(stdout);
            isprojectExist(parts_url, project, params, image_name, obj.id, PRIVATETOKEN, tenants, dockerPushCommand, dockerBuildCommand);
        }
    });
}


function buildSucess(params) {
    mariaDB.build_queues.destroy({
        where: {
            id: params
        }
    });

}
function buildFaild(params, param) {
    mariaDB.build_queues.update(
        {
            status: "failed",
            details: param
        },
        {
            where: {
                id: params,
            },
        }
    );
}

buildDockerImage();

setInterval(function () {
    buildDockerImage();
}, 10000);


