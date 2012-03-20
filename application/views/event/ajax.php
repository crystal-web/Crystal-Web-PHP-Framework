<ul>
<?php foreach ($event AS $data): ?>
	<li><?php echo $data->descri; ?>
	<span class="time"><?php echo getRelativeTime($data->time); ?></span></li>
<?php endforeach; ?>
</ul>