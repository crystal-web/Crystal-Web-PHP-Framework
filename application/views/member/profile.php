<?php 
$son = 'Son';
$sa = 'Sa';
$ses = 'Ses';
if ($this->mvc->Session->isLogged())
{
	if ($info->idmember == $this->mvc->Session->user('id'))
	{
	$son = 'Mon';
	$sa = 'Ma';
	$ses = 'Mes';
	}
}
?>
<ul class="tabs">
  <li class="active"><a href="#default">Profil</a></li>
  <li><a href="#wall"><?php echo $son; ?> actu</a></li>
  <?php
  if ($this->mvc->Session->isLogged()): ?>
  <li><a href="<?php echo Router::url('member/edit'); ?>">Editer mon profil</a></li>
  <?php endif; ?>
  <?php
  if ($this->mvc->Acl->isAllowed('member', 'editother')): ?>
  <li><a href="<?php echo Router::url('member/editother/id:' . $info->idmember); ?>">Editer le profil</a></li>
  <?php endif; ?>
  
<li><a href="<?php echo Router::url('member');; ?>">Membre au hasard</a></li>


</ul>

		<h3><a href="<?php echo Router::url('member/index/slug:' . $info->loginmember); ?>">Informations générales</a></h3>
		<div class="well">
			<div class="row">
				<div class="span3">
				<?php
				if (!empty($info->avatar)) {
				echo '<img src="'.__CW_PATH.'/media/avatar/'.$info->avatar.'" alt="' . $info->loginmember . '" width="120" height="120" >';			
				} else { 
				echo '<img src="'.get_gravatar($info->mailmember).'" alt="' . $info->loginmember . '" width="120" height="120" >'; }
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
					</ul>

				</div>
			</div>
		</div>

<div class="pill-content">

	<div class="active" id="default">

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
	
	
	
	<div id="wall">
	<?php 
	if ($this->mvc->Session->isLogged()):
		if ($this->mvc->Session->user('id') == $info->idmember):
			?>
			<form method="post">
				<textarea name="actu" style="margin-top: 0px;
margin-bottom: 0px;
height: 60px;
margin-left: 0px;
margin-right: 0px;
width: 688px;"></textarea>

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
	endif;?>
	</div>	
</div>




