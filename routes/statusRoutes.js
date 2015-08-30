var express = require('express');

var routes = function(Status){
    var statusRouter = express.Router();
    statusRouter.route('/status/:statusId')
        .get(function(req,res){
            Status.findById(req.params.statusId, function(err, status) {
                if(err) {
                    console.log(err);
                    res.status(500).send(err);
                } else {
                    res.json(status);
                }
            });
        });

    statusRouter.route('/status')
        .post(function(req, res){
            var status = new Status(req.body);
            status.save();
            console.log(status);
            res.status(201).send(status);
        })
        .get(function(req,res){
            Status.find(function(err, statuses) {
                if(err) {
                    console.log(err);
                    res.status(500).send(err);
                } else {
                    res.json(statuses);
                }
            });
        });
    return statusRouter;
};

module.exports = routes;