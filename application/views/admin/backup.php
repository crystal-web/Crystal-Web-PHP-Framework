<h1>Sauvegarde</h1>
<p>Il est fortement conseillé de faire un back-up de toutes les données fréquemment.<br />
Les dossiers de taille relativement importante risque de faire planter la compréssion.</p>

<p>
<ul style="list-style: none;">
<?php
foreach ($backupthis as $key){
// Print fit son apparition
echo '<li><a href="{THEME_LINKTO}/index.php?module=admin_backup&action=bak&me='.$key.'">'.$key.'-' . date('Y-m-d-h').'.zip</a></li>';
}
?>
</ul></p>