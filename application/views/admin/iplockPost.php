<?php
if ($boolLock == true)
{
echo '<div class="MSGbox MSGvalide"><p>L\'adresse IP: <strong>'.$ip.'</strong> est maintenant bloqu&eacute;</p></div>';
}
?>

<form method="post">
<input type="text" name="ipx" style="width:75%" value="<?php echo  $ip; ?>" />
<input type="submit" value="Bloqu&eacute; l'ip">
</form>