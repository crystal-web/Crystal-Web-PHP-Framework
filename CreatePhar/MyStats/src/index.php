<?php 
include_once 'Model/pluginMyStatsModel.php';
Class MyStatsPlugin extends PluginManager{

    public function onEnable() {
        $model = new pluginMyStatsModel();
        $model->install();
        $model->insertView();
    }
    
    
    public function onStatsShow() {
        echo '<div class="panel panel-default"><div class="panel-heading">Statistiques des visiteurs</div><div class="panel-body">';
        
        $model = new pluginMyStatsModel();
        //ETAPE 1 - Affichage du nombre de visites d'aujourd'hui
        
        //On compte le nombre d'entrées pour aujourd'hui
        $retour_count = $model->countTodayView();
        if (!isset($retour_count->visites)) {
            $retour_count = new stdClass();
            $retour_count->visites = 0;
        }
        echo 'Pages vues aujourd\'hui : <strong>' . $retour_count->visites . '</strong><br>'; // On affiche tout de suite pour pas le retaper 2 fois après    
        
    
        //ETAPE 2 - Record des connectés par jour
        $topOne = $model->countTopPageView(1);
        if (!$topOne) {
            $topOne = new stdClass();
            $topOne->date = date('Y-m-d');
            $topOne->visites = 1;
        }
        //On l'affiche ainsi que la date à laquelle le record a été établi
        list($year, $month, $day) = explode('-', $topOne->date);
        echo 'Record de visite : <strong>' . $topOne->visites . '</strong> établi le <strong>' . $day . '/' . $month . '/' . $year . '</strong><br>'; 
        
        // Moyenne journalière
        echo 'Moyenne : <strong>' . $model->avgView() . '</strong> visiteurs par jour<br>';

        $topOne = $model->countTopConnectes(1);
        if (!$topOne) { 
            $topOne = new stdClass();
            $topOne->date = date('Y-m-d');
            $topOne->top = 1;
        }
        //On l'affiche ainsi que la date à laquelle le record a été établi
        list($year, $month, $day) = explode('-', $topOne->date);
        echo 'Record de visiteur simultan&eacute; : <strong>' . $topOne->top . '</strong> établi le <strong>' . $day . '/' . $month . '/' . $year . '</strong><br>'; 
        // Moyenne journalière
        echo 'Moyenne : <strong>' . $model->avgConnectes() . '</strong> visiteurs<br>';

        
        // Affichage
        echo 'Visiteurs connectés : <strong>' . $model->connectes() . '</strong>';
        echo '</div></div>';
    }

    public function onStatsAdmin() {
        $model = new pluginMyStatsModel();
        $data = $model->getTopReferer();
        if (!$data) {return;}
        echo '<table class="table table-striped table-bordered table-hover"><thead><tr><th>#</th><th>Site web</th><th>Entrer</th></tr></thead>';
        for($i=0;$i<count($data);$i++) { ?>
                  <tr class="active">
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $data[$i]->domain; ?></td>
                    <td><?php echo $data[$i]->nb; ?></td>
                  </tr>
                  <?php
        }

        echo '</tbody></table>';
    }
}