<ul>
<?php
if ($this->mvc->Acl->isAllowed('member', 'getList')): ?>
	<li><a href="<?php echo Router::url('member/getlist'); ?>">Liste des membres</a></li>
<?php
endif;
if ($this->mvc->Acl->isAllowed('member', 'cgu_manager')): ?>
	<li><a href="<?php echo Router::url('member/cgu_manager'); ?>">Changement de la CGU</a></li>
<?php
endif;
if ($this->mvc->Acl->isAllowed('member', 'approb_change_login')): ?>
	<li><a href="<?php echo Router::url('member/approb_change_login'); ?>">Approuvé le changement de pseudo</a></li>
<?php
endif;
?>
</ul>

