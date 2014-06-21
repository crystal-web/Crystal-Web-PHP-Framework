<?php if(isset($topic, $topicList, $page)):
debug($topic, $topicList, $page);
    ?>
<table class="table table-bordered table-responsive forum-topic">
    <thead>
    <tr class="forum-topic-head">
        <th>Auteur</th>
        <th>Message</th>
    </tr>
    </thead>
    <tbody>
    <?php for ($to=0;$to<count($topicList);$to++):
    $alias = $topicList[$to];
    ?>
    <tr id="p<?php echo $alias->id; ?>">
        <td class="col-sm-2"><?php echo (!is_null($alias->Pauteur)) ? clean($alias->Pauteur, 'str') : 'Visiteur'; ?></td>
        <td>
            <span class="pull-left">
                <span class="label label-info" title="<?php echo dates($alias->created_time, 'fr_datetime'); ?>">
                    <i class="fa fa-align-justify"></i>
                    <?php echo getRelativeTime($alias->created_time); ?>
                </span>
                <?php
                if ( ($alias->edited_time - $alias->created_time) > 60):
                ?>
                <span class="label label-info" title="<?php echo dates($alias->edited_time, 'fr_datetime'); ?>">
                    <i class="fa fa-repeat"></i>
                    <?php echo getRelativeTime($alias->edited_time); ?>
                </span>
                <?php endif; ?>
            </span>
            <span class="pull-right clearfix">
                <?php if(true): ?>
                <a href="http://imagineyourcraft.fr/forum/action/delpost-2880">
                    <span class="label label-danger">
                        <i class="fa fa-trash"></i> Effacer
                    </span>
                </a>&nbsp;
                <?php endif;
                if(true): ?>
                <a href="http://imagineyourcraft.fr/forum/action/editpost-2880">
                    <span class="label label-success">
                        <i class="fa fa-pencil"></i> Éditer
                    </span>
                </a>&nbsp;
                <?php endif; ?>
                <a href="#p<?php echo $alias->id; ?>">
                    <span class="label label-warning">
                        <i>#<?php echo (($to+1)*$page); ?></i>
                    </span>
                </a>
            </span>
        </td>
    </tr>
    <tr>
        <td class="forum-topic-info">
            <a href="<?php echo Router::url('forum/profil/user:' . clean($alias->Pauteur, 'slug')); ?>">
                <img src="http://imagineyourcraft.fr/minecraft/votre-face/Thorin/100">
            </a>
        </td>
        <td class="forum-topic-message"><?php echo clean($alias->message, 'bbcode'); ?></td>
    </tr>
    <tr>
        <td></td>
        <td class="forum-topic-sign"><?php echo clean($alias->Psign, 'str'); ?></td>
    </tr>
    <?php endfor; ?>
    </tbody>
</table>
    <a class="btn btn-primary" href="<?php echo Router::url('forum/repondre/slug:' . clean($topic->titre, 'slug') . '/id:' . $topic->id); ?>">R&eacute;pondre</a>
<?php

else:
$this->message = "Une ou plusieurs variable manque";
return $this->show('forum/error');
endif;
?>