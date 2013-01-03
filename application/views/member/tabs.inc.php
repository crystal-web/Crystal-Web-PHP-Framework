<?php
	$page = Page::getInstance();
	$session = Session::getInstance();
	$form = Form::getInstance();
	$request = Request::getInstance();
	
	$page->setHeader('
<script type="text/javascript">
function lookup(inputString) { if(inputString.length == 0) { $(\'#suggestions\').hide(); } else { $.post("' . Router::url('rpc/cmd:findmember') . '", {queryString: ""+inputString+""}, function(data){ if(data.length >0) { $(\'#suggestions\').show(); $(\'#autoSuggestionsList\').html(data); } }); } }
function fill(thisValue) { $(\'#inputString\').val(thisValue); setTimeout("$(\'#suggestions\').hide();", 200); }
</script>
<style type="text/css">
.formSuggestions { margin: auto;width: 400px; }
.suggestionsBox {margin: auto; position: relative; width: 215px; background-color: white; color: white; float: left; border: 2px solid lightgray; -webkit-border-bottom-right-radius: 3px; -webkit-border-bottom-left-radius: 3px; -moz-border-radius-bottomright: 3px; -moz-border-radius-bottomleft: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; }
.suggestionList {margin: 0px !important;padding: 0px;list-style: none;}
.suggestionList ul {margin: 0 !important;}
.suggestionList li {margin: 0px 0px 3px 0px;padding: 5px;cursor: pointer;list-style:none;}
.suggestionList li:hover {background-color: #659CD8;}

ul.inline li { display : inline;padding : 0 0.5em; }
ul.inline {list-style-type : none;text-align: center;}
</style>
	');
?>




<form method="post" action="<?php echo Router::url('member/search'); ?>">
	
		<div class="formSuggestions">
			<input type="text" size="30" name="login" id="inputString" onkeyup="lookup(this.value);" autocomplete="off">
				<div style="width:940px;margin:auto;position: absolute;">
					<div class="suggestionsBox" id="suggestions" style="display: none; z-index: 500;">
						<ul class="suggestionList" id="autoSuggestionsList">
						</ul>
					</div>
				</div>
			<input type="submit" class="btn success">
		</div>
	</form>
	

<ul class="tabs">
  <li<?php echo ($request->getAction() == 'index') ? ' class="active"' : ''; ?>><a href="#default">Profil</a></li>
  <li><a href="#wall">Fil d'actualit&eacute;</a></li>
  <li><a href="#multi">Multi-compte</a></li>

  <?php
 	if ($session->isLogged()): ?>
  <li><a href="#edit">Editer mon profil</a></li>
  <?php endif; ?>
</ul>
