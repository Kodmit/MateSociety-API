const express = require('express');
var jwtAuth = require('socketio-jwt-auth');
var lodash = require('lodash');
const app = express();
var fs = require('fs');

const server = app.listen(3001, function() {
    console.log('MateSociety Chat running on :3001');
}); 

const io = require('socket.io')(server);
const cert = fs.readFileSync('public.pem');

var onlineUsers = [];
var sockets = {};
var users =[];

io.use(jwtAuth.authenticate({
  secret: cert,    // required, used to verify the token's signature
  algorithm: 'RS256'        // optional, default to be HS256
}, function(payload, done) {
  return done(null, payload);
}));

io.on('connection', function(socket) {

  sockets[socket.id] = socket;
  
  var onlineSocketID = onlineUsers[socket.request.user.username];

  if (onlineSocketID && onlineSocketID !== socket.id) {
      // console.log("DISCONNECTED: " + onlineUsers[socket.request.user.username]);
      sockets[onlineSocketID].disconnect();
      delete sockets[onlineSocketID];
      lodash.pull(users, socket.request.user.username);
  }
  if (onlineSocketID !== socket.id) {
      onlineUsers[socket.request.user.username] = socket.id;
      users.push(socket.request.user.username);
      // console.log("CONNECTED: " + onlineUsers[socket.request.user.username]);
  }

  console.log(users);
  // now you can access user info through socket.request.user
  // socket.request.user.logged_in will be set to true if the user was authenticate

  socket.emit('success', {
    message: 'success logged in!',
    user: socket.request.user
  });

  setTimeout(() => {
    io.emit('ONLINE', users);
  }, 3000);

  socket.on('GET_ONLINE', function(data) {
    io.emit('ONLINE', users);
  });

  socket.on('IS_WRITING', function(data) {
    data.user = socket.request.user.username;
    io.emit('WRITING:' + data.userId, data);
  });

  socket.on('SEND_MESSAGE', function(data) {
    data.user = socket.request.user.username;
    io.emit('MESSAGE:' + data.userId, data);
  });
});
 