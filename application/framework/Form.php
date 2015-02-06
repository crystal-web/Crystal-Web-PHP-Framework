<?php
/*##################################################
 *                                 Form.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

class Form{
    private $request;
    public $mvc;
    public $errors;
    const errorDisplay = 'block';			//block ou inline


    /**
     * @var Form
     * @access private
     * @static
     */
    private static $_instance = null;


    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     *
     * @param void
     * @return Form
     */
    public static function getInstance($mvc='ToDel') {
        if(is_null(self::$_instance)) {
            self::$_instance = new Form($mvc);
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->request = Request::getInstance();
    }

    public function setErrors($arrayErrors) {
        if (!is_array($arrayErrors)) {
            throw new Exception ('setErrors require array is a ' . gettype($arrayErrors) . '  ;-) ');
        }
        $this->errors = $arrayErrors;
    }

    private function clean($name) {
        return clean(trim($name), 'str');
    }

    private function getAttribut($options) {
        // Initialisations des attributs
        $attr = ' ';

        unset($options['prepend']);
        unset($options['append']);
        unset($options['addon']);
        unset($options['default']);
        unset($options['options']);
        unset($options['value']);
        unset($options['type']);
        unset($options['editor']);
        unset($options['help']);

        // Parcour du tableau option
        if (count($options)) {
            foreach($options as $k=>$v) {
                $attr .= " $k=\"$v\"";
            }
        }
        return $attr;
    }

    /**
     * Retourne le message d'erreur ou false si il n'y en a pas
     */
    private function checkError($name) {
        // Ajoute la valeur d'erreur textuelle au champ en erreur
        return (isset($this->errors[$name])) ? $this->errors[$name] : false;
    }

    /**
     * Retourne la valeur du champ testé
     * @param String $name
     * @param Array $options
     */
    private function getValue($name, $options) {
        Log::setLog(print_r($options, true));
        // Ajoute la valeur de champ a value si elle exist
        if(!isset($this->request->data->$name) && isSet($options['value'])) {
            return $options['value'];
        } elseif(isset($this->request->data->$name)) {
            return clean($this->request->data->$name, 'str');
        }
    }

    /**
     * Retour le block help, avec l'erreur si elle existe
     */
    private function getHelp($name, $options){
        $error = $this->checkError($name);
        // Ajout d'un help block
        if(isSet($options['help']) && !$error) {
            return '<p class="help-'.self::errorDisplay.'">'.$options['help'].'</p>';
        } elseif($error) {
            return '<p class="help-'.self::errorDisplay.'">'.$error.'</p>';
        }
    }

    /**
     * Création d'un champ caché
     * @param String $name
     * @param Array $options
     */
    public function hidden($name, $options = array()) {
        $name = $this->clean($name);
        $value = $this->getValue($name, $options);
        // Cache un champ, si son label est hidden
        if (isSet($options['default']) && empty($value)){
            return '<input type="hidden" id="input'.$name.'" name="'.$name.'" value="'.$options['default'].'"'.$this->getAttribut($options).'>';
        } elseif(!empty($value)) {
            return '<input type="hidden" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$this->getAttribut($options).'>';
        }
    }

    /**
     * Creation d'un champ password, ne retiens pas la valeur
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function password($name, $label, $options = array()) {
        $classError = NULL;
        $error = $this->checkError($name);
        if($error) {
            $classError = ' error';
        }
        $html = '<div class="form-group control-group'.$classError.'">';
        $html .= '<label for="input'.$name.'" class="col-sm-2 control-label">'.$label.'</label>';

        // Ouverture de la structure interne au champ
        $html .= '<div  class="col-sm-10 controls">';
        $html .= '<input type="password" class="form-control" id="input'.$name.'" name="'.$name.'"'.$this->getAttribut($options).'>';
        $html .= $this->getHelp($name, $options);

        // Fermeture des division
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Creation d'un champ file
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function file($name, $label, $options = array()) {
        $classError = NULL;
        $error = $this->checkError($name);
        if($error) {
            $classError = ' error';
        }
        // Initialisations des attributs
        $attr = $this->getAttribut($options);

        $html = '<div class="form-group '.$classError.'">';
        $html .= '<label for="input'.$name.'" class="col-sm-2 control-label">'.$label.'</label>';

        $html .= '<div class="col-sm-10">';
        $html .= '<input type="file" class="form-control" id="input'.$name.'" name="'.$name.'"'.$attr.'>';
        $html .= $this->getHelp($name, $options);
        $html .= '</div>';

        // Fermeture des division
        $html .= '</div>';
        return $html;
    }

    /**
     * Creation de champ checkbox
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function checkbox($name, $label, $options) {
        $classError = NULL;
        $error = $this->checkError($name);
        if($error) {
            $classError = ' error';
        }
        $html = '<div class="form-group control-group'.$classError.'">';

        if (!empty($label)) {
            $html .= '<label for="input'.$name.'" class="col-sm-2 control-label">'.$label.'</label>';
        }

        // Ouverture de la structure interne au champ
        $css = (empty($label)) ? 'col-sm-offset-2 ' : '';
        $html .= '<div  class="'.$css.'col-sm-10">';

        $html .= '<input type="hidden" name="'.$name.'[]" value="0">';
        //$html .= '<ul class="inputs-list" id="input'.$name.'">';

        $options['options'] = (!isset($options['options'])) ? $options : $options['options'];
        foreach($options['options'] AS $k=>$v) {
            $sel = NULL;
            if (isSet($this->request->data->$name)) {
                foreach($this->request->data->$name AS $ke=>$va) {
                    if ($va == $k) {
                        $sel = 'checked="checked"';
                    }
                }
            }
            $html .= '<div class="checkbox">' .
                '<label for="'.clean($name . $k, 'slug').'">' .
                '<input type="checkbox" name="'.$name.'[]" id="'.clean($name . $k, 'slug').'" value="'.$k.'" '.$sel.'>' .
                $v.'</label>' .
                '</div>';
        }
        //$html .= '</ul>';

        $html .= $this->getHelp($name, $options);

        // Fermeture des division
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Creation de champ radio
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function radio($name, $label, $options) {
        $classError = NULL;
        $error = $this->checkError($name);
        if($error) {
            $classError = ' error';
        }
        $html = '<div class="control-group'.$classError.'">';
        $html .= '<label for="input'.$name.'">'.$label.'</label>';

        // Ouverture de la structure interne au champ
        $html .= '<div  class="controls">';
        $html .= '<ul class="inputs-list">';
        $value = $this->getValue($name, $options);
        foreach($options['options'] AS $k=>$v) {
            $sel = ($value === $k) ?'checked="checked"':'';
            $html .= '<li>' .
                '<label for="'.clean($name . $k, 'slug').'">'.
                '<input type="radio" name="'.$name.'" id="'.clean($name . $k, 'slug').'" value="'.$k.'" '.$sel.'> '.
                '<span>'.$v.'</span>'.
                '</label>' .
                '</li>';
        }
        $html .= '</ul>';
        $html .= $this->getHelp($name, $options);

        // Fermeture des division
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Creation de champs select
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function select($name, $label, $options) {
        // On permet l'envois d'information direct via un array
        $selectOptions = array();
        $selectOptions['options'] = (isset($options['options'])) ? $options['options'] : $options;
        $classError = NULL;
        $error = $this->checkError($name);
        if($error) {
            $classError = ' error';
        }
        $html = '<div class="form-group control-group'.$classError.'">';

        if (!empty($label)){
            $html .= '<label class="col-sm-2 control-label" for="input'.$name.'">'.$label.'</label>';
        }

        $cssCol = (!empty($label)) ? 'col-sm-10' : 'col-sm-12';

        // Ouverture de la structure interne au champ
        $html .= '<div class="'.$cssCol.' controls"><select class="form-control" id="input'.$name.'" name="'.$name.'" '.$this->getAttribut($selectOptions).'>';
        $value = $this->getValue($name, $selectOptions);
        foreach($selectOptions['options'] as $k=>$v) {
            $html .= '<option value="'.$k.'" '.($k==$value?'selected':'').'>'.$v.'</option>';
        }
        $html .= '</select></div>';

        $html .= $this->getHelp($name, $options);

        // Fermeture des division
        $html .= '</div>';
        return $html;
    }


    /**
     * Creation d'un bouton simple
     * @param String $name
     * @param String $label
     * @param Array $options
     */
    public function button($name, $label, $options = array()) {
        $type = (isset($options['type'])) ? $options['type'] : 'button';
        $type = $this->clean($type);
        $html=null;
        if (isset($options['onclick']) && isset($options['class'])) {
            $html .= '<button type="'.$type.'" id="input'.$name.'" name="'.$name.'" class="'.$options['class'].'"  onclick="'.$options['onclick'].'"';
            unset($options['onclick']); unset($options['class']);
        } elseif (isset($options['onclick']) && !isset($options['class'])) {
            $html .= '<button type="'.$type.'" id="input'.$name.'" name="'.$name.'" class="btn" onclick="'.$options['onclick'].'"';
            unset($options['onclick']);
        } elseif (!isset($options['onclick']) && isset($options['class'])) {
            $html .= '<button type="'.$type.'" id="input'.$name.'" name="'.$name.'" class="'.$options['class'].'"';
            unset($options['class']);
        } else {
            $html .= '<button type="'.$type.'" id="input'.$name.'" name="'.$name.'" class="btn btn-primary"';
        }
        $html .= $this->getAttribut($options).'>'.$label.'</button>';
        return $html;
    }

    /**
     * Création d'un bouton submit
     */
    public function submit($name, $label, $options = array()) {
        $name = $this->clean($name);

        $html = '<div class="control-group">';
        $html .= '<div class="controls">';
        if (isset($options['prepend'])) {
            $html .= $options['prepend']. ' ';
        }

        $options['type'] = 'submit';
        $html .= $this->button($name, $label, $options);

        if (isset($options['append'])) {
            $html .= $options['append']. ' ';
        }

        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Création d'un input
     * @param $name champ name
     * @param $label affiche devant le champ
     * @param array $options
     * @return string
     * @throws Exception
     */
    public function input($name,$label,$options = array()) {
        $error = false;
        $classError = '';
        $name = trim($name);
        $request = Request::getInstance();

        //Changement pour le systeme d'option
        if ( isSet($options['option']) ) {
            $options['options'] = $options['option'];
            unset($options['option']);
            if (!is_array($options['options'])) {
                throw new Exception("Valeur de array(option) n'est pas un array()", 1);
            }
        }

        // Ajoute la valeur d'erreur textuelle au champ en erreur
        if(isset($this->errors[$name])) {
            $error = $this->errors[$name];
            $classError = ' has-error';
        }

        // Ajoute la valeur de champ a value si elle exist
        $value = $this->getValue($name, $options);

        // Cache un champ, si son label est hidden
        if( $label == 'hidden' OR (isset($options['type']) && $options['type'] == 'hidden')) {
            return $this->hidden($name, $options);
        }

        if (isset($options['offset'])) {
            // Ouverture de la balise div pour la structure
            $html = '<div class="input-'.$options['offset'].$classError.'">';
        } else {
            // Ouverture de la balise div pour la structure
            // .form-group bootstrap 3
            // .control-group bootstrap 2
            $html = '<div class="form-group control-group'.$classError.'">';
        }


        /**
         * Site l'option type n'est pas d�fini ou est different de submit
         * On l'affiche
         */
        if ( (!isSet($options['type']) OR $options['type'] != 'submit') AND !empty($label) ) {
            // .col-sm-2 bootstrap 3
            $html .= '<label class="col-sm-2 control-label" for="input'.$name.'">'.$label.'</label>';
        }


        // Ouverture de la structure interne au champ
        // .col-sm-10 bootstrap 3
        $cssCol = (!empty($label)) ? 'col-sm-10' : 'col-sm-12';
        $html .= '<div class="'.$cssCol.' controls">';


        // Initialisations des attributs
        $attr = $this->getAttribut($options);


        /**
         * Aucun type defini et aucune option on affiche la forme standard
         */
        if(!isset($options['type']) && !isset($options['options'])) {
            if (isSet($options['addon'])) {
                $html .= '<div class="input-prepend">
					<span class="add-on">'.$options['addon'].'</span>';
                $html .= '<input type="text" class="form-control" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
                $html .= '</div>';
            } else {
                $html .= '<input type="text" class="form-control" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
            }
        } elseif($options['type'] == 'select') { // Un champ d'option est d�fini on le traite
            $html .= '<select id="input'.$name.'" name="'.$name.'" '.$attr.'>';
            foreach($options['options'] as $k=>$v) {
                $html .= '<option value="'.$k.'" '.($k==$value?'selected':'').'>'.$v.'</option>';
            }
            $html .= '</select>';
        } elseif($options['type'] == 'textarea') { // Le champ type d�fini un textarea
            // On demand l'editeur visuel
            if (isSet($options['editor'])) {
                $html .= $this->editor($options, $name, $value);
            } else { // On demand le textarea sans l'editeur visuel
                $html .= '<textarea id="input'.$name.'" class="form-control" name="'.$name.'" '.$attr.'>'.$value.'</textarea>';
            }
        } elseif($options['type'] == 'checkbox') { // CheckBox
            $html .= '<input type="hidden" name="'.$name.'[]" value="0">';
            $html .= '<ul class="inputs-list" id="input'.$name.'">';
            foreach($options['options'] AS $k=>$v) {
                $sel = NULL;
                if (isSet($request->data->$name)) {
                    foreach($request->data->$name AS $ke=>$va) {
                        if ($va == $k) {
                            $sel = 'checked="checked"';
                        }
                    }
                }
                $html .= '<li>
			 <input type="checkbox" name="'.$name.'[]" id="'.clean($name . $k, 'slug').'" value="'.$k.'" '.$sel.'>
                  <label class="control-label">'.$value.'
                  </label>
                </li>';
            }
            $html .= '</ul><span>'.$v.'</span>';
        } elseif($options['type'] == 'radio') { // Radip
            $html .= '<ul class="inputs-list">';
            foreach($options['options'] AS $k=>$v) {
                $sel = ($value === $k) ?'checked="checked"':'';
                $html .= '<li>
                  <labe class="control-label"><input type="radio" name="'.$name.'" id="'.clean($name . $k, 'slug').'" value="'.$k.'" '.$sel.'> <span>'.$v.'</span></label>
                </li>';
            }
            $html .= '</ul>';
        } elseif($options['type'] == 'file') { // Fichier
            $html .= '<input type="file" class="input-file" id="input'.$name.'" name="'.$name.'"'.$attr.'>';
        } elseif($options['type'] == 'password') { // Mot de passe
            $html .= '<input type="password" id="input'.$name.'" name="'.$name.'" value="'.$value.'"'.$attr.'>';
        } elseif($options['type'] == 'submit') { // Action submit
            return $this->submit($name, $label,$options);//'<div class="actions"><input type="submit" id="input'.$name.'" value="'.$label.'"'.$attr.' onclick="this.disabled=1; this.form.submit();"></div>';
        } elseif($options['type'] == 'date') { // Action submit
            $html .= $this->date($value, $name);
        }

        // Ajout d'un help block
        if(isSet($options['help']) && !$error) {
            $html .= '<span class="help-block">'.$options['help'].'</span>';
        } elseif($error) {
            $html .= '<span class="help-'.self::errorDisplay.'">'.$error.'</span>';
        }

        // Fermeture des division
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }




    /**
     *
     * Dessin un editeur visuel CkEditor
     * @param array $options
     * @param string $name
     * @param string $value
     */
    private function editor($options, $name, $value = NULL) {
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
        if (!empty($value)) { $html .= '<textarea id="input'.$name.'" class="form-control" name="'.$name.'">'.$value.'</textarea>'; }
        // les champ editor et value sont vide, on affiche
        else { 	$html .= '<textarea id="input'.$name.'" class="form-control" name="'.$name.'"></textarea>'; }

        if ( !is_array($options['editor']) ) {
            switch($options['editor'])
            {
                case 'htmlfull':
                    $params = '{"toolbar":['.$document.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
                    break;
                case 'html':
                    $params = '{"toolbar":['.$documentNoSource.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
                    break;
                default:
                    return $this->xCodeEditor($options, $name, $value);
                    $params = '{"extraPlugins":"bbcode","toolbar":['.$bbcode.']}';
                    break;
            }
        } else {
            // A-t-on des paramettres ?
            if (isSet($options['editor']['params'])) {
                // On utilise un model ?
                if (!isSet($options['editor']['params']['model'])) {

                    if (isSet($options['editor']['params']['extraPlugins'])) {
                        $extraPlugins = $options['editor']['params']['extraPlugins'];
                    } else {
                        $extraPlugins = '"extraPlugins":"bbcode",';
                    }

                    /*
                    * http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Toolbar
                    */
                    if (isSet($options['editor']['params']['toolbar'])) {
                        $toolbar = $options['editor']['params']['toolbar'];
                    } else {
                        $toolbar = '"toolbar":[["Undo","Redo","-","Bold","Italic","Underline","Strike","FontSize"],["Image","Link","Unlink"],["Find","Replace","-","SelectAll","RemoveFormat","\/","BulletedList","-","Blockquote","TextColor","-","Smiley","-","Maximize"]]';
                    }
                    $params = '{'.$extraPlugins.''.$toolbar.'}';
                } else {

                    switch ($options['editor']['params']['model']) {
                        case 'htmlfull':
                            $params = '{"toolbar":['.$document.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
                            break;
                        case 'html':
                            $params = '{"toolbar":['.$documentNoSource.', '.$documentEdit.', '.$basic.', '.$paragraph.', '.$insert.', '.$link.', '.$color.', '.$style.']}';
                            break;
                        default:
                            return $this->xCodeEditor($options, $name, $value);
                            $params = '{"extraPlugins":"bbcode","toolbar":['.$bbcode.']}';
                            break;
                    }
                }

            } else {
                return $this->xCodeEditor($options, $name, $value);
                $params = '{"extraPlugins":"bbcode","toolbar":['.$bbcode.']}';
            }
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
		window.CKEDITOR_BASEPATH=\'/assets/plugins/ckeditor/\';
		</script>
		<script type="text/javascript" src="/assets/plugins/ckeditor/ckeditor.js?t=B5GJ5GG"></script>
		<script type="text/javascript">
		CKEDITOR.replace(\''.$name.'\', '.$params.');
		</script>';

        return $html;
    }


    private $xCodeReady = false;
    private function xCodeEditor($options, $name, $value) {
        if (!$this->xCodeReady) {
            $page = Page::getInstance();
            $page->setHeaderCss(__CW_PATH . '/assets/plugins/xcode/css/xcode.css');
            $page->setHeaderJs(__CW_PATH . '/assets/plugins/xcode/js/xcode.js?rev=1');
            $this->xCodeReady = true;
        }
        ob_start();
        ?>
        <div class="xCode">
            <div class="xCodeMenu">
                <div class="btn-toolbar" role="toolbar" style="margin: 0;">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default xCodeBtnUp" title="Agrandir l'éditeur"><i class="icon-plus fa fa-plus"></i></button>
                        <button type="button" class="btn btn-default xCodeBtnDown" title="Réduire l'éditeur"><i class="icon-minus fa fa-minus"></i></button>
                        <button type="button" class="btn btn-default xCodeBtnPreview" title="Aperçu"><i class="icon-refresh fa fa-refresh"></i></button>
                    </div>
                    <div class="btn-group xCodeBtnSelect">
                        <button type="button" data-tag="b" class="btn btn-default" title="Gras"><i class="icon-bold fa fa-bold"></i></button>
                        <button type="button" data-tag="i" class="btn btn-default" title="Italique"><i class="icon-italic fa fa-italic"></i></button>
                        <button type="button" data-tag="u" class="btn btn-default" title="Souligner"><i class="icon-underline fa fa-underline"></i></button>
                        <button type="button" data-tag="s" class="btn btn-default" title="Barrer"><i class="icon-strikethrough fa fa-strikethrough"></i></button>
                    </div>
                    <div class="btn-group xCodeBtnSelect">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-align-left fa fa-align-left"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="cursor: pointer;">
                                <li data-tag="position" data-value="left"><a><i class="icon-align-left fa fa-align-left"></i> Gauche</a></li>
                                <li data-tag="position" data-value="center"><a><i class="icon-align-center fa fa-align-center"></i> Centre</a></li>
                                <li data-tag="position" data-value="right"><a><i class="icon-align-right fa fa-align-right"></i> Droite</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-font fa fa-font"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="cursor: pointer;">
                                <li data-tag="police" data-value="arial black" style="font-family:'arial black';"><a>Arial Black</a></li>
                                <li data-tag="police" data-value="times new roman" style="font-family:'arial black';"><a>Times New Roman</a></li>
                                <li data-tag="police" data-value="comic sans ms" style="font-family:'arial black';"><a>Comic Sans Ms</a></li>
                                <li data-tag="police" data-value="courrier new" style="font-family:'courrier new';"><a>Courier New</a></li>
                                <li data-tag="police" data-value="impact" style="font-family:'impact';"><a>Impact</a></li>
                                <li data-tag="police" data-value="georgia" style="font-family:'georgia';"><a>Georgia</a></li>
                                <li data-tag="police" data-value="verdana" style="font-family:'verdana';"><a>Verdana</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-text-height fa fa-text-height"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="cursor: pointer;">
                                <li data-tag="taille" data-value="1"><a><font size="1">Très très petit</font></a></li>
                                <li data-tag="taille" data-value="2"><a><font size="2">Très petit</font></a></li>
                                <li data-tag="taille" data-value="3"><a><font size="3">Petit</font></a></li>
                                <li data-tag="taille" data-value="4"><a><font size="4">Grand</font></a></li>
                                <li data-tag="taille" data-value="5"><a><font size="5">Très grand</font></a></li>
                                <li data-tag="taille" data-value="6"><a><font size="6">Très très grand</font></a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-tint fa fa-tint"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" style="cursor: pointer;">
                                <li data-tag="color" data-value="maroon" style="background-color: maroon;"><a style="color: #fff;">Maron</a></li>
                                <li data-tag="color" data-value="red" style="background-color: red;"><a style="color: #fff;">Rouge</a></li>
                                <li data-tag="color" data-value="pink" style="background-color: pink;"><a style="color: #000;">Rose</a></li>
                                <li data-tag="color" data-value="orange" style="background-color: orange;"><a style="color: #fff;">Orange</a></li>
                                <li data-tag="color" data-value="yellow" style="background-color: yellow;"><a style="color: #fff;">Jaune</a></li>
                                <li data-tag="color" data-value="olive" style="background-color: olive;"><a style="color: #fff;">Olive</a></li>
                                <li data-tag="color" data-value="green" style="background-color: green;"><a style="color: #fff;">Vert</a></li>
                                <li data-tag="color" data-value="aqua" style="background-color: aqua;"><a style="color: #fff;">Turquoise</a></li>
                                <li data-tag="color" data-value="blue" style="background-color: blue;"><a style="color: #fff;">Bleu</a></li>
                                <li data-tag="color" data-value="white" style="background-color: white;"><a style="color: #000;">Blanc</a></li>
                                <li data-tag="color" data-value="grey" style="background-color: grey;"><a style="color: #fff;">Gris</a></li>
                                <li data-tag="color" data-value="black" style="background-color: black;"><a style="color: #fff;">Noir</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="btn-group xCodeBtnSelect">
                        <button type="button" onclick="insertTag('quote', 'quote', 'citation');" class="btn btn-default" title="Citation"><i class="icon-quote-right fa fa-quote-right"></i></button>
                        <button type="button" onclick="insertTag('url', 'url', 'lien');" class="btn btn-default" title="Lien"><i class="icon-link fa fa-link"></i></button>
                        <button type="button" onclick="img_ins();" class="btn btn-default" title="Image"><i class="icon-picture fa fa-picture-o"></i></button>
                        <button type="button" class="btn btn-default xCodeBtnUploadImage" title="Héberger une image"><i class="icon-download-alt fa fa-download"></i></button>
                        <button type="button" onclick="insertTag('youtube', 'youtube', 'youtube');" class="btn btn-default" title="Vidéo Youtube"><i class="icon-facetime-video fa fa-video-camera"></i></button>
                    </div>
                    <div class="btn-group">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-smile-o"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-smiley=":)"><img src="/assets/images/smiley/1.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley="'8)"><img src="/assets/images/smiley/2.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":D:"><img src="/assets/images/smiley/3.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":("><img src="/assets/images/smiley/4.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":P"><img src="/assets/images/smiley/5.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":calin:"><img src="/assets/images/smiley/6.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":'D"><img src="/assets/images/smiley/7.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley="o_O"><img src="/assets/images/smiley/8.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=";):"><img src="/assets/images/smiley/9.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley="X("><img src="/assets/images/smiley/10.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley="8D"><img src="/assets/images/smiley/11.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":$"><img src="/assets/images/smiley/12.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":satan:"><img src="/assets/images/smiley/13.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":crotte:"><img src="/assets/images/smiley/14.gif" alt="Inserer des emoticones"></a></li>
                                <li><a href="#" data-smiley=":magik:"><img src="/assets/images/smiley/15.gif" alt="Inserer des emoticones"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <textarea class="xCodeContent" placeholder="Corps du message" cols="30" rows="10" tabindex="30" id="inputcontent" name="<?php echo $name; ?>"><?php echo $value; ?></textarea>
            <div class="xCodePreview"></div>
            <div class="xCodeLoad" style="display: none;"><img style="margin-right: 10px;" src="http://img11.hostingpics.net/pics/397175load.gif">Chargement...</div>
        </div>
        <?php
        return ob_get_clean();
    }


    /**
     *
     * Cr�e un selecteur de date
     * @param string $value
     */
    private function date($value, $name) {
        $fr_month = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
            'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

        $cday = $name . '_day';
        $cmonth = $name . '_month';
        $cyear = $name . '_year';

        if (isSet($this->Request->data->$cday) AND isSet($this->Request->data->$cmonth) AND isSet($this->Request->data->$cyear)) {
            $value = $this->Request->data->$mconth . '-' . $this->Request->data->$cday . '-' . $this->Request->data->$cyear;
        }

        // Si on pas de valeur
        if (empty($value)) {
            $value = date('n-j-Y');
        }

        // On a recu, un timestamp
        if (is_numeric($value)) {
            $value = date('n-j-Y', $value);
        }

        // On a une date normalis�
        list($month, $day, $year) = explode('-', $value);


        // On a une erreur ;-)
        if (!checkdate($month, $day, $year)) {

            // L'ann�e
            if ( $year < (date('Y')-70) OR $year > (date('Y')+5) ) {
                $year = date('Y');
            }

            /**
             * Le jour
             */
            if (!is_int($day) OR $day < 1 OR $day > 31) {
                $day = date('j');
            }

            /**
             * Le mois
             */
            if (!is_int($month) OR $month < 1 OR $month > 12) {
                $month = date('n');
            }


            /**
             * V�rifie a nouveau si la date est correcte
             * En particulier pour f�vrier
             */
            if (!checkdate($month, $day, $year)) {
                // Recherche le nombre de jour dans le mois
                $nbDay = date('t',mktime(0,0,0,$month,1,$year));

                /**
                 * Si le jour est plus grand que le nombre de jour total
                 * On lui donne �a valeur maximal
                 */
                if ($day > $nbDay) {
                    $day = $nbDay;
                }

            }
        }




        // Jours
        $html = '<select class="form-control pull-left" name="'.$cday.'" id="input'.$cday.'" style="width: auto !important;">';
        for ($i=1; $i<=31; $i++) {
            $seleted = ($i == $day) ? ' selected="selected"' : '';
            $html .= '<option value="'.$i.'"'.$seleted.'>'.$i.'</option>';
        }
        $html .= '</select>';

        // Mois
        $html .= '<select class="form-control pull-left" name="' . $cmonth . '" id="input' . $cmonth . '" style="width: auto !important;">';
        for ($i=1; $i<=12; $i++) {
            $seleted = ($i == $month) ? ' selected="selected"' : '';
            $html .= '<option value="'.$i.'"'.$seleted.'>'.$fr_month[$i-1].'</option>';
        }
        $html .= '</select>';


        // Annee
        $html .= '<select class="form-control" name="' . $cyear . '" id="input' . $cyear . '" style="width: auto !important;">';
        for ($i=(date('Y')-70); $i<=(date('Y')+5); $i++) {
            $seleted = ($i == $year) ? ' selected="selected"' : '';
            $html .= '<option value="'.$i.'"'.$seleted.'>'.$i.'</option>';
        }
        $html .= '</select>';


        return $html;
    }


}