<html>
<head>
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/main.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/style.css" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300italic,500,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
{$headerinclude}
<title>{$title}</title>
</head>
<body>
<div id="wrapper">
	<?php 
		get_template_element('sidebar');
	?>
	<div id="main">
		<header>
			{$header}
		</header>
		<div id="content">
			{$content}
		</div>
		
		
	</div>
</div>
<script src="{$CURTEMPLATE_PATH}js/notie.js"></script>
<script type="text/javascript">
	var ntf = "{$lgmsg}";
	var type = {$lgmsgtype};
	if(ntf) {
		notie.alert(type, ntf, 4.0);
	}
</script>
</body>
</html>