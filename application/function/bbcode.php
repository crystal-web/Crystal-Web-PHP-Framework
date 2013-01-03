<?php
/*##################################################
 *                                Model.php
 *                            -------------------
 *   begin                : ???
 *   copyright            : (C) 2012 phpit.net
 *
###################################################
 * 
 * Based on http://www.phpit.net/article/create-bbcode-php/  
 * Modified by www.vision.to please keep credits, thank you.
 * Document your changes.
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

/**
 * Supprime le BBCode
 */
function stripBBcode($string)
{
	return preg_replace('#\[(.*)\](.*)\[(.*)\]#', '$2', $string);
}


/**
 * Convertis le BBCode alias de bbcode_format($str, $picSize)
 */
function bbcode($str, $picSize=NULL)
{
	return bbcode_format($str, $picSize);
}


/**
 * Convertis le BBCode
 */
function bbcode_format($str, $picSize=NULL)
{

	$picSize = (empty($picSize)) ? array('w' => 640,'h' => 480) : $picSize;


	
	// Liste a puce
    $str  = str_replace(
        array('[list]', '[/list]', '[*]'), 
        array('<ul>', '</li></ul>', '</li><li>'),
        $str);//*/
		
    $str = preg_replace('#<ul>(.*)</li>#isU', '<ul>', $str);	// On supprime le </li> au début


    $str = preg_replace('#<br>([\s]+)</li>#isU', '</li>', $str);
    $str = preg_replace('#<br>([\s]+)<ul>#isU', '<ul>', $str);
//(?:/embed/|/v/|/watch\?v=))([\w\-]{10,12})

   // $str = htmlentities($str, ENT_QUOTES, 'UTF-8');
	#\[link:(.*?)\]#
	#\[link:(.*?)=(.*?)\]#
	
    $simple_search = array(  
                //added line break  
                '/\[br\]/is',  
                '/\[b\](.*?)\[\/b\]/is',  
                '/\[i\](.*?)\[\/i\]/is',  
                '/\[u\](.*?)\[\/u\]/is',  
                '/\[url\=(.*?)\](.*?)\[\/url\]/is',  
                '/\[url\](.*?)\[\/url\]/is',  
                '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',  
                '/\[img\](.*?)\[\/img\]/is',  
                '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',  
                '/\[mail\](.*?)\[\/mail\]/is',  
                '/\[font\=(.*?)\](.*?)\[\/font\]/is',  
                '/\[size\=(.*?)\](.*?)\[\/size\]/is',  
                '/\[color\=(.*?)\](.*?)\[\/color\]/is',  
                  //added textarea for code presentation  
               '/\[codearea\](.*?)\[\/codearea\]/is',  
                 //added pre class for code presentation  
              '/\[code\](.*?)\[\/code\]/is',  
                //added paragraph  
              '/\[p\](.*?)\[\/p\]/is',  
                );  
  
    $simple_replace = array(  
				//added line break  
               '<br>',  
                '<strong>$1</strong>',  
                '<em>$1</em>',  
                '<u>$1</u>',  
				// added nofollow to prevent spam  
                '<a href="$1" rel="nofollow" title="$2 - $1" target="_blank">$2</a>',  
                '<a href="$1" rel="nofollow" title="$1" target="_blank">$1</a>',  
                '<div style="text-align: $1;">$2</div>',  
				//added alt attribute for validation  
                '<img src="$1" alt="" style="max-width: '.$picSize['w'].'px;max-height: '.$picSize['h'].'px;overflow: hidden;"/>',  
                '<a href="mailto:$1">$2</a>',  
                '<a href="mailto:$1">$1</a>',  
                '<span style="font-family: $1;">$2</span>',  
                '<span style="font-size: $1;">$2</span>',  
                '<span style="color: $1;">$2</span>',  
				//added textarea for code presentation  
				'<textarea class="code_container" rows="30" cols="70">$1</textarea>',  
				//added pre class for code presentation  
				'<pre class="code">$1</pre>',  
				//added paragraph  
				'<p>$1</p>',
                );  
  
    // Do simple BBCode's  
    $str = preg_replace ($simple_search, $simple_replace, $str);
	 
    // Do <blockquote> BBCode  
    $str = bbcode_quote ($str);
	
	$str = preg_replace_callback('#\[link:(.*?)\]#','viki', $str);
	
	$str = preg_replace_callback('#\[code=css](.+?)\[/code]#si','parse_css',$str);	
	$str = preg_replace_callback('#\[code=php](.+?)\[/code]#si','php_code',$str);
	
    $str = preg_replace('#(^|[\n ]|<a(.*?)>)http://(www\.)?(youtube\.com|youtu\.be)/(embed/|v/|watch\?v=)?([a-zA-Z0-9\-_]{10,12})(&feature=related?)?(.*)(\s|\n|\t)?(</a>)?#im','<div class="video"><object width="340" height="210">' . 
             '<param name="movie" value="http://www.youtube.com/v/$6?fs=1&ap=%2526fmt%3D18&autoplay=0&rel=0&fs=1&color1=0xffffff&color2=0xffffff&border=0&loop=0&showinfo=0"">' . 
             '</param><param name="allowFullScreen" value="true">' . 
             '</param><param name="allowscriptaccess" value="always">' . 
             '</param><embed src="http://www.youtube.com/v/$6?fs=1&ap=%2526fmt%3D18&autoplay=0&rel=0&fs=1&color1=0xffffff&color2=0xffffff&border=0&loop=0&showinfo=0"type="application/x-shockwave-flash" allowfullscreen="true" width="340" height="210"></embed></object></div>',$str);
		
	$str = nl2br($str);
	$str = preg_replace('#<br \/>.<br \/>#', '<br>', $str);
    return $str;  
}  
  

$__vikiLink = array();
function viki($match)
{
	global $__vikiLink;
	
	$exploded = explode('=', $match[1]);
	$exploded[0] = clean($exploded[0], 'slug');
	$__vikiLink[strtolower($exploded[0])] = 0;
	if (isSet($exploded[1]))
	{
		return '<a href="'.Router::url('viki/slug:' . $exploded[0]).'">'.$exploded[1].'</a>';
	}
	else
	{
		return '<a href="'.Router::url('viki/slug:' . $exploded[0]).'">'.$exploded[0].'</a>';
	}
}

/**
 * Convertion du code PHP en HTML
 */
function php_code($code){ //Colorisation de Code PHP
$str = highlight_string(html_entity_decode($code[1]), true);

    return '<br><strong>Script PHP :</strong><div class="scroll">' . highlight_string(html_entity_decode($code[1]), true) . '</div><br>';
}


/**
 * Convertion du CSS en HTML
 */
function parse_css($texte) {
	$texte[1] = html_entity_decode($texte[1], true);
	$texte[1] = preg_replace('`((.*)(:)(.*)(;))+`x','<span style="color: navy;">$2</span><span style="color: fuchsia;">$3</span><span style="color: blue;">$4</span><span style="color: fuchsia;">$5</span>', $texte[1]);
	$texte[1] = preg_replace('`((.*) \n? {)`x','<span style="color: fuchsia;">$1</span>', $texte[1]);
	$texte[1] = preg_replace('`(/\*(.*)\*/)`xs','<span style="color: gray;">$1</span>', $texte[1]);
	$texte[1] = str_replace('}','<span style="color: fuchsia;">}</span>', $texte[1]);
	$texte[1] = preg_replace('`<span style="color: (.*);">(.*)</span>`sU', '<span style="color:$1">$2</span>',$texte[1]);
	$texte[1] = str_replace('\n','', $texte[1]);
	$texte[1] = preg_replace('`(/\*\n?(.*)\n?\*/)`xsU','', $texte[1]);
	return '<br><strong>Code CSS</strong> :<br><div class="scroll">' . $texte[1] . '</div><br>';
}
 
 
/**
 * Quotation
 */
function bbcode_quote($str) {  
    //added div and class for quotes  
    $open = '<blockquote><div class="quote">';  
    $close = '</div></blockquote>';  
  
    // How often is the open tag?  
    preg_match_all ('/\[quote\]/i', $str, $matches);  
    $opentags = count($matches['0']);  
  
    // How often is the close tag?  
    preg_match_all ('/\[\/quote\]/i', $str, $matches);  
    $closetags = count($matches['0']);  
  
    // Check how many tags have been unclosed  
    // And add the unclosing tag at the end of the message  
    $unclosed = $opentags - $closetags;  
    for ($i = 0; $i < $unclosed; $i++) {  
        $str .= '</div></blockquote>';  
    }  
  
    // Do replacement  
    $str = str_replace ('[' . 'quote]', $open, $str);  
    $str = str_replace ('[/' . 'quote]', $close, $str);  
  
    return $str;  
}


/**
 * @deprecated
 */
function  bbcode_edit($name, $content=null)
{
$textarea = '<div id="nav_form" class="menu_form"> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_gras.png" width="20" height="20"  title="Texte gras" onClick="javascript:xCode(\'[g]\', \'[/g]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a><img src="' . __CW_PATH . '/files/bord/texte_italic.png" width="20" height="20"  title="Texte italique" onClick="javascript:xCode(\'[i]\', \'[/i]\');return(false)" /></a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_souligner.png" width="20" height="20" title="Texte souligner" onClick="javascript:xCode(\'[s]\', \'[/s]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_barrer.png" width="20" height="20" title="Texte barrer" onClick="javascript:xCode(\'[strike]\', \'[/strike]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/balise_sup.png" width="20" height="20"  title="Texte exposant" onClick="javascript:xCode(\'[sup]\', \'[/sup]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/balise_sub.png" width="20" height="20" title="Texte indice" onClick="javascript:xCode(\'[sub]\', \'[/sub]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_gauche.png" width="20" height="20" title="Texte a gauche" onClick="javascript:xCode(\'[align=left]\', \'[/align]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_centrer.png" width="20" height="20" title="Texte centrer" onClick="javascript:xCode(\'[align=center]\', \'[/align]\');return(false)" /> 
</a></li> 
</ul> 
<ul> 
<li><a> 
<img src="' . __CW_PATH . '/files/bord/texte_droite.png" width="20" height="20" title="Texte a droite" onClick="javascript:xCode(\'[align=right]\', \'[/align]\');return(false)" /> 
</a></li> 
</ul> 
 
 
 
<!-- Menu style texte --> 
<ul> <li><a><img src="' . __CW_PATH . '/files/bord/balise_font.png" width="20" height="20"  title="Style textes" /><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]--> <ul> <li><a class="arial" onClick="javascript:xCode(\'[font=Arial]\', \'[/font]\');return(false)">Arial</a></li> <li><a class="arialblack" onClick="javascript:xCode(\'[font=Arial Black]\', \'[/font]\');return(false)">Arial Black</a></li> <li><a class="comic" onClick="javascript:xCode(\'[font=Comic Sans MS]\', \'[/font]\');return(false)">Comic Sans MS</a></li> <li><a class="courier" onClick="javascript:xCode(\'[font=Courier New]\', \'[/font]\');return(false)">Courier New</a></li> <li><a class="georgia" onClick="javascript:xCode(\'[font=Georgia]\', \'[/font]\');return(false)">Georgia</a></li> <li><a class="impact" onClick="javascript:xCode(\'[font=Impact]\', \'[/font]\');return(false)">Impact</a></li> <li><a class="times" onClick="javascript:xCode(\'[font=Times New Roman]\', \'[/font]\');return(false)">Times New Roman</a></li> <li><a class="trebuchet" onClick="javascript:xCode(\'[font=Trebuchet MS]\', \'[/font]\');return(false)">Trebuchet MS</a></li> <li><a class="verdana" onClick="javascript:xCode(\'[font=Verdana]\', \'[/font]\');return(false)">Verdana</a></li> </ul> <!--[if lte IE 6]></td></tr></table></a><![endif]--> </li> </ul> 
 
<!-- Menu taile texte --> 
<ul> <li><a><img src="' . __CW_PATH . '/files/bord/balise_font_size.png" width="20" height="20"  title="Taile du textes" /><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]--> <ul> <li><a><span style="font-size: 8px;" onClick="javascript:xCode(\'[size=8]\', \'[/size]\');return(false)">Petit</span></a></li> <li><a><span style="font-size: 10px;" onClick="javascript:xCode(\'[size=10]\', \'[/size]\');return(false)">Moyen</span></a></li> <li><a><span style="font-size: 12px;" onClick="javascript:xCode(\'[size=12]\', \'[/size]\');return(false)">Normal</span></a></li> <li><a><span style="font-size: 14px;" onClick="javascript:xCode(\'[size=14]\', \'[/size]\');return(false)">Grand</span></a></li> <li><a><span style="font-size: 16px;" onClick="javascript:xCode(\'[size=16]\', \'[/size]\');return(false)">Plus grand</span></a></li>  </ul> <!--[if lte IE 6]></td></tr></table></a><![endif]--> </li> </ul> 
 
<!-- Menu couleur texte --> 
<ul> <li><a><img src="' . __CW_PATH . '/files/bord/balise_font_color.png" width="20" height="20"  title="Couleur textes" /><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]--> <ul> <li><a onClick="javascript:xCode(\'[color=#FFFFFF]\', \'[/color]\');return(false)"><span style="background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Blanc</a></li> <li><a onClick="javascript:xCode(\'[color=#000000]\', \'[/color]\');return(false)"><span style="background-color:#000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Noir</a></li> <li><a onClick="javascript:xCode(\'[color=#C0C0C0]\', \'[/color]\');return(false)"><span style="background-color:#C0C0C0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Gris</a></li> <li><a onClick="javascript:xCode(\'[color=#808080]\', \'[/color]\');return(false)"><span style="background-color:#808080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Gris fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#0000FF]\', \'[/color]\');return(false)"><span style="background-color:#0000FF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Bleu</a></li> <li><a onClick="javascript:xCode(\'[color=#000080]\', \'[/color]\');return(false)"><span style="background-color:#000080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Bleu fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#FF00FF]\', \'[/color]\');return(false)"><span style="background-color:#FF00FF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Violet</a></li> <li><a onClick="javascript:xCode(\'[color=#800080]\', \'[/color]\');return(false)"><span style="background-color:#800080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Violet fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#00FF00]\', \'[/color]\');return(false)"><span style="background-color:#00FF00;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Vert</a></li> <li><a onClick="javascript:xCode(\'[color=#008000]\', \'[/color]\');return(false)"><span style="background-color:#008000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Vert fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#00FFFF]\', \'[/color]\');return(false)"><span style="background-color:#00FFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Turquoise</a></li> <li><a onClick="javascript:xCode(\'[color=#008080]\', \'[/color]\');return(false)"><span style="background-color:#008080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Turquoise fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#FF0000]\', \'[/color]\');return(false)"><span style="background-color:#FF0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Rouge</a></li> <li><a onClick="javascript:xCode(\'[color=#800000]\', \'[/color]\');return(false)"><span style="background-color:#800000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Rouge fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[color=#808000]\', \'[/color]\');return(false)"><span style="background-color:#808000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Kaki</a></li> <li><a onClick="javascript:xCode(\'[color=#FFFF00]\', \'[/color]\');return(false)"><span style="background-color:#FFFF00;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Jaune</a></li> </ul> <!--[if lte IE 6]></td></tr></table></a><![endif]--> </li> </ul> 
<!-- Menu couleur fond texte --> 
<ul><li><a><img src="' . __CW_PATH . '/files/bord/balise_bgcolor_color.png" width="20" height="20"  title="Couleur fond" /><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]--> <ul> <li><a onClick="javascript:xCode(\'[bgcolor=#FFFFFF]\', \'[/bgcolor]\');return(false)"><span style="background-color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Blanc</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#000000]\', \'[/bgcolor]\');return(false)"><span style="background-color:#000000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Noir</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#C0C0C0]\', \'[/bgcolor]\');return(false)"><span style="background-color:#C0C0C0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Gris</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#808080]\', \'[/bgcolor]\');return(false)"><span style="background-color:#808080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Gris fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#0000FF]\', \'[/bgcolor]\');return(false)"><span style="background-color:#0000FF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Bleu</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#000080]\', \'[/bgcolor]\');return(false)"><span style="background-color:#000080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Bleu fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#FF00FF]\', \'[/bgcolor]\');return(false)"><span style="background-color:#FF00FF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Violet</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#800080]\', \'[/bgcolor]\');return(false)"><span style="background-color:#800080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Violet fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#00FF00]\', \'[/bgcolor]\');return(false)"><span style="background-color:#00FF00;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Vert</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#008000]\', \'[/bgcolor]\');return(false)"><span style="background-color:#008000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Vert fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#00FFFF]\', \'[/bgcolor]\');return(false)"><span style="background-color:#00FFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Turquoise</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#008080]\', \'[/bgcolor]\');return(false)"><span style="background-color:#008080;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Turquoise fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#FF0000]\', \'[/bgcolor]\');return(false)"><span style="background-color:#FF0000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Rouge</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#800000]\', \'[/bgcolor]\');return(false)"><span style="background-color:#800000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Rouge fonc&eacute;</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#808000]\', \'[/bgcolor]\');return(false)"><span style="background-color:#808000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Kaki</a></li> <li><a onClick="javascript:xCode(\'[bgcolor=#FFFF00]\', \'[/bgcolor]\');return(false)"><span style="background-color:#FFFF00;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;Jaune</a></li> </ul><!--[if lte IE 6]></td></tr></table></a><![endif]--></li></ul>
<ul><li><a><img src="' . __CW_PATH . '/files/bord/texte_lien.png" width="20" height="20"  title="Lien direct" onClick="javascript:urlis();return(false)" /> 
</a></li></ul><ul><li><a><img src="' . __CW_PATH . '/files/bord/balise_img.png" width="20" height="20" title="Inserez une image" onClick="javascript:image();return(false)" /><br></a></li></ul> 
<ul><li><a><img src="' . __CW_PATH . '/files/bord/image_upload.png" width="20" height="20" title="t&eacute;l&eacute;verser une image" onClick="window.open(\'popup_image_upload.htm\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=1, copyhistory=0, menuBar=0, width=280, height=70\');return(false)" /><br></a></li></ul> 
<!-- Menu video --> 
<ul><li><a><img src="' . __CW_PATH . '/files/bord/balise_video.png" width="20" height="20" title="Inserez une video" /><!--[if IE 7]><!--></a><!--<![endif]--> <!--[if lte IE 6]><table><tr><td><![endif]--> <ul> <li><a onClick="javascript:video(\'[youtube]\', \'[/youtube]\', \'http://www.youtube.com/watch.v=\', \'Un bouton partager contient le lien fonctionnel.\');return(false)"> <img src="' . __CW_PATH . '/files/bord/balise_video_youtube.png" width="20" height="20" title="Inserez une video YouTube" />&nbsp;YouTube</a></li> <li><a onClick="javascript:video(\'[dmotion]\', \'[/dmotion]\', \'http://www.dailymotion.com/video/\', \'Une zone de selection Permalink contient le lien fonctionnel. Arréter la selection juste avant le _ \');return(false)"> <img src="' . __CW_PATH . '/files/bord/balise_video_dailymotion.png" width="20" height="20" title="Inserez une video DailyMotion" />&nbsp;DailyMotion</a></li> <li><a onClick="javascript:video(\'[gvideo]\', \'[/gvideo]\', \'http://video.google.com/videoplay.docid=\', \'Copier l\'adresse de la page jusqu au #.\');return(false)"> <img src="' . __CW_PATH . '/files/bord/balise_video_google.png" width="20" height="20" title="Inserez une video DailyMotion" />&nbsp;GoogleVideo</a></li></ul><!--[if lte IE 6]></td></tr></table></a><![endif]--></li></ul>
</div>';

$textarea .= '<textarea name="' . $name . '" id="message" style="width: 100%;height: 250px;">' . PHP_EOL;
$textarea .= $content;
$textarea .= '</textarea>' . PHP_EOL;

return $textarea;
}
/* used in Vision.To CMS  
function VISION_TO_PAGE_CONTENT_PROCESSOR ($content)  
{  
$content=bbcode_format ($content);  
return $content;  
}  
*/  
/*Usage in CodeCharge Studio :  
before show event , the content_html  is label property as HTML   
$content=bbcode_format ($cms_pages->content_html->GetValue());  
$cms_pages->content_html->SetValue($content);  
*/  
?>