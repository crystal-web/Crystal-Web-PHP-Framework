<?php
class Form{
	
	public $mvc; 
	public $errors; 
	const errorDisplay = 'block';			//block ou inline
	
	public function __construct($mvc){
		$this->mvc = $mvc; 
	}
	
	public function setErrors($arrayErrors)
	{
	$this->errors = $arrayErrors;
	}
	
	public function input($name,$label,$options = array())
	{
		$error = false; 
		$classError = ''; 
		
		// Ajoute la valeur d'erreur textuelle au champ en erreur
		if(isset($this->errors[$name]))
		{
			$error = $this->errors[$name];
			$classError = ' error'; 
		}
		
		// Ajoute la valeur de champ a value si elle exist
		if(!isset($this->mvc->Request->data->$name) && isSet($options['value']))
		{
			$value = $options['value']; 
		}
		elseif (!isset($this->mvc->Request->data->$name))
		{
			$value = ''; 
		}
		else{
			$value = $this->mvc->Request->data->$name; 
		}
		
		
		// Cache un champ, si son label est hidden
		if($label == 'hidden'){
			if (isSet($options['default']) && empty($value)){
				 return '<input type="hidden" name="'.$name.'" value="'.$options['default'].'">';
			}
			elseif(!empty($value))
			{
				return '<input type="hidden" name="'.$name.'" value="'.$value.'">'; 
			}
		}
		
		
		// Ouverture de la balise div pour la structure
		$html = '<div class="clearfix'.$classError.'">';
		// Site l'option type n'est pas défini ou est different de submit
		// On l'affiche
		if (!isSet($options['type']) OR $options['type'] != 'submit')
		{
		$html .= '<label for="input'.$name.'">'.$label.'</label>';
		}

		// Ouverture de la structure interne au champ
		$html .= '<div class="input">';
		
		// Initialisations des attributs
		$attr = ' '; 
		// Parcour du tableau option
		foreach($options as $k=>$v)
		{
			if($k!='type' && $k!='addon' && $k!='default' && $k!='options')
			{
			$attr .= " $k=\"$v\""; 
			}
		}
		
		// Aucun type defini et aucune option on affiche la forme standard
		if(!isset($options['type']) && !isset($options['options']))
		{
			if (isSet($options['addon']))
			{
			$html .= '<div class="input-prepend">
				<span class="add-on">'.$options['addon'].'</span>';
			$html .= '<input type="text" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
			$html .= '</div>';
			}
			else
			{
			$html .= '<input type="text" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
			}
		}
		// Un champ d'option est défini on le traite
		elseif(isset($options['options']))
		{
			$html .= '<select id="input'.$name.'" name="'.$name.'" '.$attr.'>';
			foreach($options['options'] as $k=>$v)
			{
				$html .= '<option value="'.$k.'" '.($k==$value?'selected':'').'>'.$v.'</option>'; 
			}
			$html .= '</select>'; 
		}
		// Le champ type défini un textarea
		elseif($options['type'] == 'textarea')
		{
			// On demand l'editeur visuel
			if (isSet($options['editor']))
			{
				// Valeur n'est pas vide on remplis
				if (!empty($value))
				{
				$html .= '<textarea id="input'.$name.'" name="'.$name.'">'.$value.'</textarea>';
				}
				// les champ editor et value sont vide, on affiche 
				else
				{
				$html .= '<textarea id="input'.$name.'" name="'.$name.'"></textarea>';
				}
				
				
				
				
				
$toolbar = '[ "Undo","Redo","-","Bold","Italic","Underline","Strike","FontSize" ], ["Image","Link","Unlink"], ["Find","Replace","-","SelectAll","RemoveFormat","\/","BulletedList","-","Blockquote","TextColor","-","Smiley","-","Maximize"]';

$document = '[ "Source","-","Save","NewPage","Preview","Print","-","Templates" ]';
$documentNoSource = '[ "Save","NewPage","Preview","Print","-","Templates" ]';
$documentEdit = '[ "Find","Replace","-","SelectAll","-","SpellChecker", "Scayt","-","Maximize" ]';

$paragraph = '[ "Undo","Redo","-","Bold","Italic","Underline","Strike","NumberedList","BulletedList","-","Outdent","Indent","-","Blockquote","CreateDiv","-","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock","-","BidiLtr","BidiRtl" ]';
$insert = '[ "Image","Flash","Table","HorizontalRule","Smiley","SpecialChar","PageBreak","Iframe" ]';
$link = '[ "Link","Unlink","Anchor" ]';
$color = '[ "TextColor","BGColor" ]';
$style = '[ "Styles","Format","Font","FontSize" ]';
$basic = '["Undo","Redo","-","Cut","Copy","Paste","PasteText","PasteFromWord"]';

$basicstyles = '["Bold","Italic","Underline","Strike","FontSize","Subscript","Superscript"],["Image","Link","Unlink"],["Find","Replace","-","SelectAll","RemoveFormat","BulletedList","-","Blockquote","TextColor","-","Smiley","-","Maximize"]';
$bbcode = '["Undo","Redo","-","Bold","Italic","Underline","Strike","NumberedList","BulletedList","FontSize","Subscript","Superscript"],["Image","Link","Unlink"]';
					
				if (isSet($options['editor']['params']))
				{
				
					if (!isSet($options['editor']['params']['model']))
					{
						if (isSet($options['editor']['params']['extraPlugins']))
						{
						$extraPlugins = $options['editor']['params']['extraPlugins'];
						}
						else
						{
						$extraPlugins = '"extraPlugins":"bbcode",';
						}
						
						/*
						* http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
						*/
						if (isSet($options['editor']['params']['toolbar']))
						{
						$toolbar = $options['editor']['params']['toolbar'];
						}
						else
						{
$toolbar = '"toolbar":[["Undo","Redo","-","Bold","Italic","Underline","Strike","FontSize"],["Image","Link","Unlink"],["Find","Replace","-","SelectAll","RemoveFormat","\/","BulletedList","-","Blockquote","TextColor","-","Smiley","-","Maximize"]]';
						}
						$params = '{'.$extraPlugins.''.$toolbar.'}';
					}
					else
					{
	
						switch ($options['editor']['params']['model'])
						{
						case 'htmlfull':
						$params = '{"toolbar":['.$document.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
						break;
						case 'html':
						$params = '{"toolbar":['.$documentNoSource.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
						break;	
						default:
						$params = '{"extraPlugins":"bbcode","toolbar":['.$bbcode.']}';
						break;
						}
					}
					
				}
				else
				{
				$params = '{"extraPlugins":"bbcode","toolbar":['.$bbcode.']}';
				}
				
				// Chargement de l'editeur, apres le textarea
				$html .= '<script type="text/javascript">//<![CDATA[
				window.CKEDITOR_BASEPATH=\''.__CW_PATH.'/ckeditor/\';
				//]]></script>
				<script type="text/javascript" src="'.__CW_PATH.'/ckeditor/ckeditor.js?t=B5GJ5GG"></script>
				<script type="text/javascript">//<![CDATA[
				CKEDITOR.replace(\''.$name.'\', '.$params.');
				//]]></script>';
			}
			// On demand le textarea sans l'editeur visuel
			else
			{
			$html .= '<textarea id="input'.$name.'" name="'.$name.'"'.$attr.'>'.$value.'</textarea>';
			}
			
			
		}
		elseif($options['type'] == 'checkbox')
		{
			$html .= '<input type="hidden" name="'.$name.'" value="0"><input type="checkbox" name="'.$name.'" value="1" '.(empty($value)?'':'checked').'>'; 
		}
		elseif($options['type'] == 'radio')
		{
		$html .= '<ul class="inputs-list">';
			foreach($options['option'] AS $k=>$v)
			{
            
			 $sel = ($value === $k) ?'checked="checked"':'';
			 $html .= '<li>
                  <label><input type="radio" name="'.$name.'" value="'.$k.'" '.$sel.'> <span>'.$v.'</span></label>
                </li>'; 
			}
		$html .= '</ul>';
		}
		
		elseif($options['type'] == 'file')
		{
			$html .= '<input type="file" class="input-file" id="input'.$name.'" name="'.$name.'"'.$attr.'>';
		}
		elseif($options['type'] == 'password')
		{
			$html .= '<input type="password" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
		}
		elseif($options['type'] == 'submit')
		{
			return '<div class="actions"><input type="submit" id="input'.$name.'" value="'.$label.'"'.$attr.'></div>';
		}
		
		if(isSet($options['help']) && !$error)
		{
			$html .= '<span class="help-block">'.$options['help'].'</span>';
		}
		elseif($error)
		{
			$html .= '<span class="help-'.self::errorDisplay.'">'.$error.'</span>';
		}
		$html .= '</div></div>';
		return $html; 
	}

}