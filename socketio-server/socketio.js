const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const io = require('socket.io')(server, {
  cors: {
    origin: '*',
  }
});

const port = process.env.PORT || 3000;

app.get('/', (req, res) => {
  res.send({test: "test"});
})

let users = {};

io.on('connection', (socket) => {
    //User connected
    let currentUserID = 0;
    socket.on('User connected', user => {
      currentUserID = user.id;
      users[user.username] = socket.id;
      socket.broadcast.emit('User connected', user);
    });

    socket.on('disconnect', () => {
      socket.broadcast.emit('User disconnected', currentUserID);
    });

    socket.on('User registered', () => {
      socket.broadcast.emit('User registered');
    })

    socket.on('chat message', (msg) => {
      io.emit('chat message', msg);
    });

    socket.on("private message", msg => {
      io.to(users[msg.receiver]).emit("private message", msg);
    });

    socket.on("private typing", receiver => {
      io.to(users[receiver.receiver]).emit("private typing", receiver);
    });
});



server.listen(port, () => {
  console.log('listening on *:3000');
});