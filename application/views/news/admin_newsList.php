<script>
	$(function() {
		$("table#sortTable").tablesorter({ sortList: [[1,0]] });
	});
</script>

<table class="zebra-striped" id="sortTable">
<thead>
<tr>
	<th>#</th><th>Titre</th><th>Auteur</th><th>Date</th><th>Categorie</th><th>Hits</th><th>action</th>
</tr>
</thead>
<tbody>
<?php
foreach ($news AS $data)
{
$data['auteur'] = (!empty($data['auteur'])) ?$data['auteur'] : 'inconnu';
$data['titre'] = (!empty($data['titre'])) ? $data['titre']: 'sans titre';
$data['hit'] = (!empty($data['hit'])) ?$data['hit'] : '0';
$data['categorie'] = (!empty($data['categorie'])) ? $data['categorie']: 'sans cat&eacute;gorie';

echo '<tr>
	<td>
		'.$data['id'].'
	</td>
	<td>
		<a href="' . url('index.php?module=news&action=post&p=' . $data['id'] . '&'.urlencode($data['titre'])) . '">' . $data['titre'] . '</a></td><td>' . $data['auteur'] . '
	</td>
	<td title="' . getRelativeTime($data['date']) . '">
		' . date('d-m-Y H:i',$data['date']) . '</td><td>' . $data['auteur'] . '</td>
	<td>
		' . $data['hit'] . '
	</td>
	<td>
		<a href="index.php?module=admin_news&action=del&cmd_s=' . $data['id'] . '">
			<i class="icon-trash"></i>
		</a>
		<a href="index.php?module=admin_news&action=edit&cmd_e=' . $data['id'] . '">
			<i class="icon-pencil"></i>
		</a>
	</td>
</tr>';
}
?>
</tbody>
</table>

<?php 
$searchInThis = (isSet($_GET['cat'])) ? '&cat=' . $_GET['cat'] : NULL;
$thispage = (isSet($_GET['page'])) ? (int) $_GET['page'] : 1;

//Pour l'affichage, on centre la liste des pages
echo '<div class="clearfix pagination"><ul class="center">'; 

echo (($thispage-1) > 0) ? '<li class="prev"><a href="index.php?module=admin_news' . $searchInThis . '&page='.($thispage-1).'">&larr; Previous</a></li>' : '<li class="prev disabled"><a href="#">&larr; Previous</a></li>';

for($i=1; $i<=$nombreDePages; $i++) //On fait notre boucle
{
     //On va faire notre condition
     if($i==$thispage) //Si il s'agit de la page actuelle...
     {
         echo '<li class="active">
				<a href="#">[ '.$i.' ]</a>
			</li>'; 
     }	
     else //Sinon...
     {
          echo ' <li>
				<a href="index.php?module=admin_news' . $searchInThis . '&page='.$i.'">'.$i.'</a>
			</li>';
     }

}
echo (($thispage+1) <= $nombreDePages) ? '<li class="next"><a href="index.php?module=admin_news' . $searchInThis . '&page='.($thispage+1).'">Next &rarr;</a></li>': '<li class="next disabled"><a href="#">Next &rarr;</a></li>';

echo '</ul></div>';



?>
