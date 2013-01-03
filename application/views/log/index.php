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

<div class="well">
<form method="post">
	
	
	<div class="formSuggestions">
		<input type="text" size="30" name="login" id="inputString" onkeyup="lookup(this.value);" AUTOCOMPLETE="off">
			<div style="width:940px;margin:auto;position: absolute;">
				<div class="suggestionsBox" id="suggestions" style="display: none; z-index: 500;">
					<ul class="suggestionList" id="autoSuggestionsList">
					</ul>
				</div>
			</div>
		<input type="submit" class="btn success">
	</div>
</form>

<?php 

	echo '<ul id="logsiteli">';
	foreach($tagList AS $k => $b)
	{
		echo '<li><a href="' . Router::url($request->controller . '/' . clean($b->tag, 'slug') ) . '">' . $b->tag . ' (' . $b->count . ')</a></li>';	
	}
	echo '</ul></div>';

	if (count($log['query']))
	{	

	echo '<table class="zebra-striped bordered-table condensed-table">'.
		'</thead>
			<tr>
				<td style="text-align:center;width:150px;">UID</td>
				<td style="width:55px;">Taged</td>
				<td>Message</td>
			</tr>
		</thead><tbody>';
	
		$authorized = (AccessControlList::getInstance()->isAllowed('log', 'all'));
		
		
		foreach ($log['query'] AS $k => $v)
		{
		echo '<tr>';
			if ($v->loginmember)
			{
				echo '<td style="text-align:center;"><a href="' . Router::url($request->controller . '/this/' . $v->uid) . '">' . $v->loginmember . '</a> (<a href="' . Router::url('member/index/slug:' . $v->loginmember) . '">Voir profil</a>)</a><br>' . date('j-n-Y \&\a\g\r\a\v\e; H:i ' , strtotime($v->hastime)) . '</td>';
			}
			else
			{
				echo '<td style="text-align:center;">Non membre<br>' . date('j-n-Y \&\a\g\r\a\v\e; H:i ' , strtotime($v->hastime)) . '</td>';
			}
			

			
			if ($authorized)
			{
				echo '<td><a href="' . Router::url($request->controller . '/' . clean($v->tag, 'slug') ) . '">' . $v->tag . '</a></td>';
			}
			else
			{
				echo '<td>' . $v->tag . '</td>';
			}
			
			echo '<td>' . $v->msg . '</td>';
		echo '</tr>';
		
		}//*/

	echo '</tbody></table>' . pagination($log['page']);
	}
	else
	{
		Session::getInstance()->setFlash('Aucun log...', 'warning');
	}