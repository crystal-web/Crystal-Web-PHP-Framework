<?php
/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Titre          : Tronquer une chaîne de caractères HTML                    */
/*                                                                            */
/* URL            : http://www.phpsources.org/scripts391-PHP.htm              */
/* Auteur         : forty                                                     */
/* Date édition   : 21 Mai 2008                                               */
/* Website auteur : http://www.toplien.fr/                                    */
/*                                                                            */
/******************************************************************************/


/*
 * Script base sur le parser html disponible ici :
 http://php-html.sourceforge.net/
 */

define ("NODE_TYPE_START",0);
define ("NODE_TYPE_ELEMENT",1);
define ("NODE_TYPE_ENDELEMENT",2);
define ("NODE_TYPE_TEXT",3);
define ("NODE_TYPE_COMMENT",4);
define ("NODE_TYPE_DONE",5);
define ("NODE_TYPE_ELEMENT_END",6);

/**
 * Class HtmlParser.
 * To use, create an instance of the class passing
 * HTML text. Then invoke parse() until it's false.
 * When parse() returns true, $iNodeType, $iNodeName
 * $iNodeValue and $iNodeAttributes are updated.
 *
 * To create an HtmlParser instance you may also
 * use convenience functions HtmlParser_ForFile
 * and HtmlParser_ForURL.
 */
class HtmlParser {

    /**
    * Field iNodeType.
    * May be one of the NODE_TYPE_* constants above.
    */
    var $iNodeType;

    /**
    * Field iNodeName.
    * For elements, it's the name of the element.
    */
    var $iNodeName = "";

    /**
    * Field iNodeValue.
    * For text nodes, it's the text.
    */
    var $iNodeValue = "";

    /**
    * Field iNodeAttributes.
    * A string-indexed array containing attribute values
    * of the current node. Indexes are always lowercase.
    */
    var $iNodeAttributes;

    /**
    * Field iNodeStart.
    * The position of the first char.
    */
    var $iNodeStart;

    /**
    * Field iNodeEnd.
    * The position of the last char.
    */
    var $iNodeEnd;

    // The following fields should be 
    // considered private:

    var $iHtmlText;
    var $iHtmlTextLength;
    var $iHtmlTextIndex = 0;
    var $iHtmlCurrentChar;
    var $BOE_ARRAY;
    var $B_ARRAY;
    var $BOS_ARRAY;

    var $no_comment = false;

    //Liste des balises autofermantes
    var $BalisesSimples = array('hr', 'br', 'input', 'meta', 'link', 'img', 
'area', 'param');

    /**
    * Constructor.
    * Constructs an HtmlParser instance with
    * the HTML text given.
    */
    function HtmlParser ($aHtmlText) {
        $this->iHtmlText = $aHtmlText;
        $this->iHtmlTextLength = strlen($aHtmlText);
        $this->iNodeAttributes = array();
        $this->setTextIndex (0);

        $this->BOE_ARRAY = array (" ", "\t", "\r", "\n", "=" );
        $this->B_ARRAY = array (" ", "\t", "\r", "\n" );
        $this->BOS_ARRAY = array (" ", "\t", "\r", "\n", "/" );
    }

    /**
    * Method parse.
    * Parses the next node. Returns false only if
    * the end of the HTML text has been reached.
    * Updates values of iNode* fields.
    */
    function parse() {
        $this->iNodeStart = $this->iHtmlTextIndex;
        $text = $this->skipToElement();
        if ($text != "") {
            $this->iNodeType = NODE_TYPE_TEXT;
            $this->iNodeName = "Text";
            $this->iNodeValue = $text;
            $this->iNodeEnd = $this->iHtmlTextIndex;
            return true;
        }
        return $this->readTag();
    }

    function clearAttributes() {
        $this->iNodeAttributes = array();
    }

    function readTag() {
        if ($this->iCurrentChar != "<") {
            $this->iNodeType = NODE_TYPE_DONE;
            return false;
        }
        $this->clearAttributes();
        $this->skipMaxInTag ("<", 1);
        if ($this->iCurrentChar == '/') {
            $this->moveNext();
            $name = $this->skipToBlanksInTag();
            if (strtolower($name) == 'script') {
                $this->no_comment = false;
            }
            $this->iNodeType = NODE_TYPE_ENDELEMENT;
            $this->iNodeName = $name;
            $this->iNodeValue = "";
            $this->skipEndOfTag();
            $this->iNodeEnd = $this->iHtmlTextIndex;
            return true;
        }
        $name = $this->skipToBlanksOrSlashInTag();
        if (!$this->isValidTagIdentifier ($name)) {
            $comment = false;
            if ((strpos($name, "!--") === 0) && (!$this->no_comment)) {
                $ppos = strpos($name, "--", 3);
                if (strpos($name, "--", 3) === (strlen($name) - 2)) {
                    $this->iNodeType = NODE_TYPE_COMMENT;
                    $this->iNodeName = "Comment";
                    $this->iNodeValue = "<" . $name . ">";
                    $comment = true;
                } else {
                    $rest = $this->skipToStringInTag ("-->");
                    if ($rest != "") {
                        $this->iNodeType = NODE_TYPE_COMMENT;
                        $this->iNodeName = "Comment";
                        $this->iNodeValue = "<" . $name . $rest;
                        $comment = true;
                        // Already skipped end of tag
                        $this->iNodeEnd = $this->iHtmlTextIndex;
                        return true;
                    }
                }
            }
            if (!$comment) {
                $this->iNodeType = NODE_TYPE_TEXT;
                $this->iNodeName = "Text";
                $this->iNodeValue = "<" . $name;
                $this->iNodeEnd = $this->iHtmlTextIndex;
                return true;
            }
        } else {
            if (strtolower($name) == 'script') {
                $this->no_comment = true;
            }
            $this->iNodeType = NODE_TYPE_ELEMENT;
            $this->iNodeValue = "";
            $this->iNodeName = $name;
            while ($this->skipBlanksInTag()) {
                $attrName = $this->skipToBlanksOrEqualsInTag();
                if ($attrName != "" && $attrName != "/") {
                    $this->skipBlanksInTag();
                    if ($this->iCurrentChar == "=") {
                        $this->skipEqualsInTag();
                        $this->skipBlanksInTag();
                        $value = $this->readValueInTag();
                        $this->iNodeAttributes[strtolower($attrName)] = $value;
                    } else {
                        $this->iNodeAttributes[strtolower($attrName)] = "";
                        $this->setTextIndex ($this->iHtmlTextIndex - 1);
                    }
                }
            }
        }
        if (($this->iHtmlText{$this->iHtmlTextIndex - 1} == '/') || (in_array(
$this->iNodeName, $this->BalisesSimples))) {
            $this->iNodeType = NODE_TYPE_ELEMENT_END;
        }
        $this->skipEndOfTag();
        $this->iNodeEnd = $this->iHtmlTextIndex;
        return true;
    }

    function isValidTagIdentifier ($name) {
        return preg_match("#^[A-Za-z0-9_\\-]+$#i", $name);
    }

    function skipBlanksInTag() {
        return "" != ($this->skipInTag ($this->B_ARRAY));
    }

    function skipToBlanksOrEqualsInTag() {
    return $this->skipToInTag ($this->BOE_ARRAY);
    }

    function skipToBlanksInTag() {
        return $this->skipToInTag ($this->B_ARRAY);
    }

    function skipToBlanksOrSlashInTag() {
        return $this->skipToInTag ($this->BOS_ARRAY);
    }

    function skipEqualsInTag() {
        return $this->skipMaxInTag ("=", 1);
    }

    function readValueInTag() {
        $ch = $this->iCurrentChar;
        $value = "";
        if ($ch == "\"") {
            $this->skipMaxInTag ("\"", 1);
            $value = $this->skipToInTag ("\"");
            $this->skipMaxInTag ("\"", 1);
        } elseif ($ch == "'") {
            $this->skipMaxInTag ("'", 1);
            $value = $this->skipToInTag ("'");
            $this->skipMaxInTag ("'", 1);
        } else {
            $value = $this->skipToBlanksInTag();
        }
        return $value;
    }

    function setTextIndex ($index) {
        $this->iHtmlTextIndex = $index;
        if ($index >= $this->iHtmlTextLength) {
            $this->iCurrentChar = -1;
        } else {
            $this->iCurrentChar = $this->iHtmlText{$index};
        }
    }

    function moveNext() {
        if ($this->iHtmlTextIndex < $this->iHtmlTextLength) {
            $this->setTextIndex ($this->iHtmlTextIndex + 1);
            return true;
        } else {
            return false;
        }
    }

    function skipEndOfTag() {
        while (($ch = $this->iCurrentChar) !== -1) {
            if ($ch == ">") {
            $this->moveNext();
            return;
            }
            $this->moveNext();
        }
    }

    function skipInTag ($chars) {
        $sb = "";
        while (($ch = $this->iCurrentChar) !== -1) {
            if ($ch == ">") {
                return $sb;
            } else {
                $match = false;
                for ($idx = 0; $idx < count($chars); $idx++) {
                    if ($ch == $chars[$idx]) {
                    $match = true;
                    break;
                    }
                }
                if (!$match) {
                    return $sb;
                }
                $sb .= $ch;
                $this->moveNext();
            }
        }
        return $sb;
    }

    function skipMaxInTag ($chars, $maxChars) {
        $sb = "";
        $count = 0;
        while (($ch = $this->iCurrentChar) !== -1 && $count++ < $maxChars) {
            if ($ch == ">") {
                return $sb;
            } else {
                $match = false;
                for ($idx = 0; $idx < count($chars); $idx++) {
                    if ($ch == $chars[$idx]) {
                    $match = true;
                    break;
                    }
                }
                if (!$match) {
                    return $sb;
                }
                $sb .= $ch;
                $this->moveNext();
            }
        }
        return $sb;
    }

    function skipToInTag ($chars) {
        $sb = "";
        while (($ch = $this->iCurrentChar) !== -1) {
            $match = $ch == ">";
            if (!$match) {
                for ($idx = 0; $idx < count($chars); $idx++) {
                    if ($ch == $chars[$idx]) {
                        $match = true;
                        break;
                    }
                }
            }
            if ($match) {
                return $sb;
            }
            $sb .= $ch;
            $this->moveNext();
        }
        return $sb;
    }

    function skipToElement() {
        $sb = "";
        while (($ch = $this->iCurrentChar) !== -1) {
            if ($ch == "<") {
                return $sb;
            }
            $sb .= $ch;
            $this->moveNext();
        }
        return $sb;
    }

    /**
    * Returns text between current position and $needle,
    * inclusive, or "" if not found. The current index is moved to a point
    * after the location of $needle, or not moved at all
    * if nothing is found.
    */
    function skipToStringInTag ($needle) {
        $pos = strpos ($this->iHtmlText, $needle, $this->iHtmlTextIndex);
        if ($pos === false) {
            return "";
        }
        $top = $pos + strlen($needle);
        $retvalue = substr ($this->iHtmlText, $this->iHtmlTextIndex, $top - 
$this->iHtmlTextIndex);
        $this->setTextIndex ($top);
        return $retvalue;
    }
}

function HtmlParser_ForFile ($fileName) { 
    return HtmlParser_ForURL($fileName);
}

function HtmlParser_ForURL ($url) {
    $fp = fopen ($url, "r");
    $content = "";
    while (true) {
        $data = fread ($fp, 8192);
        if (strlen($data) == 0) {
            break;
        }
        $content .= $data;
    }
    fclose ($fp);
    return new HtmlParser ($content);
}

function TronqueHtml($chaine, $max, $separateur = ' ', $suffix = ' ...') {
    if (strlen(strip_tags($chaine)) > $max) {
        $tabElements = array();
        $cur_len = 0;
        $parser = new HtmlParser($chaine);
        while ($parser->parse()) {
            if ($parser->iNodeType == NODE_TYPE_ELEMENT) {
                array_push($tabElements, $parser->iNodeName);
            } elseif ($parser->iNodeType == NODE_TYPE_ENDELEMENT) {
                while (array_pop($tabElements) != $parser->iNodeName) {
                    if (count($tabElements) < 1) {
                        echo 'Erreur : pas de balise ouvrante pour ' . $parser->
iNodeName;
                    }
                }
            } elseif ($parser->iNodeType == NODE_TYPE_TEXT) {
                $cur_max = $cur_len + $parser->iNodeEnd - $parser->iNodeStart;
                if ($cur_max == $max) {
                    $resultat = substr($chaine, 0, $parser->iNodeEnd) . $suffix;
                    while (($balise = array_pop($tabElements)) !== null) {
                        $resultat .= '</' . $balise . '>';
                    }
                    return $resultat;
                } elseif ($cur_max > $max) {
                    if (($pos = strrpos(substr($parser->iNodeValue, 0, ($max - 
$cur_len + strlen( $separateur ))), $separateur)) !== false) {
                        $resultat = substr($chaine, 0, $parser->iNodeStart + 
$pos) . $suffix;
                        while (($balise = array_pop($tabElements)) !== null) {
                            $resultat .= '</' . $balise . '>';
                        }
                        return $resultat;
                    } else {
                        $resultat = substr($chaine, 0, $parser->iNodeEnd) . 
$suffix;
                        while (($balise = array_pop($tabElements)) !== null) {
                            $resultat .= '</' . $balise . '>';
                        }
                        return $resultat;
                    }
                } else {
                    $cur_len += $parser->iNodeEnd - $parser->iNodeStart;
                }
            }
        }
    }
    return $chaine;
}
/*
$chaine = '<h2>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </h2>
<p>
<span class="Style1">Praesent tortor purus, <strong>commodo</strong> quis,' .
' interdum et, tincidunt ut, lacus.
Etiam condimentum volutpat dolor. Proin faucibus libero eu lectus.<br />
<em>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. <strong>Nam' .
' aliquam tellus eget ligula</strong>. Phasellus pretium neque ut felis.' .
' Quisque lacinia congue ante. Lorem ipsum dolor sit amet, consectetuer' .
' adipiscing elit. Quisque at metus quis tortor auctor faucibus.</em></span><b' .
'r />
Curabitur quis lectus. Integer felis est, congue id, luctus quis, congue' .
' volutpat, lacus. Curabitur malesuada felis semper nisl. Aenean laoreet. Nunc' .
' vitae nisi. <br />
</p>';
echo "Tronqué à 280 caractères (hors balises) :<br>\n";
echo TronqueHtml($chaine, 280, ' ', ' ...');
echo "\n<hr>\n";
echo $chaine;
*/
?>
