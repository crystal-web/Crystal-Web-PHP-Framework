<form method="post">

<ul class="tabs">
	<li class="active"><a href="#default">Information sur le site</a></li>
	<li><a href="#confst">Information de contact</a></li>
</ul>


<div class="pill-content">


	<div id="confst">
		<div class="widget">
			<div class="widget-header">
				<h3>Information de contact</h3>
			</div>
			<div class="widget-content">
		<?php
		$form = Form::getInstance();
		echo 
			$form->input('mailSite', 'E-mail du site:', array(
				'placeholder' => 'noreply@site.url',
				'value' => clean($config->mailSite, 'str')
				)) . 		
			$form->input('mailContact', 'E-mail de correspondance:', array(
				'placeholder' => 'contact@site.url',
				'value' => clean($config->mailContact, 'str')
				));
		?>
			</div>
		</div>
	</div>

	<div class="active" id="default">
		<div class="widget">
			<div class="widget-header">
				<h3>Information sur le site</h3>
			</div>
			<div class="widget-content">
		<?php
		echo 
			$form->input('siteName', 'Titre:', array(
				'placeholder' => 'Crystal-Web',
				'value' => clean($config->siteName, 'str')
				)) . 		
			$form->input('siteSlogan', 'Slogan:', array(
				'placeholder' => 'Et si notre partage faisait l\'&eacute;volution ?',
				'value' => clean($config->siteSlogan, 'str')
				)) . 
			$form->input('siteUrl', 'Url du site:', array(
				'placeholder' => __CW_PATH,
				'value' => clean($config->siteUrl, 'str')
				)) . 
			$form->input('siteTeamName', 'Equipe:', array(
				'placeholder' => 'Team Summer Crystal',
				'value' => clean($config->siteTeamName, 'str')
				) ) . 
			$form->input('layout', 'Template:', array(
				'type' => 'select',
				'option' => $layoutList,
				'value' => clean($config->layout, 'str')
				));
		?>
			</div>
		</div>
	</div>

</div>
	<?php echo $form->input('submit', 'Enregistrer', array('type' => 'submit', 'class' => 'btn success')); ?>

</form>