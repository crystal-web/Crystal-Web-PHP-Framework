<?php loadFunction('bbcode'); ?>

<h5><?php echo $message->motif; ?></h5>
<div class="dateContact"><?php echo dates($message->time, 'fr_datetime'); ?></div>
<div class="message">
<?php echo bbcode($message->message); ?>
</div>
<div class="infomessage">
<span class="infoContact"><?php echo $message->firstname . ' ' . $message->lastname; ?></span>
<span class="ipContact"><?php echo $message->ip; ?></span>

</div>