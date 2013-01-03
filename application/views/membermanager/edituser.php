<?php 
require 'tabs.inc.php';
$form = Form::getInstance();
$acl = AccessControlList::getInstance();
?>

<form method="post" enctype="multipart/form-data">
	<div class="widget">
		<div class="widget-header">
			<h3>Avatar</h3>
		</div>
		
<?php
$styleBan = ($profilInfo->hasban == 'y') ? ' style="border: red 1px solid;"' : NULL;
	echo '<div class="widget-content"'.$styleBan.'>';
$t = NULL;
	if (!empty($profilInfo->sex)) 
	{
		$c = array('x' => '#ADD8E6', 'y' => '#FFC0CB', 'z' => '#000');
		$t = 'border: 1px solid '.$c[$profilInfo->sex] . ';';
	}
		
	$ava = '<div style="'.$t.'width: 120px;height: 120px;background-color: white;text-align: center;margin: auto;">';			
	
	if (isSet($profilInfo->avatar))
	{
		if ($profilInfo->avatar == '0')
		{
			$ava .= '<img src="' . get_gravatar($profilInfo->mailmember) . '" alt="' . clean($profilInfo->loginmember, 'slug') . '"  width="120" height="120">';
		}
		else
		{
			$ava .= '<img src="' . __CW_PATH . '/media/avatar/' . $profilInfo->avatar . '?r='.time().'" alt="' . clean($profilInfo->loginmember, 'slug') . '"  width="120" height="120">';
		}
	}
	else 
	{
		$ava .= '<img src="' . get_gravatar($profilInfo->mailmember) . '" alt="' . clean($profilInfo->loginmember, 'slug') . '" width="80" height="80">';
	}
	
	$ava .= '</div>';
	echo $form->input('avatar', $ava, array('type' => 'file'));
	echo $form->input('delavatar', 'Supprimer:', array('type' => 'select', 'option' => array('yes' => 'oui', 'no' => 'Non'), 'value' => 'no' ) );
?>
		</div>
	</div>	
	
	
		
	<div class="widget">
		<div class="widget-header">
			<h3>Informations de connexion</h3>
		</div>
		<div class="widget-content">
	<?php
		
	echo 
		$form->input('loginmember', 'Pseudo:', array('value' => $profilInfo->loginmember) ) . 
		$form->input('passmember1', 'Mot de passe:', array('type' => 'password') ) . 
		$form->input('passmember2', 'Confirmer le mot de passe:', array('type' => 'password') ) . 
		$form->input('mailmember', 'Mail:', array('value' => $profilInfo->mailmember) )
		;
		if ( isSet($groupList) )
		{
			if (is_array($groupList))
			{
			echo 
				$form->input('groupmember', 'Groupe:', array(
							'type' => 'select',
							'option' => $groupList,
							'value' => $profilInfo->groupmember
						)
					)
				;
			}
		}
	echo  
		$form->input('validemember', 'Approbation:', array(
				'type' => 'select',
				'option' => array(
					'on' => 'Oui',
					'off' => 'Non'
				),
				'value' => $profilInfo->validemember
			)
		)
		;
	
	if ($acl->isAllowed('membermanager', 'banned'))
	{
		if ( isSet($groupList[$profilInfo->groupmember]) )
		{
			if ($profilInfo->hasban == 'n' && $profilInfo->warning < 100)
			{
		  	echo
				$form->input('warning', 'Avertissement:', array(
						'type' => 'select',
						'option' => array(
							'0' => '0%',
							'10' => '10%',
							'20' => '20%',
							'30' => '30%',
							'40' => '40%',
							'50' => '50%',
							'60' => '60%',
							'70' => '70%',
							'80' => '80%',
							'90' => '90%',
							'100' => '100%',
							),
						'value' => $profilInfo->warning
					)
				)
				;
			}


	echo		
		$form->input('hasban', 'Bannissement:', array(
				'type' => 'select',
				'option' => array('y' => 'Oui', 'n' => 'Non'),
				'value' => $profilInfo->hasban
			)
		)
		;
		} // END is parent group
	}
	?>
		</div>
	</div>
	
	
	<div class="widget">
		<div class="widget-header">
			<h3>Informations du membre</h3>
		</div>
		<div class="widget-content">
	<?php
	
	echo 
		$form->input('sex', 'Sexe:', 
			array(
				'type' => 'select',
				'option' => array(
					'z' => 'Inconnu',
					'x' => 'Masculin',
					'y' => 'Féminin'
				),
				'value' => $profilInfo->sex)
			).
	    $form->input('job', 'Mon travail:', array('placeholder' => 'Plombier, y a pas de saut métier', 'value' => clean($profilInfo->job, 'str'))).
	    $form->input('leisure', 'Mes passions:', array('placeholder' => 'La guitare, le saut à la perche et le vélo', 'value' => clean($profilInfo->leisure, 'str'))).
	    $form->input('website', 'Mon site internet:', array('placeholder' => 'http://www.monsiteetblog.com', 'value' => clean($profilInfo->website, 'str'))).
	    $form->input('location', 'Ma localisation:', array('placeholder' => 'France, Pyrénées', 'value' => clean($profilInfo->location, 'str'))).
	    $form->input('birthday', 'Ma date de naissance:', array(
                'value' => date('n-j-Y', $profilInfo->birthday),
    			'type'=> 'date')) .
		$form->input('sign', 'Signature:', array('type' => 'textarea', 'editor' => '', 'value' => clean($profilInfo->sign, 'str') ) ) .
		$form->input('bio', 'Biographie:', array('type' => 'textarea', 'editor' => '', 'value' => clean($profilInfo->bio, 'str') ) ) . 
		$form->input('submit', 'Modifier', array('type' => 'submit', 'class' => 'btn success') );


	?>
		</div>
	</div>
</form>
