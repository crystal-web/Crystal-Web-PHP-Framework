<?php if (isset($catList)):
    for($cat=0;$cat<count($catList);$cat++):
?>
<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th class="col-sm-12" colspan="5">
                <a href="<?php echo Router::url('forum/categorie/slug:' . clean($catList[$cat]->name, 'slug') . '/id:' . $catList[$cat]->id); ?>"><?php echo clean($catList[$cat]->name, 'str'); ?></a>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="info">
            <td colspan="2" class="col-sm-8">Forum</td>
            <td class="col-sm-1">Topics</td>
            <td class="col-sm-1">R&eacute;ponses</td>
            <td class="col-sm-2">Derni&egrave;re post</td>
        </tr>
        <?php
        for($sujet=0;$sujet<count($catList[$cat]->sujet);$sujet++):
            $alias = $catList[$cat]->sujet[$sujet];
        ?>
        <tr>
            <td class="col-sm-1"><img src="<?php echo clean($alias->icone, 'str'); ?>" alt="<?php echo clean($alias->name, 'str'); ?>"></td>
            <td>
                <a href="<?php echo Router::url('forum/sujet/slug:'.clean($alias->name, 'slug').'/id:' . $alias->id); ?>"><?php echo clean($alias->name, 'str'); ?></a>
                <div class="description">
                    <?php echo clean($alias->description, 'str'); ?>
                </div>
            </td>
            <td class="number"><?php echo $alias->nb_topic; ?></td>
            <td class="number"><?php echo $alias->nb_post; ?></td>
            <td>
                <div>
                    <?php
                        if (!empty($alias->Tid) && is_numeric($alias->Tid)) {
                            ?>
                            Dernier poste le 5 avril 2012<br>
                            Dans: <?php echo truncatestr(clean($alias->Ttitre, 'str'), 20); ?><br>
                            <?php if (!empty($alias->LPpost) || $alias->LPpost == 0): ?>
                            Par: <a href="<?php echo Router::url('forum/profil/user:' . clean($alias->LPpost, 'slug')); ?>"><?php echo clean($alias->LPpost, 'slug'); ?></a>
                            <?php else: ?>
                                Par: Visiteur
                            <?php
                            endif;
                        } else {
                            echo 'Aucun topic';
                        }
                    ?>
                </div>
            </td>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>
<?php
    endfor;
else:
    $this->message = "Une ou plusieurs variable manque";
    return $this->show('forum/error');
endif; ?>