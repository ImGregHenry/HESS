var mongoose = require('mongoose'),
    Schema = mongoose.Schema;

var statusModel = new Schema({
    title: { type: String },
    test: {type: String },
    enabled: {type: Boolean, default: false}
});

module.exports = mongoose.model('Status', statusModel);