<table class="table table-bordered">
    <thead>
    <tr>
        <td style="width: 35px;">#</td>
        <td>Titre</td>
        <td style="width: 35px;">Action</td>
    </tr>
    </thead>
    <tbody>
    <?php for($i=0;$i<count($list);$i++):
    $alias = $list[$i];
    ?>
    <tr>
        <td><a href="<?php echo Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id); ?>"><?php echo $alias->id; ?></a></td>
        <td>
            <a href="<?php echo Router::url('article/page:' . clean($alias->title, 'slug') . '/id:' . $alias->id); ?>"><?php echo clean($alias->title, 'str'); ?></a>
        </td>
        <td class="text-center">
            <a href="<?php echo Router::url('article/manager/id:' . $alias->id . '/action:edit'); ?>"><i class="fa fa-pencil"></i></a>
            <a href="<?php echo Router::url('article/manager/id:' . $alias->id . '/action:del'); ?>"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>
    <?php endfor; ?>
    </tbody>
</table>