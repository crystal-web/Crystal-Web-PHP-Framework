<?php 
require 'tabs.inc.php';
?>
	<div class="widget">
		<div class="widget-header">
			<h3>Derniere inscription</h3>
		</div>
		<div class="widget-content">
			<table class="zebra-striped bordered-table condensed-table">
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Rang</th>
						<th>Mail</th>
						<th>Site web</th>
						<th>Enregistré</th>
						<th>Approbation</th>
						<th>Modifier</th>
					</tr>
				</thead>

				<tbody>
				<?php 
					for($i=0; $i<count($foundList); $i++)
					{
						$url = (!empty($foundList[$i]->website)) ? '<a href="' . $foundList[$i]->website . '">Site web</a>' : 'aucun';
						$approved = ($foundList[$i]->validemember == 'off') ? 'Non' : 'Oui'; 
						echo
						'<tr>	 			 	 
							<td><a href="' . Router::url('member/index/slug:' . clean($foundList[$i]->loginmember, 'slug') ) . '">' . clean($foundList[$i]->loginmember, 'slug') . '</a></td>
							<td>' . $foundList[$i]->groupmember . '</td>
							<td><a href="' . Router::url('membermanager/mail/id:' . $foundList[$i]->idmember) . '">E-Mail</a></td>
							<td>' . $url . '</td>
							<td>' . dates($foundList[$i]->firstactivitymember, 'fr_date') . '</td>
							<td>' .$approved . '</td>
							<td>';
							
							if (isSet($groupList[$foundList[$i]->groupmember])) { echo '<a href="' . Router::url('membermanager/edituser/id:' . $foundList[$i]->idmember) . '" class="btn success">Modifier</a>'; }
							else { echo '<a class="btn disabled">Modifier</a>'; }
						echo '</td></tr>';
					//	usleep(50); // On fait « dormir » le programme afin d'économiser l'utilisation du processeur.
					}
				?>

				</tbody>
			</table>
			
			
		</div>
	</div>