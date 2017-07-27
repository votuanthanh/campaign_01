var app = require('express')()
var server = require('http').Server(app)
var io = require('socket.io')(server)
var redis = require('redis')

server.listen(8890)
var userConnection = []
var userLogin = null
var listFriend = []

var redisClient = redis.createClient(6379, 'redis')
redisClient.subscribe('singleChat', 'groupChat', 'activies')
redisClient.on('message', function(channel, data) {

    if (channel == 'activies') {
        data = JSON.parse(data)

        if (data.status && findIndexById(listFriend, data.userId) == -1) {
            userLogin = data.userId
            listFriend.push({ id: data.userId, listFriend: data.listFollow })
        } else {
            callOffline(io.sockets, listFriend, data.userId)
        }
    } else {
        var message = JSON.parse(data)

        if (parseInt(message.to)) {
            io.sockets.in(message.to).in(message.from).emit(channel, data)
        } else {
            io.to(message.to).emit(channel, data)
        }
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

        callOnline(socket, listFriend, userLogin)
    })

    socket.on('disconnect', function() {
        var index = userConnection.findIndex(user => user.socketId === socket.id)

        if (index != -1) {
            var userId = userConnection[index].id
            userConnection.splice(index, 1)

            if (userConnection.findIndex(user => user.id === userId) == -1) {
                callOffline(socket, listFriend, userId)
            }
        }
    })
})

function callOnline (socket, listFriend, userLogin)
{
    let index = findIndexById(listFriend, userLogin)

    if (index != -1) {
        for (var i = 0; i < listFriend[index].listFriend.length; i++) {
            socket.in(listFriend[index].listFriend[i]).emit('online', { type: true, id: userLogin, status: true })
        }

        let listFriendOnline = []

        for (var i = 0; i < listFriend[index].listFriend.length; i++) {
            let indexUserOnline = findIndexById(userConnection, listFriend[index].listFriend[i])

            if (indexUserOnline != -1) {
                listFriendOnline.push({ id: userConnection[indexUserOnline].id, status: true })
            }
        }

        socket.emit('online', { type: false, listOnline: listFriendOnline })
    }
}

function callOffline (socket, listFriend, id) {
    let index = findIndexById(listFriend, id)

    if (index != -1) {
        for (var i = 0; i < listFriend[index].listFriend.length; i++) {
            socket.in(listFriend[index].listFriend[i]).emit('online', { type: true, id: listFriend[index].id, status: false })
        }

        listFriend.splice(index, 1)
    }

    return true
}

function findIndexById (array, id) {
    return array.findIndex(arr => arr.id == id)
}
