/**
 * Created by Gerrit on 27/10/2020.
 */
var net = require('net');
var sockets = [];
var port = 9100;
var socketNr = 0;
var activeprinter = false;

var server = net.createServer(function(socket) {
    // Increment
    socketNr++;

    socket.name = "Socket " + socketNr;
    var clientName = socket.name;

    sockets.push(socket);
    socket.setTimeout(3000);
    // Log it to the server output
    console.log(clientName + ' joined the connection pool');
    console.log('there are: '+sockets.length+' connections');
    socket.write("\x1b"+"v"+"\n");

    // When client sends data
    socket.on('data', function(data) {
        var message =  data
        console.log("\n DATA length: " + data.length);
        if(data.length===1) {
            console.log("\n SET ACTIVE PRINTER TRUE: " + JSON.stringify(data));
            activeprinter = true;
        }
        console.log("\n DATA: " + JSON.stringify(data));
        if(activeprinter){
            console.log("\n BROADCAST: ");
            broadcast(clientName, message);
        }else{
            console.log("\n NO BROADCAST: ");
        }

    });

    socket.on('timeout', () => {
        console.log('socket timeout');
        socket.write("\x00");
    });

    // When client leaves
    socket.on('end', function() {
        // Remove client from socket array
        removeSocket(socket);
    });


    // When socket gets errors
    socket.on('error', function(error) {
        console.log('Socket got problems: ', error.message);
        removeSocket(socket);
    });


});


// Broadcast to others, excluding the sender
function broadcast(from, message) {

    // If there are no sockets, then don't broadcast any messages
    if (sockets.length === 0) {
        process.stdout.write('Everyone left the chat');
        return;
    }

    // If there are clients remaining then broadcast message
    sockets.forEach(function(socket, index, array){
        // Dont send any messages to the sender
        if(socket.name === from) return;

        socket.write(message);

    });

};

// Remove disconnected client from sockets array
function removeSocket(socket) {
    console.log(socket.name + " ended the connection");

    sockets.splice(sockets.indexOf(socket), 1);
    console.log('there are: '+sockets.length+' connections');
};


// Listening for any problems with the server
server.on('error', function(error) {

    console.log("So we got problems!", error.message);

});

// Listen for a port to telnet to
// then in the terminal just run 'telnet localhost [port]'
server.listen(port, function() {

    console.log("Server listening at http://localhost:" + port);

});