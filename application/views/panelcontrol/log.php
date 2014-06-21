<?php
$request = Request::getInstance();
Page::getInstance()->setHeader('
<script type="text/javascript">
function lookup(inputString) { if(inputString.length == 0) { $(\'#suggestions\').hide(); } else { $.post("' .Router::url('rpc/cmd:findmember'). '", {queryString: ""+inputString+""}, function(data){ if(data.length >0) { $(\'#suggestions\').show(); $(\'#autoSuggestionsList\').html(data); } }); } }
function fill(thisValue) { $(\'#inputString\').val(thisValue); setTimeout("$(\'#suggestions\').hide();", 200); }
</script>
<style type="text/css">
.formSuggestions { margin: auto;width: 305px; }
.suggestionsBox {margin: auto; position: relative; width: 215px; background-color: white; color: white; float: left; border: 2px solid lightgray; -webkit-border-bottom-right-radius: 3px; -webkit-border-bottom-left-radius: 3px; -moz-border-radius-bottomright: 3px; -moz-border-radius-bottomleft: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; }
.suggestionList {margin: 0px !important;padding: 0px;list-style: none;}
.suggestionList ul {margin: 0 !important;}
.suggestionList li {margin: 0px 0px 3px 0px;padding: 5px;cursor: pointer;list-style:none;}
.suggestionList li:hover {background-color: #659CD8;}
ul#logsiteli { text-align: center; }
ul#logsiteli li { display: inline; text-align: center; padding: 2px ; margin: 0; width: 30%; }
</style>
');
?>
    <div class="well well-small">
<?php
	echo '<ul id="logsiteli" class="list-unstyled">';
	foreach($tagList AS $k => $b) {
        echo '<li><a href="' . Router::url('panelcontrol/log/' . clean($b->tag, 'slug') ) . '">' . $b->tag . ' (' . $b->count . ')</a></li>';
    }
	echo '</ul></div>';

	if (count($log['query'])) {
        echo '<table class="table table-striped table-bordered">'.
            '<thead  class="grd-black color-white">
                <tr>
                    <td style="text-align:center;width:150px;">UID</td>
                    <td style="width:55px;">Taged</td>
                    <td>Message</td>
                </tr>
            </thead><tbody>';


        foreach ($log['query'] AS $k => $v) {
            echo '<tr>';
            echo '<td style="text-align:center;">' . date('j-n-Y \&\a\g\r\a\v\e; H:i ' , strtotime($v->hastime)) . '</td>';

                echo '<td><a href="' . Router::url('panelcontrol/log/' . clean($v->tag, 'slug') ) . '">' . $v->tag . '</a></td>';

            echo '<td>' . $v->msg . '</td>';
            echo '</tr>';
        }//*/

        echo '</tbody></table>' . pagination($log['page']);
    } else {
        echo 'Aucun log...';
    }