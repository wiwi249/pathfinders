<div id="sidebar">
	<div class="sbar-content">
		<div class="sbar-profile">
			<div class="profilepicture" style="background-image:url('<?php echo get_profile_picture($_SESSION['id']) ?>');">
				<a href="{$siteurl}logout"></a>
				<span>Wylogować?</span>
			</div>
			<h2>Cześć {$imie}!</h2>
			[<a href="{$siteurl}profile/edit">Edytuj profil</a>]
		</div>
		<script type="text/javascript">
			$('.sbar-profile .profilepicture').hover(function() {
				$(this).animate({ backgroundSize: '130%' }, 200, "linear");
			}, function() {
				$(this).animate({ backgroundSize: '110%', }, 200, "linear");
			})
		</script>
		<?php loadMenu('main'); ?>
		
	</div>
	
	
	
	
	
	
</div>