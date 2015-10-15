var express = require('express'),
    mongoose = require('mongoose'),
    bodyParser = require('body-parser');


var db = mongoose.connect('mongodb://localhost/hess');
var app = express();

var port = process.env.PORT || 3000;

var Status = require('./models/statusModel');

app.use(bodyParser.urlencoded({extended:true}));
app.use(bodyParser.json());

statusRouter = require('./routes/statusRoutes')(Status);
app.use('/api', statusRouter);


app.get('/', function(req, res) {
    res.send('welcome!');
});

app.listen(port, function() {
    console.log('Gulping away on PORT: ' + port);
});