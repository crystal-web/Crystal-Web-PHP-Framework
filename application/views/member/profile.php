<?php
require 'tabs.inc.php';
$son = 'Son';
$sa = 'Sa';
$ses = 'Ses';


if ($session->isLogged())
{
	if ($info->idmember == $session->user('id'))
	{
	$son = 'Mon';
	$sa = 'Ma';
	$ses = 'Mes';
	}
}
?>




<div class="pill-content">

	<div class="active" id="default">
		<h3><a href="<?php echo Router::url('member/index/slug:' . $info->loginmember); ?>">Informations générales</a></h3>
		<div class="well">
			<div class="row">
				
				<div class="span3">
				<?php
				if (!empty($info->avatar)) {
					echo '<img src="'.__CW_PATH.'/media/avatar/'.$info->avatar.'" alt="' . $info->loginmember . '" width="120" height="120" >';			
				} else { 
					echo '<img src="'.get_gravatar($info->mailmember).'" alt="' . $info->loginmember . '" width="120" height="120" >';
				}
				?>
				</div>
				
				<div class="span8">
					<ul>
						<li><strong>Pseudo:</strong> <?php echo $info->loginmember; 
						$sex = array('y' => ' (fille)', 'x' => ' (garçon)');
						echo ($info->sex == 'x' or $info->sex == 'y') ?  $sex[$info->sex] : '';
						?></li>
						<li><strong>Inscription:</strong>	<?php echo dates($info->firstactivitymember, 'fr_date'); ?></li>
						<li><strong>Dernière visite:</strong>	<?php echo getRelativeTime($info->lastactivitymember); ?></li>
						<li><strong>Grade:</strong>	<?php echo $info->groupmember; ?></li>
					</ul>
				</div>
				
			</div>
		</div>
		

		<h3>Profil</h3>
		<div class="well">
			<ul>
				<?php if ($info->birthday > 0):
function age($naiss)  {
  list($annee, $mois, $jour) = explode('-', $naiss);
  $today['mois'] = date('n');
  $today['jour'] = date('j');
  $today['annee'] = date('Y');
  $annees = $today['annee'] - $annee;
  if ($today['mois'] <= $mois) {
    if ($mois == $today['mois']) {
      if ($jour > $today['jour'])
        $annees--;
      }
    else
      $annees--;
    }
  return $annees;
  }
				
				?>
				<li><strong><?php echo $sa; ?> date de naissance:</strong> <?php echo dates($info->birthday, 'fr_date'); ?></li>
				<li><strong><?php echo $son; ?> age:</strong> <?php echo age(date('Y-m-d', $info->birthday)) .' ans'; ?></li>
				<?php endif; ?>
				
				<li><strong><?php echo $son; ?> travail:</strong> <?php echo (!empty($info->job)) ? clean($info->job, 'str') : 'Inconnu';?></li>
				<li><strong><?php echo $ses; ?> passions:</strong> <?php echo (!empty($info->leisure)) ? clean($info->leisure, 'str') : 'Inconnu';?></li>
				<li><strong><?php echo $son; ?> site internet:</strong> <?php echo (!empty($info->website)) ? '<a href="' . clean($info->website, 'str') . '">' . clean($info->website, 'str') . '</a>' : 'Inconnu';?></li>
				<li><strong><?php echo $sa; ?> localisation:</strong> <?php echo (!empty($info->location)) ? clean($info->location, 'str') : 'Inconnu';?></li>
			</ul>
		</div>

		<h3>Signature</h3>
		<div class="well">
		<?php echo (!empty($info->sign)) ? clean($info->sign, 'bbcode') : 'Inconnu';?>
		</div>

		<h3>Biographie</h3>
		<div class="well">
		<?php echo (!empty($info->bio)) ? clean($info->bio, 'bbcode') : 'Inconnu';?>
		</div>
</div>
<?php if (count($multi) > 1): ?>
<div id="multi">
    <h3>Multi-compte</h3>
    <div class="well">

            <table class="zebra-striped bordered-table condensed-table">
            <thead>
                <tr>            
                    <th>Pseudo</th>
                    <th>Enregistr&eacute;</th>
                    <th>Dernière connexion</th>
                    <th>Approbation</th>
                </tr>
            </thead>
            <tbody>
            
            <?php foreach($multi AS $k => $v): ?>
                <tr>
                    <td><a href="<?php echo Router::url('member/index/slug:' . $v->loginmember); ?>"><?php echo $v->loginmember; ?></a></td>
                    <td><?php echo dates($v->firstactivitymember, 'fr_date'); ?></td>
                    <td><?php echo dates($v->lastactivitymember, 'fr_date'); ?></td>
                    <td><?php echo $v->validemember; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>

    </div>
</div>
<?php else: ?>
<div id="multi">
    <h3>Multi-compte</h3>
    <div class="well">
		Pas de multicompte
    </div>
</div>
<?php endif; ?>

	
	
	
	
<div id="wall">
	<h3>Fil d'actualit&eacute;</h3>
	<?php 
	if ($session->isLogged()):
		if ($session->user('id') == $info->idmember):
			?>
			<form method="post">
				<textarea name="actu" style="margin-top: 0px;margin-bottom: 0px;height: 60px;margin-left: 0px;margin-right: 0px;width: 688px;"></textarea>
				
				<div class="actions"><input type="submit" id="inputsubmit" value="Poster mon actu" class="btn primary"></div>
			</form>
			<?php
		endif;	
	endif;
	?>
	<?php if (count($actu) > 0):
	foreach($actu AS $k => $v): ?>
	
	<div class="well"><?php echo clean($v->actu, 'str'); ?><br>
	<small class="right">Par <a href="<?php echo Router::url('member/index/slug:' . $v->loginmember); ?>"><?php echo $v->loginmember; ?></a>, <?php echo getRelativeTime($v->time); ?></small></div>
	
	<?php endforeach;
	else:
		echo '<div class="well">Pas d\'actualit&eacute;</div>';
	endif;?>
</div>



	
<?php if($session->isLogged()): ?>
	<div id="edit">
		<h3>Editer mon profil</h3>
		<div class="well">

<form method="post" enctype="multipart/form-data">
<h3>Avatar</h3>
<div class="well">
<?php
//


$t = NULL;
	if (!empty($infouser->sex))
	{
		$c = array('x' => '#ADD8E6', 'y' => '#FFC0CB', 'z' => '#000');
		$t = 'border: 1px solid '.$c[$infouser->sex] . ';';
	}
		
$ava = '<div style="'.$t.'width: 120;height: 120;background-color: white;text-align: center;margin: auto;">';			

if (isSet($infouser->avatar))
{
	if ( $infouser->avatar == '0' OR empty($infouser->avatar) )
	{
		$ava .= '<img src="' . get_gravatar($infouser->mailmember) . '" alt="' . clean($infouser->loginmember, 'slug') . '" width="120" height="120">';
	}
	else
	{
		$ava .= '<img src="' . __CW_PATH . '/media/avatar/' . $infouser->avatar . '?r='.time().'" alt="' . clean($infouser->loginmember, 'slug') . '" width="120" height="120">';
	}
}
else
{
		$ava .= '<img src="' . get_gravatar($infouser->mailmember) . '" alt="' . clean($infouser->loginmember, 'slug') . '" width="120" height="120">';
}
$ava .= '</div>';
	echo $form->input('avatar', $ava, array('type' => 'file')); ?>
</div>


<h3>Profil</h3>
<div class="well">
    <?php
    echo $meform;
    ?>
</div>
<h3>Signature</h3>
<div class="well">
<?php
$sign = (isSet($infouser->sign)) ? $infouser->sign : '';
echo $form->input('sign', 'Votre signature:', array('type' => 'textarea', 'editor' => '', 'value' => clean($sign, 'str'))); ?>
</div>
<h3>Biographie</h3>
<div class="well">
<?php
$bio = (isset($infouser->bio)) ? $infouser->bio : '';
echo $form->input('bio', 'Votre bio:', array('type' => 'textarea', 'editor' => '', 'value' => clean($bio, 'str'))); ?>
</div>
<?php
echo $form->input('submit', 'Enregistrer', array('type' => 'submit', 'class' => 'btn success')); ?>
</form>

		</div>
	</div>
<?php endif; ?>	
</div>




