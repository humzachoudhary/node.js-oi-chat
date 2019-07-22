var mongoose = require("mongoose");
mongoose.Promise = global.Promise;
mongoose.connect("mongodb://localhost:27017/iochat");

var bodyParser = require('body-parser');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));





var nameSchema = new mongoose.Schema({
  name: String,
  msg: String
});

var User = mongoose.model("User", nameSchema);