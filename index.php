<!DOCTYPE html>
<html>
<head>
	<title>Web Chat</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="/socket.io/socket.io.js"></script>
</head>
<style>
	body{
		margin: 30px;
		background-color: grey;
		color: black;
		text-align: center;
		font-family: "Arial";
	}
	#messageArea{
		display: none;
	}
	.form-control {
	 	display: block;
	 	margin-right: auto;
	 	margin-left: auto;
	}
	input[type=text]:focus {
    	border: 3px solid #555;
    	background-color: black;
    	color: white;
	}
	.chat {
	border: none;
	padding: 5px;
	width: 750px;
	height: 500px;
	overflow: scroll;
	}
</style>

<script>
scrollToBottom = function scrollToBottom (duration) {
  var chat = $(".chat");
  var scrollHeight = chat.prop("scrollHeight");
  chat.stop().animate({scrollTop: scrollHeight}, duration || 0);
};
</script>
<body>
		<div class="container">
			<div id="userFormArea" class="row">
				<div class="col-md-12">
					<form id="userForm">
							<div class="form-group">
								<label>Username:</label>
								<input type="text" class="form-control" name="name" id="username" style="width: 300px" required/>
								<br>
								<label>Password:</label>
								<input type="password" class="form-control" name="password" id="password" style="width: 300px" required/>
								<br>
								<input type="submit" class="btn btn-primary" value="Login">
				</div>
					</form>
				</div>
			</div>
				<div id="messageArea" class="row">
					<div class="col-md-4">
							<div class="well">
									<h4>Online Users</h4>
									<ul class="list-group" id="users"></ul>
							</div>

					<div id="google_translate_element"></div><script type="text/javascript">
                        function googleTranslateElementInit() {
                          new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ar,ca,el,en,es,fr,ga,nl,pt,ru,ur,zh-CN', layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL}, 'google_translate_element');
                        }
       				 </script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					</div>
					
						<div class="col-md-8">
						<form id="messageForm">
							<div class="form-group">
								<label>Enter Message:</label>
								<input type="text" class="form-control" name="msg" id="message" required></input>
								<br>
								<input type="submit" class="btn btn-primary" value="Send Message">
						</div>
						</form>

						<div class="chat" id="chat"></div>
						
				</div>
		</div>
		<br>
		<br>
		

		<script>
			$(function(){
				var socket = io.connect();
				var $messageForm = $('#messageForm');
				var $message = $('#message');
				var $chat = $('#chat');
				var $messageArea = $('#messageArea');
				var $userFormArea = $('#userFormArea');
				var $userForm = $('#userForm');
				var $users = $('#users');
				var $username = $('#username');


				$messageForm.submit(function(e){
					e.preventDefault();
					socket.emit('send message', $message.val());
					$message.val('');
				});


				function displayMsg(data){
					$chat.append('<div class="well"><strong>'+data.user+'</strong>: '+data.msg+'</div>');
				};


				socket.on('load old messages', function(docs){
					for(var i=docs.length-1; i >= 0; i--){
						displayMsg(docs[i]);
					}
				});

				socket.on('new message', function(data){
					displayMsg(data);
				});

				$userForm.submit(function(e){
					e.preventDefault();
					socket.emit('new user', $username.val(), function(data){
							if(data){
								$userFormArea.hide();
								$messageArea.show();
							}
					});
					$username.val('');
				});

				socket.on('get users', function(data){
					var html = '';
					for(i = 0;i < data.length;i++){
						html += '<li class="list-group-item">'+data[i]+'</li>';
					}
					$users.html(html);
				});



			});
		</script>
</body>
</html>