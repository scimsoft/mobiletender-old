/**
 * Created by Gerrit on 04/11/2020.
 */

var net = require('net');
var printers = [];
var apps= [];
var printerport = 9100;
var appport = 9101;

var printerserver = net.createServer(function(printersocket) {
    printersocket.name = "Printer"
    printers.push(printersocket);
    console.log("Printer connected");

    printersocket.setTimeout(30000);
    // When client leaves
    printersocket.on('end', function() {
        // Remove client from socket array
        removeSocket(printers,printersocket);
    });


    // When socket gets errors
    printersocket.on('error', function(error) {
        console.log('printersocket got problems: ', error.message);
        removeSocket(printers,printersocket);
    });

    printersocket.on('timeout', () => {
        console.log('printersocket timeout');
        printersocket.write("\x00");
    });
    printersocket.on('data', function(data) {
        console.log('printerdata:', data);
    });


});

var appserver = net.createServer(function(appsocket) {
    appsocket.name = "App"
    apps.push(appsocket);
    console.log("App connected");


    appsocket.on('data', function(data) {
        var message = data;
        if(data.length !== 1){
            if (printers.length === 0) {
                appsocket.emit("error","No printers conected");
                appsocket.destroy();
                console.log("EMIT error to socket no printer conected");
            }else {
                printPedido(data);
            }
        }


    });
    // When client leaves
    appsocket.on('end', function() {
        // Remove client from socket array
        removeSocket(apps,appsocket);
    });


    // When socket gets errors
    appsocket.on('error', function(error) {
        console.log('appsocket got problems: ', error.message);
        removeSocket(apps,appsocket);
    });



});



// Remove disconnected client from sockets array
function removeSocket(arraydesockets,socket) {
    console.log(socket.name + " ended the connection");

    arraydesockets.splice(arraydesockets.indexOf(socket), 1);
    console.log('there are: '+arraydesockets.length+' connections');
};

// Broadcast to others, excluding the sender
function printPedido(message) {

    // If there are clients remaining then broadcast message
    printers.forEach(function(socket, index, array){
        // Dont send any messages to the sender

         socket.write(message);

    });

};



// Listen for a port to telnet to
// then in the terminal just run 'telnet localhost [port]'
printerserver.listen(printerport, function() {

    console.log("Printer Server listening at port:" + printerport);

});

appserver.listen(appport, function() {

    console.log("Server listening at port:" + appport);

});


