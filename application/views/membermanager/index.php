<?php 
require 'tabs.inc.php';
?>
	<div class="widget">
		<div class="widget-header">
			<h3>Derniere inscription</h3>
		</div>
		<div class="widget-content">
			<?php
				
				$login = $rang = 'asc';
				if ( isSet($this->mvc->Request->params['login']) )
				{
					$login = ( $this->mvc->Request->params['login'] == 'desc') ? 'asc' : 'desc';	
				}
				
				if ( isSet($this->mvc->Request->params['rang']) )
				{
					$rang = ( $this->mvc->Request->params['rang'] == 'desc') ? 'asc' : 'desc';	
				}
			?>

			<table class="zebra-striped bordered-table condensed-table">
				<thead>
					<tr>
						<th><a href="<?php echo Router::url('membermanager/order/login:' . $login); ?>">Pseudo</a></th>
						<th><a href="<?php echo Router::url('membermanager/order/rang:' . $rang); ?>">Rang</a></th>
						<th>Mail</th>
						<th>Site web</th>
						<th>Enregistré</th>
						<th>Approbation</th>
						<th>Modifier</th>
					</tr>
				</thead>

				<tbody>
				<?php 
					for($i=0; $i<count($lastMember); $i++)
					{
						$url = (!empty($lastMember[$i]->website)) ? '<a href="' . $lastMember[$i]->website . '">Site web</a>' : 'aucun';
						$approved = ($lastMember[$i]->validemember == 'off') ? 'Non' : 'Oui'; 
						echo
						'<tr>	 			 	 
							<td><a href="' . Router::url('member/index/slug:' . clean($lastMember[$i]->loginmember, 'slug') ) . '">' . clean($lastMember[$i]->loginmember, 'slug') . '</a></td>
							<td>' . $lastMember[$i]->groupmember . '</td>
							<td><a href="' . Router::url('membermanager/mail/id:' . $lastMember[$i]->idmember) . '">E-Mail</a></td>
							<td>' . $url . '</td>
							<td>' . dates($lastMember[$i]->firstactivitymember, 'fr_date') . '</td>
							<td>' .$approved . '</td>
							<td>';
							
							if (isSet($groupList[$lastMember[$i]->groupmember])) { echo '<a href="' . Router::url('membermanager/edituser/id:' . $lastMember[$i]->idmember) . '" class="btn success">Modifier</a>'; }
							else { echo '<a class="btn disabled">Modifier</a>'; }
						echo '</td></tr>';
					//	usleep(50); // On fait « dormir » le programme afin d'économiser l'utilisation du processeur.
					}
				?>

				</tbody>
			</table>
			
			
		</div>
	</div>