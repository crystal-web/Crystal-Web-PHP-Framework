<form action="<?php echo Router::url('auth/forgotpassword'); ?>" method="post">
<div class="widget">
	<div class="widget-content">
		<div class="clearfix">
			<label for="web"><?php echo i18n::get('Your email address'); ?>: </label>
			<div class="input">
				<div class="input-prepend">
					<span class="add-on">@</span>
					<input type="text"name="mailmember" placeholder="moi@domain.com">
				</div>
			</div>
		</div>
							
		<div class="clearfix">
			<div class="input">
				<input type="submit" name="submit" class="btn success">
			</div>
		</div>
	</div>
</div>
</form>