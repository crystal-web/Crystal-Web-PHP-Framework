<?php
	$this->mvc->Page->setHeaderCss(__CDN . '/files/css/jqueryUI/themes/Aristo/Aristo.css');
	$this->mvc->Page->setHeaderJs(__CDN . '/files/js/jqueryui/jquery-ui-1.8.17.min.js');
	echo '<script type="text/javascript">
jQuery(function($){
	$.datepicker.setDefaults( $.datepicker.regional[\'fr\'] );
	var datepickers = $(\'.datepicker\').datepicker({
		minDate : 0,
		dateFormat: \'yy-m-d\'
	})
});
	</script>';
?>


<form method="post">


<?php
	/*include_once "ckeditor/ckeditor.php";
	// Create a class instance.
	$CKEditor = new CKEditor();

	// Path to the CKEditor directory.
	$CKEditor->basePath = __CW_PATH . '/ckeditor/';

	// Creation de l'editeur avec la config BBcode $CKEditorConfig
	$CKEditor->editor("event", '', $CKEditorConfig);//*/

echo $this->mvc->Form->input('date', 'Date: ', array('class' => 'datepicker', 'autocomplete' => 'off', 'required' => 'required'));
	
echo $this->mvc->Form->input('heure', 'heure: ', array(
	'type' => 'select',
	'option' => 
array('0:00' => '0:00',
'0:30' => '0:30','1:00' => '1:00',
'1:30' => '1:30','2:00' => '2:00',
'2:30' => '2:30','3:00' => '3:00',
'3:30' => '3:30','4:00' => '4:00',
'4:30' => '4:30','5:00' => '5:00',
'5:30' => '5:30','6:00' => '6:00',
'6:30' => '6:30','7:00' => '7:00',
'7:30' => '7:30','8:00' => '8:00',
'8:30' => '8:30','9:00' => '9:00',
'9:30' => '9:30','10:00' => '10:00',
'10:30' => '10:30','11:00' => '11:00',
'11:30' => '11:30','12:00' => '12:00',
'12:30' => '12:30','13:00' => '13:00',
'13:30' => '13:30','14:00' => '14:00',
'14:30' => '14:30','15:00' => '15:00',
'15:30' => '15:30','16:00' => '16:00',
'16:30' => '16:30','17:00' => '17:00',
'17:30' => '17:30','18:00' => '18:00',
'18:30' => '18:30','19:00' => '19:00',
'19:30' => '19:30','20:00' => '20:00',
'20:30' => '20:30','21:00' => '21:00',
'21:30' => '21:30','22:00' => '22:00',
'22:30' => '22:30','23:00' => '23:00',
'23:30' => '23:30')));
echo $this->mvc->Form->input('resume', 'Note: ', array('type'=>'textarea', 'editor' => ''));
echo $this->mvc->Form->input('', 'Envoyer', array('type'=>'submit'));

/* Im not crazy xD
for($i=0; $i<24; $i++)
{
echo "'{$i}:00' => '{$i}:00',<br />";
echo "'{$i}:30' => '{$i}:30',";
}//*/

?>

</form>