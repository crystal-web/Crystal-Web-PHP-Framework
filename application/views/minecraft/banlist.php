<style>

    .pagination {
        margin: 20px 0;
    }

    .pagination ul {
        display: inline-block;
        *display: inline;
        margin-bottom: 0;
        margin-left: 0;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        *zoom: 1;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .pagination ul > li {
        display: inline;
    }

    .pagination ul > li > a,
    .pagination ul > li > span {
        float: left;
        padding: 4px 12px;
        line-height: 20px;
        text-decoration: none;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        border-left-width: 0;
    }

    .pagination ul > li > a:hover,
    .pagination ul > li > a:focus,
    .pagination ul > .active > a,
    .pagination ul > .active > span {
        background-color: #f5f5f5;
    }

    .pagination ul > .active > a,
    .pagination ul > .active > span {
        color: #999999;
        cursor: default;
    }

    .pagination ul > .disabled > span,
    .pagination ul > .disabled > a,
    .pagination ul > .disabled > a:hover,
    .pagination ul > .disabled > a:focus {
        color: #999999;
        cursor: default;
        background-color: transparent;
    }

    .pagination ul > li:first-child > a,
    .pagination ul > li:first-child > span {
        border-left-width: 1px;
        -webkit-border-bottom-left-radius: 4px;
        border-bottom-left-radius: 4px;
        -webkit-border-top-left-radius: 4px;
        border-top-left-radius: 4px;
        -moz-border-radius-bottomleft: 4px;
        -moz-border-radius-topleft: 4px;
    }

    .pagination ul > li:last-child > a,
    .pagination ul > li:last-child > span {
        -webkit-border-top-right-radius: 4px;
        border-top-right-radius: 4px;
        -webkit-border-bottom-right-radius: 4px;
        border-bottom-right-radius: 4px;
        -moz-border-radius-topright: 4px;
        -moz-border-radius-bottomright: 4px;
    }

    .pagination-centered {
        text-align: center;
    }

    .pagination-right {
        text-align: right;
    }

    .pagination-large ul > li > a,
    .pagination-large ul > li > span {
        padding: 11px 19px;
        font-size: 17.5px;
    }

    .pagination-large ul > li:first-child > a,
    .pagination-large ul > li:first-child > span {
        -webkit-border-bottom-left-radius: 6px;
        border-bottom-left-radius: 6px;
        -webkit-border-top-left-radius: 6px;
        border-top-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        -moz-border-radius-topleft: 6px;
    }

    .pagination-large ul > li:last-child > a,
    .pagination-large ul > li:last-child > span {
        -webkit-border-top-right-radius: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        border-bottom-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        -moz-border-radius-bottomright: 6px;
    }

    .pagination-mini ul > li:first-child > a,
    .pagination-small ul > li:first-child > a,
    .pagination-mini ul > li:first-child > span,
    .pagination-small ul > li:first-child > span {
        -webkit-border-bottom-left-radius: 3px;
        border-bottom-left-radius: 3px;
        -webkit-border-top-left-radius: 3px;
        border-top-left-radius: 3px;
        -moz-border-radius-bottomleft: 3px;
        -moz-border-radius-topleft: 3px;
    }

    .pagination-mini ul > li:last-child > a,
    .pagination-small ul > li:last-child > a,
    .pagination-mini ul > li:last-child > span,
    .pagination-small ul > li:last-child > span {
        -webkit-border-top-right-radius: 3px;
        border-top-right-radius: 3px;
        -webkit-border-bottom-right-radius: 3px;
        border-bottom-right-radius: 3px;
        -moz-border-radius-topright: 3px;
        -moz-border-radius-bottomright: 3px;
    }

    .pagination-small ul > li > a,
    .pagination-small ul > li > span {
        padding: 2px 10px;
        font-size: 11.9px;
    }

    .pagination-mini ul > li > a,
    .pagination-mini ul > li > span {
        padding: 0 6px;
        font-size: 10.5px;
    }

</style>

<div class="well">
    <?php
    echo '<div class="pagination pagination-centered">
	<ul>
	<li><a href="' .Router::url('banned/slug:a'). '">A</a></li>
	<li><a href="' .Router::url('banned/slug:b'). '">B</a></li>
	<li><a href="' .Router::url('banned/slug:c'). '">C</a></li>
	<li><a href="' .Router::url('banned/slug:d'). '">D</a></li>
	<li><a href="' .Router::url('banned/slug:e'). '">E</a></li>
	<li><a href="' .Router::url('banned/slug:f'). '">F</a></li>
	<li><a href="' .Router::url('banned/slug:g'). '">G</a></li>
	<li><a href="' .Router::url('banned/slug:h'). '">H</a></li>
	<li><a href="' .Router::url('banned/slug:i'). '">I</a></li>
	<li><a href="' .Router::url('banned/slug:j'). '">J</a></li>
	<li><a href="' .Router::url('banned/slug:K'). '">K</a></li>
	<li><a href="' .Router::url('banned/slug:l'). '">L</a></li>
	<li><a href="' .Router::url('banned/slug:m'). '">M</a></li>
	<li><a href="' .Router::url('banned/slug:n'). '">N</a></li>
	<li><a href="' .Router::url('banned/slug:o'). '">O</a></li>
	<li><a href="' .Router::url('banned/slug:p'). '">P</a></li>
	<li><a href="' .Router::url('banned/slug:q'). '">Q</a></li>
	<li><a href="' .Router::url('banned/slug:r'). '">R</a></li>
	<li><a href="' .Router::url('banned/slug:s'). '">S</a></li>
	<li><a href="' .Router::url('banned/slug:t'). '">T</a></li>
	<li><a href="' .Router::url('banned/slug:u'). '">U</a></li>
	<li><a href="' .Router::url('banned/slug:v'). '">V</a></li>
	<li><a href="' .Router::url('banned/slug:w'). '">W</a></li>
	<li><a href="' .Router::url('banned/slug:x'). '">X</a></li>
	<li><a href="' .Router::url('banned/slug:y'). '">Y</a></li>
	<li><a href="' .Router::url('banned/slug:z'). '">Z</a></li>
	</ul>

	<ul>
	<li><a href="' .Router::url('banned'). '">Tout</a></li>
	<li><a href="' .Router::url('banned/slug:0'). '">0</a></li>
	<li><a href="' .Router::url('banned/slug:1'). '">1</a></li>
	<li><a href="' .Router::url('banned/slug:2'). '">2</a></li>
	<li><a href="' .Router::url('banned/slug:3'). '">3</a></li>
	<li><a href="' .Router::url('banned/slug:4'). '">4</a></li>
	<li><a href="' .Router::url('banned/slug:5'). '">5</a></li>
	<li><a href="' .Router::url('banned/slug:6'). '">6</a></li>
	<li><a href="' .Router::url('banned/slug:7'). '">7</a></li>
	<li><a href="' .Router::url('banned/slug:8'). '">8</a></li>
	<li><a href="' .Router::url('banned/slug:9'). '">9</a></li>
	<li><a href="' .Router::url('banned/slug:_'). '">_</a></li>
	</ul></div>';
    ?>
    <form name="find" class="form-inline" method="get" action="<?php echo Router::url('banned'); ?>">
        <div style="text-align: center;">
            <input type="text" size="30" name="q" id="inputString" AUTOCOMPLETE="off">
            <input type="submit" value="Rechercher" class="btn-u">
        </div>
    </form>
</div>
<?php


if (count($banList)) {
    echo '<table class="table table-striped table-bordered">';

    echo "<thead><tr style=\"font-weight: bold;\">
		<td>Joueur</td>
		<td>Raison</td>
		<td>Admin/Modo</td>
		<td>Date du bannissement</td>
		<td>D&eacute;bannissement dans</td>
		</tr></thead><tbody>";

    foreach($banList AS $k => $v) {
        echo "<tr>";
        echo '<td><a href="' . Router::url('member/index/slug:' . clean($v->nick, 'slug')) . '">'.
            clean($v->nick, 'slug').'</a></td>';
        echo '<td>'.$v->reason.'</td>';

        if ($v->adminnick == "*Console*") {
            echo '<td>Console</td>';
        } else {
            $v->adminnick = preg_replace('#(§[0-9a-zA-Z])#', '', $v->adminnick);
            $v->adminnick = explode(' ', $v->adminnick);
            echo '<td>
					<a href="' . Router::url('member/index/slug:' . clean($v->adminnick[count($v->adminnick)-1], 'slug')) . '">' .
                $v->adminnick[count($v->adminnick)-1] . '</a></td>';

        }


        echo "<td>".dates($v->banfrom, 'fr_date')."</td>";

        if ($v->status != '2') {
            if($v->banto == "0"){
                echo "<td>∞</td>";
            } elseif ($v->banto > time()) {
                $val = differenceTime($v->banto);
                if ($val) {
                    $val['hour'] = (int) $val['hour'];
                    $val['minute'] = (int) $val['minute'];
                    $val['day'] = (int) $val['day'];

                    $hs = ($val['hour'] >1) ? 's' : '';
                    $ms = ($val['minute'] >1) ? 's' : '';
                    $ds = ($val['day'] >1) ? 's' : '';


                    if ($val['day'] == 0) {
                        if ($val['hour'] == 0) {
                            echo '<td>' . $val['minute'] . ' Minute' . $ms . '</td>';
                        } else {
                            echo '<td>' . $val['hour'] . ' Heure' . $hs .' ' . $val['minute'] . ' Minute' . $ms . '</td>';
                        }
                    } else {
                        if ($val['hour'] == 0) {
                            echo '<td>' . $val['day'] . ' Jour' . $ds . ' ' . $val['minute'] . ' Minute' . $ms . '</td>';
                        } else {
                            echo '<td>' . $val['day'] . ' Jour' . $ds . ' ' . $val['hour'] . ' Heure' . $hs .' ' . $val['minute'] . ' Minute' . $ms . '</td>';
                        }
                    }
                }

            } else {
                echo "<td>D&eacute;bannis<!-- 1 --></td>";
            }
        } else {
            echo "<td>D&eacute;bannis<!-- 2 --></td>";
        }

        echo "</tr>";
    }

    echo "</tbody></table>";
}
echo pagination($nb_page);
?>