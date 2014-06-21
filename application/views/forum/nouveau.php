<?php
if (isset($sujet)):
$form = Form::getInstance();
?>
<form action="<?php echo Router::url('forum/nouveau/slug:' . clean($sujet->name, 'slug') . '/id:' . $sujet->id); ?>" method="post" class="form-horizontal">
    <?php
    echo $form->input('title', 'Titre') .
        $form->input('subtitle', 'Sous-titre') .
        $form->input('content','', array('type' => 'textarea', 'editor' => 'bbcode')) .
        $form->submit('submit', 'Enregistrer');
    ?>
</form>
<?php
else:
$this->message = "Une ou plusieurs variable manque";
return $this->show('forum/error');
endif; ?>