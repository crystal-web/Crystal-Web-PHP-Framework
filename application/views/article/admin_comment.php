<form action="<?php echo Router::url('article/admin_comment'); ?>" method="post">
<?php
echo $this->mvc->Form->input('valide', 'Afficher les ', array('options' => array('n' => 'En attente','y' => 'Approuvé', 's' => 'Indésirable'), 'onchange' => 'this.form.submit();')).'</form>';
loadFunction('bbcode');

	// Le nombre de commentaire
	if (count($getComm))
	{$nbcom = count($getComm);
	$plurial = ($nbcom > 1) ? 's' : '';
	echo '<h4 id="com">' . $nbcom . ' commentaire' . $plurial . '</h4>';
	echo '<ol style="padding: 0px;list-style: none;margin: 10px 0px 20px;">';


		foreach ($getComm as $data)
		{
		
	echo '<li>
		
		<img alt="" src="' . get_gravatar($data->mail) . '" style="float: left;
	border: 1px solid #ACA288;
	display: block;
	margin-right: 10px;
	margin-bottom: 5px;" height="32" width="32" />
		<p style="color: #B99316;
	margin-top: 10px;
	padding: 0px;
	float: left;">Commentaire de <span style="color:#000;font-weight:bold;">' . clean($data->pseudo, 'slug') . '</span> le ' . dates($data->Cdate, 'fr_date') . '</p>';
	?><span style="float:right;">
	<a href="#"  data-controls-modal="delpost" data-backdrop="true" data-keyboard="true" onclick="delPost('<?php echo Router::url('article/admin_comment/slug:s/id:'.$data->id); ?>?token=<?php echo $this->mvc->Session->getToken(); ?>');">
		<img src="<?php echo __CDN; ?>/files/images/icons/cross-white.png" alt="Ind&eacute;sirable" title="Ind&eacute;sirable">
	</a>
	<a href="<?php echo Router::url('article/admin_comment/slug:y/id:'.$data->id); ?>?token=<?php echo $this->mvc->Session->getToken(); ?>">
		<img src="<?php echo __CDN; ?>/files/images/icons/tick-white.png" alt="Approuv&eacute;" title="Approuv&eacute;">
	</a>
	<a href="<?php echo Router::url('article/admin_comment/slug:n/id:'.$data->id); ?>?token=<?php echo $this->mvc->Session->getToken(); ?>">
		<img src="<?php echo __CDN; ?>/files/images/icons/alarm-clock-select.png" alt="En attente" title="En attente">
	</a>
	</span>
	<?php
	echo '<div style="clear: both;
	width: 100%;
	color: #333;
	border-top: 1px dotted #B99316;
	padding-top: 10px;
	margin-bottom: 15px;">
		<p>' .clean($data->content, 'bbcode') . '</p>
		</div>
		
		</li>';

		}
	echo '</ol>';

	}
	else
	{
	echo '<h4>Pas de commentaire</h4>';
	}
?>

<script type="text/javascript">
function delPost(postHref)
{
document.getElementById("del").href = postHref;
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer le commentaire ?</p>');
}
</script>
<div id="delpost" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delpost').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>