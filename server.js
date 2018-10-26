var socket  = require( 'socket.io' );
var express = require('express');
var app     = express();
var server  = require('http').createServer(app);
var io      = socket.listen( server );
var port    = process.env.PORT || 3000;

server.listen(port, function () {
  console.log('Server listening at port %d', port);
});


io.on('connection', function (socket) {

  
  socket.on( 'new_notifikasi', function( data ) {
    io.sockets.emit( 'new_notifikasi', { 
      deskripsi: data.deskripsi,
      waktu: data.waktu,
      link: data.link,
      dari: data.dari,
      target : data.target
    });
  });
 

  socket.on( 'update_count_message', function( data ) {
    io.sockets.emit( 'update_count_message', {
    	update_count_message: data.update_count_message ,
      receiver_id : data.receiver_id
    });
  });

  socket.on( 'update_count_notifikasi', function( data ) {
    io.sockets.emit( 'update_count_notifikasi', {
      update_count_notifikasi: data.update_count_notifikasi,
      target : data.target
    });
  });

  socket.on( 'update_count_messages', function( data ) {
    io.sockets.emit( 'update_count_messages', {
      update_count_messages: data.update_count_messages,
      receiver_id : data.receiver_id,
      sender_id : data.sender_id
    });
  });



  socket.on( 'new_message', function( data ) {
    io.sockets.emit( 'new_message', {
      sender_id : data.sender_id,
    	receiver_id: data.receiver_id,
    	message: data.message,
    	time: data.time,
    	file: data.file,
    	message_id: data.message_id
    });
  });

  
});
