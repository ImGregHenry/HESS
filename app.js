var express = require('express'),
    mongoose = require('mongoose');

var db = mongoose.connect('mongodb://localhost/hess');
var app = express();

var port = process.env.PORT || 3000;

var Status = require('./models/statusModel');
var myRouter = express.Router();

app.use('/api', myRouter);
myRouter.route('/status')
    .get(function(req,res){
        Status.find(function(err, statuses) {
            if(err) {
                console.log(err);
            } else {
                res.json(statuses);
            }
        });
    });

app.get('/', function(req, res) {
    res.send('welcome!');
});

app.listen(port, function() {
    console.log('Gulping away on PORT: ' + port);
});