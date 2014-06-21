<?php
class labsController extends Controller {

    public function index(){
    ?>
        <link rel="stylesheet" href="/assets/plugins/xcode/css/xcode.css">
        <script type="text/javascript" src="/assets/plugins/xcode/js/xcode.js"></script>

        <div class="col-sm-12 controls">
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
                <textarea class="xCodeContent" placeholder="Corps du message" cols="30" rows="10" tabindex="30" id="inputcontent" name="content"></textarea>
                <div class="xCodePreview"></div>
                <div class="xCodeLoad" style="display: none;"><img style="margin-right: 10px;" src="http://img11.hostingpics.net/pics/397175load.gif">Chargement...</div>
            </div>
        </div>

    <?php
    }
}