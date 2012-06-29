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
	if (!is_array($arrayErrors)) {throw new Exception ('setErrors require array ;-) ');}
	$this->errors = $arrayErrors;
	}
	
	public function input($name,$label,$options = array())
	{
		$error = false; 
		$value = $classError = ''; 
		
		/**
		 * Ajoute la valeur d'erreur textuelle au champ en erreur
		 */
		if(isset($this->errors[$name]))
		{
			$error = $this->errors[$name];
			$classError = ' error'; 
		}
		
		/**
		 * Ajoute la valeur de champ a value si elle exist
		 */
		if(!isset($this->mvc->Request->data->$name) && isSet($options['value']))
		{
			$value = $options['value']; 
		}
		elseif(isset($this->mvc->Request->data->$name))
		{
			$value = clean($this->mvc->Request->data->$name, 'str'); 
		}
		
		
		
		/**
		 * Cache un champ, si son label est hidden
		 */
		if( $label == 'hidden' )
		{
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
		

		/**
		 * Site l'option type n'est pas d�fini ou est different de submit
		 * On l'affiche
		 */
		if (!isSet($options['type']) OR $options['type'] != 'submit')
		{
		$html .= '<label for="input'.$name.'">'.$label.'</label>';
		}

		
		// Ouverture de la structure interne au champ
		$html .= '<div class="input">';
		
		
		// Initialisations des attributs
		$attr = ' '; 

		/**
		 * Parcour du tableau option
		 */
		foreach($options as $k=>$v)
		{
			if($k!='type' && $k!='addon' && $k!='default' && $k!='options')
			{
			$attr .= " $k=\"$v\""; 
			}
		}
		

		/**
		 * Aucun type defini et aucune option on affiche la forme standard
		 */
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
		
		
		/**
		 * Un champ d'option est d�fini on le traite
		 */
		elseif(isset($options['options']))
		{
			$html .= '<select id="input'.$name.'" name="'.$name.'" '.$attr.'>';
			foreach($options['options'] as $k=>$v)
			{
				$html .= '<option value="'.$k.'" '.($k==$value?'selected':'').'>'.$v.'</option>'; 
			}
			$html .= '</select>'; 
		}	
		
		
		/**
		 * Le champ type d�fini un textarea
		 */
		elseif($options['type'] == 'textarea')
		{
			// On demand l'editeur visuel
			if (isSet($options['editor']))
			{			
			$html .= $this->editor($options, $name, $value);
			}
			// On demand le textarea sans l'editeur visuel
			else
			{
			$html .= '<textarea id="input'.$name.'" name="'.$name.'"'.$attr.'>'.$value.'</textarea>';
			}
			
		}
		
		
		/**
		 * CheckBox
		 */
		elseif($options['type'] == 'checkbox')
		{
			$html .= '<input type="hidden" name="'.$name.'" value="0"><input type="checkbox" name="'.$name.'" value="1" '.(empty($value)?'':'checked').'>'; 
		}
		
		
		/**
		 * Radio
		 */
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
		
		
		/**
		 * Fichier
		 */
		elseif($options['type'] == 'file')
		{
			$html .= '<input type="file" class="input-file" id="input'.$name.'" name="'.$name.'"'.$attr.'>';
		}
		
		
		/**
		 * Mot de passe
		 */
		elseif($options['type'] == 'password')
		{
			$html .= '<input type="password" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
		}
		
		
		/**
		 * Action submit
		 */
		elseif($options['type'] == 'submit')
		{
			return '<div class="actions"><input type="submit" id="input'.$name.'" value="'.$label.'"'.$attr.' onclick="this.disabled=1; this.form.submit();"></div>';
		}
		

		/**
		 * Action submit
		 */
		elseif($options['type'] == 'date')
		{
			$html .= $this->date($value);
		}
		
	
		/**
		 * Ajout d'un help block
		 */
		if(isSet($options['help']) && !$error)
		{
			$html .= '<span class="help-block">'.$options['help'].'</span>';
		}
		elseif($error)
		{
			$html .= '<span class="help-'.self::errorDisplay.'">'.$error.'</span>';
		}
		
		// Fermeture des division
		$html .= '</div></div>';
		return $html; 
	}
	
	
	
	
	/**
	 * 
	 * Dessin un editeur visuel CkEditor
	 * @param array $options
	 * @param string $name
	 * @param string $value
	 */
	private function editor($options, $name, $value = NULL)
	{
	// Prepare les diff�rents options pr�d�fini
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
	
	// Initialise $html
	$html = NULL;
	
		// Valeur n'est pas vide on remplis
		if (!empty($value)) { $html .= '<textarea id="input'.$name.'" name="'.$name.'">'.$value.'</textarea>'; }
		// les champ editor et value sont vide, on affiche 
		else { 	$html .= '<textarea id="input'.$name.'" name="'.$name.'"></textarea>'; }
		

		// A-t-on des paramettres ?
		if (isSet($options['editor']['params']))
		{
			// On utilise un model ?
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
	/*	Suppréssion de //<![CDATA[ script //]]> pour une compatibilité avec Config.class.php
	 * ***************************************************
	 * $html .= '<script type="text/javascript">//<![CDATA[
		window.CKEDITOR_BASEPATH=\''.__CW_PATH.'/ckeditor/\';
		//]]></script>
		<script type="text/javascript" src="'.__CW_PATH.'/ckeditor/ckeditor.js?t=B5GJ5GG"></script>
		<script type="text/javascript">//<![CDATA[
		CKEDITOR.replace(\''.$name.'\', '.$params.');
		//]]></script>';*/
		$html .= '<script type="text/javascript">
		window.CKEDITOR_BASEPATH=\''.__CW_PATH.'/ckeditor/\';
		</script>
		<script type="text/javascript" src="'.__CW_PATH.'/ckeditor/ckeditor.js?t=B5GJ5GG"></script>
		<script type="text/javascript">
		CKEDITOR.replace(\''.$name.'\', '.$params.');
		</script>';
		
		return $html;
	
	}

	
	
	/**
	 * 
	 * Cr�e un selecteur de date
	 * @param string $value
	 */
	private function date($value)
	{
		$fr_month = array('Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
		'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre');
	
		if (isSet($this->mvc->Request->data->day) AND isSet($this->mvc->Request->data->month) AND isSet($this->mvc->Request->data->year))
		{
			$value = $this->mvc->Request->data->month . '-' . $this->mvc->Request->data->day . '-' . $this->mvc->Request->data->year;
		}
		
		
		/**
		 * Si on pas de valeur 
		 */
		if (empty($value))
		{
			$value = date('n-j-Y');	
		}

		/**
		 * On a recu, un timestamp
		 */
		if (is_int($value))
		{
			$value = date('n-j-Y', $value);				
		}
		
		
		/**
		 * On a une date normalis�
		 */
		if (preg_match('#-#', $value))
		{
			list($month, $day, $year) = explode('-', $value);
		}

		
		/**
		 * On a une erreur ;-)
		 */
		if (!checkdate($month, $day, $year))
		{
			
			/**
			 * L'ann�e
			 */
			if ( $year < (date('Y')-70) OR $year > (date('Y')+5) )
			{
			$year = date('Y');
			}
			
			/**
			 * Le jour
			 */
			if (!is_int($day) OR $day < 1 OR $day > 31)
			{
			$day = date('j');				
			}
			
			/**
			 * Le mois
			 */
			if (!is_int($month) OR $month < 1 OR $month > 12)
			{
			$month = date('n');				
			}
			
			
			/**
			 * V�rifie a nouveau si la date est correcte
			 * En particulier pour f�vrier
			 */
			if (!checkdate($month, $day, $year))
			{
				// Recherche le nombre de jour dans le mois
				$nbDay = date(�t�,mktime(0,0,0,$month,1,$year));
				
				/**
				 * Si le jour est plus grand que le nombre de jour total
				 * On lui donne �a valeur maximal
				 */
				if ($day > $nbDay)
				{
				$day = $nbDay;
				}

			}
		}
		
		

		
		// Jours
		$html = '<select name="day" id="inputday" style="width: auto !important;">';
		for ($i=1; $i<=31; $i++)
		{
			$seleted = ($i == $day) ? ' selected="selected"' : '';
			$html .= '<option value="'.$i.'"'.$seleted.'>'.$i.'</option>';
		}
		$html .= '</select>';
		
		// Mois
		$html .= '<select name="month" id="inputmonth" style="width: auto !important;">';
		for ($i=1; $i<=12; $i++)
		{
			$seleted = ($i == $month) ? ' selected="selected"' : '';
			$html .= '<option value="'.$i.'"'.$seleted.'>'.$fr_month[$i-1].'</option>';
		}
		$html .= '</select>';
		
		
		// Annee
		$html .= '<select name="year" id="inputyear" style="width: auto !important;">';
		for ($i=(date('Y')-70); $i<=(date('Y')+5); $i++)
		{
			$seleted = ($i == $year) ? ' selected="selected"' : '';
			$html .= '<option value="'.$i.'"'.$seleted.'>'.$i.'</option>';
		}
		$html .= '</select>';	
	
		
		return $html;
	}
	
	
}





