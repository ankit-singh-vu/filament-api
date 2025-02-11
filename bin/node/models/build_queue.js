'use strict';
module.exports = function (sequelize, DataTypes) {
    return sequelize.define('build_queues', {
        /*
        id:           {
            type: DataTypes.BIGINT(250),
            primaryKey: true,
            autoIncrement: true
        }, //DataTypes.BIGINT(250),
        */
        app_uuid: DataTypes.TEXT,
        path: DataTypes.TEXT,
        image_name: DataTypes.TEXT,
        status: DataTypes.STRING(250),
        details: DataTypes.TEXT,
        client: DataTypes.STRING(255),
        service: DataTypes.STRING(255),
        docker_file: DataTypes.STRING(255)
        /*
        createdAt: {
            allowNull: false,
            type: DataTypes.DATE,
            defaultValue: Date.now()
        },
        updatedAt: {
            allowNull: false,
            type: DataTypes.DATE,
            defaultValue: Date.now()
        }
        */
    }, {
        classMethods: {
            associate: function (models) {
                //associations can be defined here
                //models.Tenant.hasMany(models.tenantuser);
            }
        }
    });
};