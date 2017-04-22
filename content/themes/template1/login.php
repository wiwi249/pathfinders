<!doctype html>
<html>
<head>
{$headerinclude}
<title>{$title}</title>
<link rel="shortcut icon" href="{$CURTEMPLATE_PATH}images/favicon.png" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/style.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/main.css" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300italic,500,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="{$CURTEMPLATE_PATH}js/jqueryui/external/jquery/jquery.js"></script>
<script src="{$CURTEMPLATE_PATH}js/main.js"></script>
</head>
<body class="loginbody">
<div id="profilepicture"></div>
<div id="loginbox" class="col-4 col-xs-5 col-s-8">
	<div class="loginformbox">
		<form name="" method="post">
		<div>
			<input type="text" id="login" name="login" placeholder="Wpisz swój login..." autocomplete="off" autofocus required />
		</div>
		<div>
			<input type="password" id="password" name="password" placeholder="Podaj hasło..."  required/>
			<input type="text" name="fakepwd" class="fakepwd" placeholder="Podaj hasło..." />
			<div class="password-show-icon"></div>
		</div>
		<input type="submit" value="Zaloguj" />
		
		</form>
		<div class="forgotpassword">
			Zapomniałeś hasła?<br>Skontaktuj się z administratorem systemu.
		</div>
	</div>
</div>
<script src="{$CURTEMPLATE_PATH}js/notie.js"></script>
<script type="text/javascript">
	var isPwdVisible = false;
	var isProfileVisible = false;
	var profile = 'default';
	$('.password-show-icon').mouseup(function(){
			$('.fakepwd').hide();
			$(':password').show();
			$(this).removeClass('.pwd-clicked');
			isPwdVisible = false;
	});
			
	$('.password-show-icon').mousedown(function(){
			var password = $(":password").val();
			$('.fakepwd').val(password)
			$('.fakepwd').show();
			$(':password').hide();
			$(this).addClass('.pwd-clicked');
			isPwdVisible = true;
	});
	
	$('input[name="login"]').focusout( function() {
		var str = $(this).val();
		if (str.length == 0) {
			if(isProfileVisible) {
				$('#profilepicture').animate({
						opacity:0.0
				}, 500, function() {});
				isProfileVisible = false;
			}
		
        	return;
		} 
		else {
			$.post("{$siteurl}app/ajax.php", { func: "profilepicture", arg: str}, function(data) {
					if(data == '0') {
						$('#profilepicture').css('background-image', "url('http://localhost:81/pathfinders/public/uploads/userprofiles/invalid.png')");
						if(!isProfileVisible)
						$('#profilepicture').animate({
							opacity:1.0
						}, 500, function() {});
						notie.alert(3, "Niepoprawna nazwa użytkownika!", 4.0);
						$('#login').focus();
					} else {
						var http = new XMLHttpRequest();
					    http.open('HEAD', '{$siteurl}public/uploads/userprofiles/' + data + '.png', false);
					    http.send();
						if(http.status!=404) {
							profile = data;
						}
						else {
							profile = 'default';
						}
					
		                $('#profilepicture').css('background-image', "url('{$siteurl}public/uploads/userprofiles/" + profile + ".png')");
						if(!isProfileVisible) {
							$('#profilepicture').animate({
								opacity:1.0
							}, 500, function() {});
							isProfileVisible = true;
						}
					}
				});
		}
	});
	
	var ntf = "{$lgmsg}";
	if(ntf) {
		notie.alert(3, ntf, 4.0);
	}
	loadPage();
	</script>
<footer>
	Projekt i wdrożenie: <a href="mailto:wiwi249@gmail.com">Wiktor Zawierucha</a>
</footer>
</body>
</html>