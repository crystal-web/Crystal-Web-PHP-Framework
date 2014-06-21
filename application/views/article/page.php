<?php 
if ($article) {
?>
<div id="article_<?php echo $article->id; ?>">
    <div class="panel panel-legend">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a href="<?php echo Router::url('article/page:' . clean($article->title, 'slug') . '/id:' . $article->id); ?>" class="ajax" title="<?php echo clean($article->title, 'str'); ?>">
                    <?php echo clean($article->title, 'str'); ?>
                </a>
            </h3>
        </div>
        <div class="panel-body" id="article_<?php echo $article->id; ?>">
            <?php if (strlen($article->picture)) { ?>
            <div class="article_pixel">
                <img class="img-responsive" style="margin: auto;" src="/assets/images/article/<?php echo $article->picture; ?>">
            </div>
                <hr/>
            <?php } ?>
            <div class="article_text">
                <?php echo clean($article->content, 'bbcode'); ?>
            </div>
            <hr class="visible">
            <div class="article_info">
                <div class="text-center">
                    <span>
                        <i class="icon-calendar fa fa-calendar"></i> <?php echo date('j/n/Y', $article->time); ?>
                    </span>
                &nbsp;
                    <span>
                        <i class="icon-user fa fa-user"></i>
                        <a href="<?php echo Router::url('minecraft/player/player:' . clean($article->name, 'slug')); ?>">
                            <?php echo clean($article->name, 'slug'); ?>
                        </a>
                    </span>

                    <?php
                    if ($isAdmin):
                        ?>
                        <span>
                            <i class="icon-edit"></i> <a href="<?php echo Router::url('article/manager/id:' . $article->id . '/action:edit'); ?>">&Eacute;dition</a>
                        </span>
                        <span>
                            <i class="icon-remove"></i> <a href="<?php echo Router::url('article/manager/id:' . $article->id . '/action:del'); ?>">Suppression</a>
                        </span>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div> <!-- /.panel-body -->
    </div><!-- /.panel.panel-legend -->
</div>


<div class="panel panel-legend">
    <div class="panel-heading">
        <h3 class="panel-title">
            Lache un commentaire
        </h3>
    </div>
    <div class="panel-body" id="article_postcomment_<?php echo $article->id; ?>">
        <?php $session = Session::getInstance(); if ($session->isLogged()): ?>
            <form method="post" class="form-horizontal comAjax" id="formComment">
                <?php
                $form = Form::getInstance();
                $user = (isset($ob_usr['user']))  ? $ob_usr['user'] : NULL;
                $mail = (isset($ob_usr['mail']))  ? $ob_usr['mail'] : NULL;
                echo $form->input('ctrl', 'hidden', array('type' => 'hidden', 'value' => 'article'));
                echo $form->input('bid', 'hidden', array('type' => 'hidden', 'value' => $article->id));
                echo $form->input('comment', '', array('type' => 'textarea', 'editor' => 'bbcode', 'maxlength' => 255));
                ?>
                <div class="form-group input-group">
                    <span class="input-group-addon" style="padding: 1px;"><?php echo Captcha::generateImgTags(".."); ?></i>
                    </span>
                    <?php echo Captcha::generateHiddenTags().Captcha::generateInputTags(); ?>
                </div>
                <?php
                echo $form->submit('submit', 'Poster');
                ?>
            </form>
        <?php else: ?>
            <div class="well">
                Vous devez &ecirc;tre connect&eacute; pour poster un commentaire
            </div>
        <?php endif; ?>
    </div>
</div>



    <?php if (count($commentList)): ?>
        <div class="panel panel-legend">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Liste des commentaires
                </h3>
            </div>
            <div class="panel-body">

                <div id="comment" class="article">
                    <ul>
                        <?php
                        for($i=0;$i<count($commentList);$i++):
                            ?>
                            <li>
                                <div class="comment-body">
                                    <div class="comment-author">
                                        <img src="<?php echo (Securite::isMail($commentList[$i]->mail)) ? get_gravatar($commentList[$i]->mail) : $commentList[$i]->mail; ?>" alt="" style="width:80px;">
                                        <cite><?php echo clean($commentList[$i]->user, 'str'); ?></cite>
                                    </div>
                                    <div class="comment-date"><?php echo ucfirst(dates($commentList[$i]->time, 'fr_date')); ?></div>
                                    <div class="comment_content">
                                        <?php echo clean($commentList[$i]->comment, 'bbcode') . '<p></p>'; ?>
                                    </div>
                                </div>
                            </li>
                        <?php
                        endfor;
                        ?>
                    </ul>
                </div>

            </div>
        </div>
<?php
    endif;
} else {
	$c = new errorController();
	$c->e404();
}
