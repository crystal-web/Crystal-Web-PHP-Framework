<?php $session = Session::getInstance(); ?>
<div class="panel panel-legend">
    <div class="panel-heading">
        R&eacute;glement du site et serveur
    </div>
    <div class="panel-body">
        <?php echo clean($cgu, 'html'); ?>
    </div>
    <div class="panel-footer">
        <p align="center">
            <a href="?token=<?php echo $session->getToken(); ?>" class="btn btn-default">
                J'ai lu et j'accepte les conditions
            </a>
        </p>
    </div>
</div>


