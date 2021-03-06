<?php
// http://javascript-html5-tutorial.com/

// get current article ID from DB
$article_id = 48;

// get comments added to article...
$history = array();

// get host and port from config...
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Socket.IO Tutorial</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.2/socket.io.min.js"></script>

        <style>
            ul li {
                list-style-type: none;
                border: solid 1px;
                margin: 4px; 
                padding: 4px;
            }

            input {
                width: 200px;
            }

            textarea {
                width: 400px;
                height: 100px;
            }

        </style>
    </head>
    <body>

        <h2>Welcome to our comment system</h2>

        <p>Write something... </p>

        <ul id="messages">
        </ul>

        <br>
        Your name:
        <p><input type="text" id="nick"/></p>

        Message:
        <p><textarea id="comment"></textarea></p>

        <p><button id="send">Send</button></p>

        <script>
            var socket = io.connect('//localhost:1350', {reconnection: false});

            // this value can be generated by e.g. PHP
            // var article_id = <?php echo $article_id ?>;
            var article_id = 48;
            
            // say hello to the server
            socket.emit("new_conn", {
                mydata: {msg: "New connection established!"}
            });

            // process
            $("#send").click(function () {
                var nick = $('#nick').val();
                var comment = $('#comment').val();

                if (nick.length === 0 || comment.length === 0) {
                    alert('Fields cannot be empty');

                    return false;
                }

                socket.emit("send", {
                    c_data: {
                        nick: nick,
                        comment: comment,
                        article_id: article_id
                    }
                });
            });

            // handle data from server
            socket.on('add_comment', function (data) {
                // display ONLY for the current article 
                // if (data.comment_data.article_id !== article_id) { return false; }
                
                var content = '<li>Comment ID: ' + data.comment_data.comment_id;
                content += '<br> Author: ' + data.comment_data.nick;
                content += '<br> Time: ' + data.comment_data.time;
                content += '<p>' + data.comment_data.comment + '</p></li>';

                $('#messages').append(content);

                // emit more data ...
                // socket.emit("something", { data: 'New comment added' });
            });

            socket.on('error', function () {
                console.error(arguments);
            });

        </script>
    </body>
</html>
