<?php
$page = Page::getInstance();
$acl = AccessControlList::getInstance();
$session = Session::getInstance();
$form = Form::getInstance();

$page->setHeader(
"
<script type=\"text/javascript\">
function delFaq(postHref)
{
document.getElementById(\"del\").href = postHref;
$('#modalTitle').text('Alerte...');
$('#modalText').html('<p>Supprimer la question ?</p>');
}
</script>"
);

?>
<div id="delfaq" class="modal hide fade">
	<div class="modal-header">
		<a href="#" class="close">&times;</a>
		<h3 id="modalTitle"></h3>
	</div>
	
	<div class="modal-body" id="modalText"></div>
	<div class="modal-footer">
		<a href="#" class="btn" onclick="$('#delfaq').modal('hide');">NON</a>
		<a href="#" id="del" class="btn danger">OUI</a>
	</div>
</div>
<div id="faq">
<?php
$tok=null;/*
if ($acl->isAllowed('faq','manager')){
?>

<?php
$tok = $session->getToken();
}//*/
?>
<div class="accordion acc-home" id="accordion3">
<?php
	if (count($faq)):
		$c = NULL;
	foreach($faq AS $k => $v) :
		if ($v->catname != $c) {
			$c = $v->catname;
			echo '<div class="headline"><h3>' .clean($v->catname, 'str'). '</h3></div>';
		}
		if (!is_null($v->question)) {
	?>
<div class="accordion-group">
  <div class="accordion-heading">
    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapse<?php echo $k; ?>">
      <?php echo clean($v->question, 'str'); ?>
    </a>
  </div>
  <div id="collapse<?php echo $k; ?>" class="accordion-body collapse" style="height: 0px;">
    <div class="accordion-inner">
      <?php echo clean($v->reponse, 'bbcode'); ?>
    </div>
  </div>
</div><!--/accordion-group-->
<?php 
		}
	endforeach;
?>


            </div><!--/accardion-->
<?php
	else:
        ?>
            <div class="well well-small">
                Aucune question-r&eacute;ponse
            </div>
        <?php
	endif;
?>
</div>