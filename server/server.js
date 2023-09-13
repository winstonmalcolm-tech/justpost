const express = require('express');
const app = express()
const http = require('http');
const server = http.createServer(app);
const { Server } = require('socket.io');
const cors = require('cors');

app.use(cors());


const io = new Server(server, {
    cors: {
        origin: "http://localhost:84",
        methods: ['GET', 'POST']
    }
});

io.on('connection', (socket) => {
    console.log(`a user connected ${socket.id}`)

    socket.on("send_comment", (data) => {
        console.log(data)
        io.emit("receive_comment", data)
    });
});

server.listen(3002, () => {
    console.log('listening on port:3002');
});