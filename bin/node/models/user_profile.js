'use strict';
module.exports = function (sequelize, DataTypes) {
    return sequelize.define('user_profiles', {
        /*
        id:           {
            type: DataTypes.BIGINT(250),
            primaryKey: true,
            autoIncrement: true
        }, //DataTypes.BIGINT(250),
        */
        uuid: DataTypes.STRING(255),
        first_name: DataTypes.STRING(255),
        middle_name: DataTypes.STRING(255),
        last_name: DataTypes.STRING(255),
        email: DataTypes.STRING(255)

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