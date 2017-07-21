var app = require('express')()
var server = require('http').Server(app)
var io = require('socket.io')(server)
var redis = require('redis')

server.listen(8890)
var userConnection = []

var redisClient = redis.createClient(6379, 'redis')
redisClient.subscribe('singleChat', 'groupChat')
redisClient.on('message', function(channel, data) {
    var message = JSON.parse(data)

    if (parseInt(message.to)) {
        io.sockets.in(message.to).in(message.from).emit(channel, data)
    } else {
        io.to(message.to).emit(channel, data)
    }
})

io.on('connection', function (socket) {
    // set room for socket and add to userConnection
    socket.on('register', data => {
        var index = userConnection.findIndex(user => user.socketId === socket.id)

        // join socket to room if type is single chat else join it to group chat with name is hashtag campaign
        if (data.type) {
            socket.join(data.id)
        } else {
            socket.join('hashtag:' + data.id)
        }

        if (index == -1) {
            userConnection.push({ id: data.id, socketId: socket.id })
        }
    })

    socket.on('disconnect', function() {
        var index = userConnection.findIndex(user => user.socketId === socket.id)

        if (index) {
            userConnection.splice(index, 1)
        }
    })
})
