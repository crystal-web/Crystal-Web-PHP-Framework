<form method="post">
<table width="100%">
<tr>
    <td width="200">
        <i class="icon-cog"></i>  <strong>Mettre le site en maintenance</strong>
    </td>
    <td>
        <p>
            <input type="radio" name="time_delay" value="no" <?php echo  $checkNon; ?>>Non
        </p>
        <p>

            <input type="radio" name="time_delay" value="sec" <?php echo $checkSec; ?>>
            Pendant:
            <select name="maintain" id="maintain" style="width: 100px;">
                <option value="inf" selected="selected">Non sp&eacute;cifi&eacute;</option>
                <option value="60">1 minute</option>
                <option value="300">5 minutes</option>
                <option value="600">10 minutes</option>
                <option value="900">15 minutes</option>
                <option value="1800">30 minutes</option>
                <option value="3600">1 heure</option>
                <option value="7200">2 heures</option>
                <option value="10800">3 heures</option>
                <option value="14400">4 heures</option>
                <option value="18000">5 heures</option>
                <option value="21600">6 heures</option>
                <option value="25200">7 heures</option>
                <option value="28800">8 heures</option>
                <option value="57600">16 heures</option>
            </select>
        </p>
        <p>
            <input type="radio" name="time_delay" value="date" <?php echo  $checkTo; ?>>
            Jusqu'au:
            <select name="j" id="jour" style="width: 50px;">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
            </select>

            <select name="m" id="mois" style="width: 60px;">
                <option value="1">Janv.</option>
                <option value="2">F&eacute;v.</option>
                <option value="3">Mars</option>
                <option value="4">Avril</option>
                <option value="5">Mai</option>
                <option value="6">Juin</option>
                <option value="7">Juill.</option>
                <option value="8">Ao&ugrave;t</option>
                <option value="9">Sept.</option>
                <option value="10">Oct.</option>
                <option value="11">Nov.</option>
                <option value="12">d&eacute;c.</option>
            </select>

            <select name="a" id="annee" style="width: 60px;">
                <?php
                for ($i=date('Y');$i<date('Y')+5;$i++)
                    echo '<option value="'.$i.'">'.$i.'</option>'.PHP_EOL;
                ?>
            </select>
        </p>
    </td>
</tr>
<tr>
    <td>
        <i class="icon-time"></i><strong> Afficher la dur&eacute;e de la maintenance ?</strong>
    </td>
    <td>
    <?php
    if ($displayDelay==true)
    {
    ?>
        <p><input type="radio" name="display_delay" id="display_delay" value="1" checked="checked">Oui</p>
        <p><input type="radio" name="display_delay" value="0">Non</p>
    <?php
    }
    else
    {
    ?>
        <p><input type="radio" name="display_delay" id="display_delay" value="1">Oui</p>
        <p><input type="radio" name="display_delay" value="0" checked="checked">Non</p>
    <?php
    }
    ?>
    </td>
</tr>

<tr>
    <td colspan="2"><i class="icon-edit"></i><strong> Texte &agrave; afficher lorsque la maintenance du site est en cours:</strong></td>
</tr>

<tr>
    <td colspan="2">
<?php
echo $this->mvc->Form->input('textForm', 'Texte du boutton', array('value' => $textForm)); ?>
    </td>
</tr>
<tr>
    <td colspan="2">
<?php
echo $this->mvc->Form->input('message', '', array('type' => 'textarea', 'editor' => array('params'=> array('model'=>'htmlfull')), 'value' => $message));
?>

    </td>
</tr>

<tr>
    <td colspan="2"><input type="submit" value="Enregistrer" /></td>
</tr>
</table>
</form>
