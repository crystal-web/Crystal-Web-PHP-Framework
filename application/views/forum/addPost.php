<?php
/**
* @title Forum | ajout poste
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Formulaire d'ajout d'un topic (mal nommé ?)
*/

?><style type="text/css">
.lili_listemessage{
border: 1px solid gray;
}
.lili_listemessage thead{
background-color: #222;
color: #fff;
}
.lili_infomembre
{
width:165px;
border-right: 1px solid gray;
}
.lili_infopost{
background-color: gray !important;
color: #fff;
}
.lili_avatar{
width: 90px;
height: 110px;
background-color:#fff;
text-align:center;
margin: auto;
border: 1px solid gray;
}
.lili_avatar img{
margin-top:7px;
}
form .input {
margin-right: 150px;
}
</style>


<form action="?token=<?php echo $this->mvc->Session->getToken(); ?>" method="post">
<?php
echo $this->mvc->Form->input('titre', 'Titre du sujet: ');
echo $this->mvc->Form->input('sous_titre', 'Sous-titre: ');
echo $this->mvc->Form->input('message', '', array('type'=>'textarea','editor'=>''));
	if (!$this->mvc->Session->isLogged())
	{
	echo '<div class="clearfix">
		<label for="captcha">Captcha : </label>
		<div class="input">
			' . $captcha_img.$captcha_hidden.$captcha_input . '
			<span class="help-block">Clique pour changé les couleurs</span>
		</div>
	</div>';
	}
	
echo $this->mvc->Form->input('submit', 'Enregistrer', array('type' => 'submit', 'class'=>'btn success'));

?>
</form>








