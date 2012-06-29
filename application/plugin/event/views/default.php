<div class="page-header well" style="-moz-box-shadow: 5px 5px 5px #888;
-webkit-box-shadow: 5px 5px 5px #888;
box-shadow: 5px 5px 5px #888;">
	<h1><?php echo $title; ?></h1>
</div>
<div class="well">
<?php 
foreach ($event AS $k => $data):
echo '<p>' . clean($data->descri, 'str'). '<br>' . getRelativeTime($data->time) . '</p>';
endforeach;
?>
</div>