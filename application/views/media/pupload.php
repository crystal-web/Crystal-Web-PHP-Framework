<script language="javascript">
$(window).bind('beforeunload', function(){
return 'ATTENTION\nSi un upload est en cours, il sera perdu !!';
});

</script>
<style type="text/css">#plupload {  font-family: Arial,Helvetica;  color: #AAA; }  #plupload #droparea {
	border: 4px dashed #ddd;
	height: 200px;
	text-align: center;
	font-size: 13px; }
	#plupload #droparea p {
	  margin: 0;
	  padding: 60px 0 0 0;
	  font-weight: bold;
	  font-size: 20px; }
	#plupload #droparea span {
	  display: block;
	  margin-bottom: 6px; }
	#plupload #droparea.hover {
	  border-color: #83b4d8; }  #plupload #browse {
	border: 1px solid #BBB;
	text-decoration: none;
	padding: 3px 8px;
	color: #464646;
	background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #ffffff), color-stop(100%, #f4f4f4));
	background-image: -webkit-linear-gradient(top, #ffffff, #f4f4f4);
	background-image: -moz-linear-gradient(top, #ffffff, #f4f4f4);
	background-image: -o-linear-gradient(top, #ffffff, #f4f4f4);
	background-image: -ms-linear-gradient(top, #ffffff, #f4f4f4);
	background-image: linear-gradient(top, #ffffff, #f4f4f4);
	-moz-border-radius: 15px;
	-webkit-border-radius: 15px;
	-o-border-radius: 15px;
	-ms-border-radius: 15px;
	-khtml-border-radius: 15px;
	border-radius: 15px; }  #plupload #filelist {
	margin-top: 10px; }
	#plupload #filelist .file {
	  padding: 0 10px;
	  border: 1px solid #DFDFDF;
	  height: 70px;
	  line-height: 70px;
	  margin-bottom: 10px;
	  position: relative; }
	#plupload #filelist img {
	  margin-right: 10px;
	  max-height: 55px;
	  vertical-align: middle; }
	#plupload #filelist .actions {
	  position: absolute;
	  top: 0px;
	  right: 5px; }
	#plupload #filelist .del {
	  color: #FF0000; }
	#plupload #filelist .progressbar {
	  position: absolute;
	  top: 25px;
	  right: 5px;
	  width: 150px;
	  height: 20px;
	  background-color: #abb2bc;
	  -moz-border-radius: 25px;
	  -webkit-border-radius: 25px;
	  -o-border-radius: 25px;
	  -ms-border-radius: 25px;
	  -khtml-border-radius: 25px;
	  border-radius: 25px;
	  -moz-box-shadow: inset 0px 1px 2px 0px rgba(0, 0, 0, 0.5);
	  -webkit-box-shadow: inset 0px 1px 2px 0px rgba(0, 0, 0, 0.5);
	  -o-box-shadow: inset 0px 1px 2px 0px rgba(0, 0, 0, 0.5);
	  box-shadow: inset 0px 1px 2px 0px rgba(0, 0, 0, 0.5); }
	#plupload #filelist .progress {
	  position: absolute;
	  border: 1px solid #4c8932;
	  height: 18px;
	  width: 10%;
	  background: url(progress.jpg) repeat;
	  -webkit-animation: progress 2s linear infinite;
	  -moz-border-radius: 25px;
	  -webkit-border-radius: 25px;
	  -o-border-radius: 25px;
	  -ms-border-radius: 25px;
	  -khtml-border-radius: 25px;
	  border-radius: 25px; }@-webkit-keyframes progress {  from {
	background-position: 0 0; }  to {
	background-position: 54px 0; } }audio { width: 200px;}</style><div id="plupload">
	<div id="droparea">
	
	<p>Déposez vos fichiers ici</p><span class="or">ou</span>
	
	<a href="#" id="browse">Parcourir</a>
	</div>

	

	<div id="filelist"><?php foreach($listFiles as $k => $v): ?><form><div class="clearfix"><label>
	<?php
	switch ($v->type)
	{
	case 'image':
		list($v->width, $v->height, $v->type, $v->attr) = getimagesize(__CW_PATH . '/media/' . $v->mime . '/' . $v->name);
		if (preg_match('#gif#', $v->name))
		{
		?>
		<a href="<?php echo  __CW_PATH . '/media/' . $v->mime . '/' . $v->name; ?>" onclick="window.open(this.href, 'pop<?php echo $v->id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $v->width;?>, height=<?php echo $v->height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media/' . $v->mime . '/' . $v->name; ?>" alt=""></a>
		<?php
		}
		elseif (preg_match('#(png|jpe?g)#', $v->name))
		{
		?>
		<a href="<?php echo  __CW_PATH . '/media/' . $v->mime . '/' . $v->name; ?>" onclick="window.open(this.href, 'pop<?php echo $v->id;?>','menubar=no, status=no, scrollbars=yes, width=<?php echo $v->width;?>, height=<?php echo $v->height;?>');return false;"><img src="<?php echo  __CW_PATH . '/media.php?thumb=/'.$v->mime.'/' . $v->name; ?>" alt=""></a>
		<?php
		}
		else
		{
		echo 'no matching ext';
		}
	break;
	case 'audio':
		echo '<img src="' . __CW_PATH . '/media/audio.png" alt="" /> <audio controls="controls" controls="true" preload="true">  <source src="' . __CW_PATH . '/media/' . $v->mime. '/' . $v->name . '" type="'.$v->mime.'" />  Your browser does not support the audio tag.</audio>';
	break;
	}
	?>
	</label>
	<div class="input">
	<div class="input-prepend">
	<span class="add-on">URL: </span>	
	<input type="text" value="<?php echo __CW_PATH . '/media/' . $v->mime . '/' . $v->name; ?>" >	
	<a href="<?php echo $v->id; ?>" class="btn del">Supprimer</a>
	</div>
	</div></div></form><?php endforeach; ?>

	</div>

	</div>

	

	<script type="text/javascript" src="<?php echo __CDN; ?>/files/js/plupload/plupload.js"></script>

	<script type="text/javascript" src="<?php echo __CDN; ?>/files/js/plupload/plupload.flash.js"></script>

	<script type="text/javascript" src="<?php echo __CDN; ?>/files/js/plupload/plupload.html5.js"></script>

	<script type="text/javascript" src="<?php echo __CW_PATH; ?>/files/js/pupload.js"></script>