<html>
<head>
{$headerinclude}
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/main.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/style.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/notie.css" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>-->
<script src="{$CURTEMPLATE_PATH}js/jqueryui/external/jquery/jquery.js"></script>
<script src="{$CURTEMPLATE_PATH}js/jqueryui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/jquery-ui.structure.min.css" type="text/css" />
<link rel="stylesheet" href="{$CURTEMPLATE_PATH}styles/jquery-ui.theme.min.css" type="text/css" />
<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300italic,500,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<title>{$title}</title>
  <script>
  //NEED TO CHANGE THE LIB!!!
  $( function() {
    $( "#datepicker" ).datepicker({
	  dayNamesMin: [ "Nd", "Pn", "Wt", "Śr", "Czw", "Pt", "So" ],
	  monthNamesShort: ["Sty", "Lu", "Mar", "Kwi", "Maj", "Cze", "Li", "Sie", "Wrze", "Paź", "Lis", "Gru"],
      changeMonth: true,
      changeYear: true,
	  minDate: "-100y",
	  maxDate: 0,
	  yearRange: "1930:-10"
    });
  } );
  </script>
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
<script src="{$CURTEMPLATE_PATH}js/notie.min.js"></script>
<script type="text/javascript">
	var ntf = "{$lgmsg}";
	var type = {$lgmsgtype};
	if(ntf) {
		notie.alert(type, ntf, 4.0);
	}
</script>
</body>
</html>