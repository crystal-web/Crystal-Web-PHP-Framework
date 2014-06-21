<?php
if (isset($sujet)):
$form = Form::getInstance();
?>
<form action="<?php echo Router::url('forum/repondre/slug:' . clean($topic->titre, 'slug') . '/id:' . $topic->id); ?>" method="post" class="form-horizontal">
    <?php
    echo $form->input('content','', array('type' => 'textarea', 'editor' => 'bbcode')) .
        $form->submit('submit', 'Enregistrer');
    ?>
</form>
<?php
else:
    $this->message = "Une ou plusieurs variable manque";
    return $this->show('forum/error');
endif; ?>