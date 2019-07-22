var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io =  require('socket.io').listen(server);
var mongoose = require('mongoose');
var mongo = require('mongodb').MongoClient;
users = [];
connections = [];

server.listen(process.env.PORT || 3000);
console.log('Server running...');

mongoose.connect('mongodb://localhost/chat', function(err){
	if(err){
		console.log(err);
	} else{
		console.log('Connected to MongoDB...');
	}
});

//collection names in db
var chatSchema = mongoose.Schema({
	user: String,
	msg: String,
	created: {type: Date, default: Date.now}
});

var Chat = mongoose.model('Message', chatSchema);



app.get('/', function(req, res){
	res.sendFile(__dirname + '/index.html')
});
app.get('/index', function(req, res){
	res.sendFile(__dirname + '/index.html')
});

app.get('/register', function(req, res){
	res.sendFile(__dirname + '/register.html')
});

io.sockets.on('connection', function(socket){
	connections.push(socket);
	console.log('Connected: %s sockets connected', connections.length);

	//When new user joins, chat retrieves 10 previous messages
	var query = Chat.find({});
		query.sort('-created').limit(10).exec(function(err, docs){
			if(err) throw err;
			socket.emit('load old messages', docs);
		});

		//Disconnect
		socket.on('disconnect', function(data){
			users.splice(users.indexOf(socket.username), 1);
			updateUsernames();
			connections.splice(connections.indexOf(socket), 1);
			console.log('Disconnected: %s socket connected', connections.length);
		});

		//When sending message and displaying
		socket.on('send message', function(data){
			var newMsg = new Chat({msg: data, user: socket.username});
			newMsg.save(function(err){
				if(err) throw err;
			io.sockets.emit('new message', {msg: data, user: socket.username});
			});
		});

		//New user
		socket.on('new user', function(data, callback){
			callback(true);
			socket.username = data;
			users.push(socket.username);
			updateUsernames();
		});

		function updateUsernames(){
			io.sockets.emit('get users', users);
		}
});