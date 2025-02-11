console.log("hello world");
'use strict';
var Sequelize = require('sequelize');
var sequelize = new Sequelize(
    "application",
    "root",
    "CwmHFTMqS6TXqupE4DF9NqUXkj7juh8A6wSk",
    {
        host: "pxc",
        dialect: "mysql",
        logging: false
    }
);
var query = function (sql, cb) {
    sequelize.query(sql)
        .then(function (res, meta) {
            cb(res, true);
        }).catch(function (err) {
            cb(err, false);
        });
    return true;
};

var sql = "SELECT * FROM `user` WHERE `id`=6";

query(sql, function (result, state) {
   console.log(result);


});