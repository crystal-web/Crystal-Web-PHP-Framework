<?php if(isset($cat, $sujet, $topicList)):
//    debug($cat, $sujet, $topicList);
?>
<script type="application/javascript">
    jQuery(function(){
        jQuery(document).on('click', '.topicPreview', function(){
            var obj = jQuery(this);
            var id = obj.attr('data-tid');
            if (obj.hasClass("icon-time")) {
                return;
            } else if (obj.hasClass("fa-plus-circle")) {
                // Action recherche
                obj.removeClass("fa-plus-circle").addClass("fa-refresh fa-spin");
                jQuery.ajax({
                    url: '/forum/rpc',
                    type: 'POST',
                    data:{query:"topicPreview", data: id},
                    success: function(resp){
                        obj.removeClass("fa-refresh fa-spin").addClass("fa-minus-circle");
                        if (resp == "error") {
                            bootbox.alert('Oups... Chargement des donn&eacute;es impossible.');
                            return obj.removeClass("fa-refresh fa-spin").addClass("fa-ban text-danger");
                        }
                        obj.removeClass("icon-time").addClass("icon-remove-sign");
                        jQuery("[data-block-tid='" + id + "']").after( resp );
                        var tab = jQuery("[data-block-tid='" + id + "']").next();
                        tab.toggle('slow');
                    },
                    error: function(resp){
                        bootbox.alert('Oups... Chargement des donn&eacute;es impossible.');
                        return obj.removeClass("fa-refresh fa-spin").addClass("fa-ban text-danger");
                    }
                });
            } else  if (obj.hasClass("fa-minus-circle")){
                // Action fermer clean
                var tab = jQuery("[data-block-tid='" + id + "']").next();
                if (typeof(tab.attr("data-block-tid")) == "undefined"){
                    obj.removeClass("fa-minus-circle").addClass("fa-refresh fa-spin");
                    tab.fadeOut('slow', function(){
                        tab.remove();
                        obj.removeClass("fa-refresh fa-spin").addClass("fa-plus-circle");
                    });
                }
                return;
            }
        });
    });
</script>
<div class="forum-action">
    <a class="btn btn-primary" href="<?php echo Router::url('forum/nouveau/slug:' . clean($sujet->name, 'slug') . '/id:' . $sujet->id); ?>">Nouveau Topic</a>
</div>

<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th colspan="7">
            <a href="<?php echo Router::url('forum/sujet/slug:' . clean($cat->Cname, 'slug') . '/id:' . $cat->Cid); ?>"><?php echo clean($cat->Cname, 'str');  ?></a> -
            <a href="<?php echo Router::url('forum/sujet/slug:' . clean($sujet->name, 'slug') . '/id:' . $sujet->id); ?>"><?php echo clean($sujet->name, 'str');  ?></a>
            <div><?php echo clean($sujet->description, 'str'); ?></div>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php if (!count($topicList)): ?>
        <tr>
            <td colspan="7" class="text-center">
                <a class="btn btn-primary" href="<?php echo Router::url('forum/nouveau/slug:' . clean($sujet->name, 'slug') . '/id:' . $sujet->id); ?>">Commencer une discution</a>
            </td>
        </tr>
    <?php else: ?>
    <tr>
        <td class="col-sm-7" colspan="3">Titre du topic</td>
        <td class="number col-sm-1">R&eacute;ponses</td>
        <td class="col-sm-1">Auteur</td>
        <td class="col-sm-1">Vue</td>
        <td class="col-sm-2">Last Action</td>
    </tr>
        <?php for($topic=0;$topic<count($topicList);$topic++):
        $alias = $topicList[$topic];
            $alias->PAlogin = (is_null($alias->PAlogin)) ? 'Visiteur' : $alias->PAlogin;
            $alias->LPAlogin = (is_null($alias->LPAlogin)) ? 'Visiteur' : $alias->LPAlogin;
        ?>
    <tr data-block-tid="<?php echo $alias->topic_id; ?>">
        <td class="col-sm-1"><img src="http://imagineyourcraft.fr/files/images/lili/sujet_read.png" alt="Sujet lu"></td>
        <td class="col-sm-1"><i class="fa fa-lock"></i> <i class="fa fa-volume-up"></i></td>
        <td>
            <a href="<?php echo Router::url('forum/topic/slug:' . clean($alias->titre, 'slug') . '/id:' . $alias->topic_id); ?>"><?php echo clean($alias->titre, 'str'); ?></a>
            <span class="pull-right">
                <i class="fa fa-plus-circle topicPreview" data-tid="<?php echo $alias->topic_id; ?>"></i>
            </span>
        </td>
        <td class="number"><?php echo $alias->nb_post; ?></td>
        <td>
            <?php if ($alias->PAlogin != 'Visiteur'):  ?>
                <a href="<?php echo Router::url('forum/profil/user:' . clean($alias->PAlogin, 'slug')); ?>"><?php echo clean($alias->PAlogin, 'slug'); ?></a>
            <?php else: ?>
                <?php echo clean($alias->PAlogin, 'slug'); ?><br>
            <?php endif; ?>
        </td>
        <td class="number">123456</td>
        <td>
            <?php if ($alias->LPAlogin != 'Visiteur'):  ?>
            par <a href="<?php echo Router::url('forum/profil/user:' . clean($alias->LPAlogin, 'slug')); ?>"><?php echo clean($alias->LPAlogin, 'slug'); ?></a><br>
            <?php else: ?>
                par <?php echo clean($alias->LPAlogin, 'slug'); ?><br>
            <?php endif; ?>
            <?php echo getRelativeTime($alias->update_time) ?>
        </td>
    </tr>
        <?php endfor; ?>
    <?php endif; ?>
    </tbody>
</table>
<div class="forum-action">
    <a class="btn btn-primary" href="<?php echo Router::url('forum/nouveau/slug:' . clean($sujet->name, 'slug') . '/id:' . $sujet->id); ?>">Nouveau Topic</a>
</div>
<?php
else:
$this->message = "Une ou plusieurs variable manque";
return $this->show('forum/error');
endif; ?>