<?php 
$config = Config::getInstance();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<style>
body{font-family:"Lucida Sans","Lucida Grande","Lucida Sans Unicode","Luxi Sans",sans-serif;background-color:#ddd;margin:0;padding:0}#main{width:640px;margin:auto;padding:15px 0 15px 0}#header{-webkit-border-top-right-radius:5px;-moz-border-radius-topright:5px;border-top-right-radius:5px;height:70px;background:#f2f2f2 url(<?php echo __CW_PATH . '/files/mail/';?>'header.png')}#content{padding:15px;margin:0;background-color:#fff}#footer{padding:15px;height:60px;margin:0;background-color:#f2f2f2;-webkit-border-bottom-left-radius:5px;-moz-border-radius-bottomleft:5px;border-bottom-left-radius:5px}.border{border-width:0 1px;border-left-style:solid;border-left-color:#CCC;border-right-style:solid;border-right-color:#CCC}@font-face{font-family:'Walter Turncoat';font-style:normal;font-weight:normal;src:local('Walter Turncoat'),local('WalterTurncoat'),url('http://themes.googleusercontent.com/static/fonts/walterturncoat/v3/sG9su5g4GXy1KP73cU3hvf-Xg5uhy57aq-Akr_1zBcg.woff') format('woff')}h1,h2,h3,h4,h5,h6{font-family:'Walter Turncoat';margin:5px;padding:5px 5px 5px 0}p{font-size:.8em;margin:5px}.message{margin:15px;font-ize:1.1em}#header h4{color:#3f3f3f}#footer .follow{text-align:left;position:relative;width:49%;float:left;height:60px}#footer .follow img{float:left;position:absolute;padding-top:5px;width:32px;margin-bottom:auto}.flippthis{clear:both}.flippthis{display:block;-webkit-transition:.2s all linear;-moz-transition:.2s all linear;-ms-transition:.2s all linear;-o-transition:.2s all linear;transition:.2s all linear;-webkit-transition:.2s all linear;-moz-transition:.2s all linear;-ms-transition:.2s all linear;-o-transition:.2s all linear;transition:.2s all linear}.flippthis:hover{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg);-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg)}#footer .subscrib{text-align:right;position:relative;width:49%;margin-left:10px;float:left;height:60px}#footer .subscrib ul{list-style:none}#footer .subscrib li{font-size:.7em}a:link,a:visited{text-decoration:none;color:black;background-color:transparent;border-bottom:1px dotted #000}a:hover,a:active{text-decoration:underline;color:black;background-color:transparent;border-bottom:0}a:link img,a:visited img{text-decoration:none;background-color:transparent}a:hover img,a:active img{background-color:transparent;border-bottom:0}
		</style>
	</head>
	<body bgcolor="#FFFFFF" text="#000000">
		<div id="main">
			<div id="header" class="border">
				<div style="position: relative; left: 15px;">
					<!--<img src="http://lorempixel.com/50/90" style="position: absolute;">-->
				</div>
				<div style="position: relative; left: 85px;">
					<h4><?php echo $this->subject; ?></h4>
				</div>
				
			</div>
			<div id="content" class="border">
				<div  class="message">
				<?php echo clean($this->message, 'bbcode'); ?>
				</div>
			<p style="margin-left:25px;"><?php echo i18n::get('Sincerely, our best regards.'); ?>,<br>
			<span style="margin-left:25px;"><?php echo $config->getSiteTeam(); ?></span></p>
			
			<hr>
			<?php echo i18n::get('In case of abuse or use by a third party, do not hesitate to let us know').'<br>IP: '.Securite::ipX().'<br>Date: '.date(i18n::get('_date_format')); ?>
			</div>
		</div>
	</body>
</html>
