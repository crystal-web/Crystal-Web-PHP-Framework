<?php $session = Session::getInstance(); ?>
<div class="widget">
<div class="widget-content">
<?php echo clean($cgu, 'html'); ?>

<p align="center">
<a href="?token=<?php echo $session->getToken(); ?>" class="btn">
<?php echo i18n::get('I have read and agree to the Terms of Use General'); ?>
</a>
</p>

</div>
</div>

