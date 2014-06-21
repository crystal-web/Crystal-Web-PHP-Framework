<?php
if (count($article)) {
	for($i=0;$i<count($article);$i++):
        $alias = $article[$i];
?>
<div class="panel panel-legend">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a href="<?php echo Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id); ?>" class="ajax" title="<?php echo clean($alias->title, 'str'); ?>">
                <?php echo clean($alias->title, 'str'); ?>
            </a>
        </h3>
    </div>
    <div class="panel-body" id="article_<?php echo $alias->id; ?>">
        <?php if (strlen($alias->picture)) { ?>
            <div class="article_pixel">
                <a href="<?php echo Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id); ?>" title="<?php echo clean($alias->title, 'str'); ?>">
                    <img class="img-responsive" style="margin: auto;" src="/assets/images/article/<?php echo $alias->picture; ?>">
                </a>
            </div>
        <?php } ?>
        <div class="article_text">
            <?php
            echo truncateBBcode($alias->content, 500, '... <div><a href="' . Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id) . '" title="' . clean($alias->title, 'str') . '" class="btn btn-default">Lire la suite</a></div>');
            ?>

        </div>
    </div>
    <div class="panel-footer">
        <div class="text-center">
            <span>
				<i class="icon-calendar fa fa-calendar"></i> <?php echo date('j/n/Y', $alias->time); ?>
			</span>
            &nbsp;
			<span>
				<i class="icon-user fa fa-user"></i>
                <a href="<?php echo Router::url("staff") . '#' .clean($alias->name, 'slug'); ?>"><?php echo clean($alias->name, 'slug'); ?></a>
			</span>

        <!--span>
                <a href="<?php echo Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id); ?>#comment" title="<?php echo clean($alias->title, 'str'); ?>">
				<i class="icon-comment fa fa-comments-o"></i> <?php $s = ($alias->comment > 1) ? 's':''; echo $alias->comment . ' commentaire' . $s; ?>
                </a>
			</span-->
        </div>
    </div>
</div>
<hr>
<?php
	endfor;
    echo pagination(ceil($nbArticle/9));
} else {
	$c = new errorController();
	$c->e404();
}
?>