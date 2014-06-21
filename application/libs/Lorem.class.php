<?php
class Lorem {
	
	/**
	 * function text(nombre de caractÃ¨res)
	 * Retourne un texte brut, sans html
	 * Appel : $lorem->text(250);
	 **/
	function text($width) {
		$lorem = "
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus dictum cursus ligula, eget pretium orci tincidunt vitae. Cras massa purus, aliquam at interdum eu, ornare facilisis diam. Quisque semper, diam id sodales accumsan, dolor mi dapibus libero, in vestibulum metus mauris sit amet orci. Phasellus dictum tortor facilisis dui tincidunt accumsan. Suspendisse varius arcu pretium tellus tempor varius eu non est. In ac justo quis leo pharetra accumsan ac ut lorem. Proin lacinia, nisi sit amet eleifend rutrum, sem turpis rutrum augue, a gravida lectus nibh sed eros. Nulla sagittis felis et mi mollis id feugiat risus ullamcorper. Cras facilisis, lacus sit amet accumsan pellentesque, nisl augue interdum nibh, id porta mi nisl vel enim. Sed sed urna libero. Donec auctor luctus erat, eu luctus massa condimentum id. Phasellus laoreet, leo quis dictum convallis, ligula libero gravida ipsum, at sagittis velit enim pellentesque ante. Integer dictum justo lobortis magna gravida blandit a at purus. Curabitur sed ipsum in est sagittis pellentesque vitae nec nisi.
			Quisque ac turpis at felis sagittis imperdiet sit amet quis dui. Curabitur faucibus ante ut velit laoreet id accumsan nulla tempor. Donec mollis, leo sit amet iaculis condimentum, odio turpis scelerisque nunc, id consectetur lacus mauris sed turpis. Quisque in sollicitudin turpis. Praesent non enim at leo varius interdum. Mauris a elit sit amet lorem pellentesque pulvinar a non leo. Praesent leo dolor, facilisis ac scelerisque vel, fermentum nec orci. Nulla vehicula lacinia massa, sed mollis sem fringilla nec. Curabitur malesuada justo vitae erat venenatis vitae scelerisque risus porta. Integer ac odio et mauris blandit scelerisque.
			Suspendisse semper, augue sed scelerisque convallis, magna elit rutrum enim, vitae condimentum arcu tortor eu tortor. Fusce orci lorem, ullamcorper ut porttitor ac, ornare faucibus risus. Aenean sapien tellus, sagittis id lobortis a, fermentum id ligula. Sed accumsan nisl nec turpis pretium varius. Aliquam tincidunt dignissim vestibulum. Aenean ultricies cursus porttitor. Aliquam odio odio, vehicula ut suscipit nec, iaculis a massa. Mauris ac felis id nisi pharetra sollicitudin vitae eu velit.
			Suspendisse nec dolor nisl, a tristique ipsum. Nullam semper consectetur ligula, vel vestibulum dolor dapibus ac. Nullam varius ante et orci luctus et volutpat nulla sagittis. Suspendisse potenti. Suspendisse potenti. Ut id nisi non turpis consequat viverra sit amet sed justo. Cras leo ipsum, iaculis sed viverra sed, posuere sit amet justo. Morbi interdum, dui ut ornare sollicitudin, ipsum massa lobortis sem, id tincidunt magna augue non ipsum. Fusce dignissim metus sed ipsum sodales pulvinar.
			In non felis eget orci bibendum sagittis quis vel neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque id massa massa, non semper risus. Vivamus porta suscipit justo, cursus pretium eros vehicula pulvinar. Praesent nec eleifend nibh. Praesent ac massa augue, nec porta mi. Sed euismod accumsan purus, pretium volutpat ipsum rutrum eu. Ut aliquam leo nec felis mollis lobortis. Aliquam fringilla purus in tellus vestibulum dapibus at sit amet nunc. Nam aliquet vestibulum turpis sed porta. Donec id arcu quis nulla blandit luctus. Duis adipiscing lacinia elit, non euismod mi adipiscing id. Suspendisse velit odio, iaculis nec malesuada vestibulum, bibendum ut ligula.
			Integer dapibus, quam et cursus rhoncus, lorem lectus viverra odio, et mollis mauris purus vitae mauris. Ut metus diam, condimentum at rutrum sit amet, bibendum et sapien. Phasellus arcu risus, mollis vel sagittis sagittis, gravida vel purus. Curabitur nec libero tellus, eget semper nisl. Etiam auctor orci at eros lobortis varius. Morbi malesuada viverra elit, vitae pellentesque augue pharetra at. Etiam consequat massa eu est rutrum cursus. Integer ultricies orci eu urna varius nec congue risus tempor. Integer eu lacus eget magna tristique tincidunt ut quis velit. Suspendisse ultrices, velit a pulvinar posuere, dolor urna tempor est, eget mollis quam magna eu augue. Praesent nisl est, viverra id malesuada porta, accumsan ac tellus. Quisque eget tortor in quam cursus varius eget quis augue. Quisque placerat ullamcorper sollicitudin. Nulla facilisi. Integer cursus volutpat convallis. Nullam at nunc a risus fermentum vestibulum.
			Nam convallis est id felis volutpat convallis. Pellentesque porttitor arcu at felis sagittis vulputate. Fusce magna dui, luctus ut rutrum quis, aliquet sed orci. Cras sit amet orci vitae nulla ultrices varius sit amet at erat. Mauris quis purus ut diam mollis tincidunt. Aliquam luctus nibh in turpis ultricies et facilisis mauris tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis sed ligula justo, ut dictum nunc. Proin eu neque lorem.
			Nunc ullamcorper accumsan quam vel vestibulum. Nulla vel velit turpis, quis varius mauris. Aliquam libero dui, adipiscing sit amet mollis vitae, sagittis ac leo. Vestibulum vestibulum, enim non tristique tristique, nulla tellus convallis tortor, id convallis dolor elit et diam. Etiam sagittis feugiat lectus, ac porta arcu porta in. Maecenas blandit consectetur sem nec viverra. Ut urna sapien, pellentesque et aliquam ut, auctor at justo. Sed risus tellus, aliquam non blandit ac, commodo eu tellus. Pellentesque at est arcu, ut scelerisque ligula. Cras non mollis justo. Aliquam laoreet sagittis congue. Nulla facilisi. Phasellus nec bibendum lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
			Quisque nunc sem, aliquam ac consequat eget, vulputate eu libero. Maecenas gravida iaculis elit, et hendrerit nibh tincidunt vitae. Proin congue justo et ante cursus ac dapibus erat tincidunt. Phasellus vestibulum nisi sit amet neque pellentesque non vehicula est ultrices. Nulla fringilla tincidunt turpis, eget sollicitudin metus interdum eleifend. Etiam dignissim lacinia arcu, nec viverra eros dignissim nec. Integer id nibh nulla, eu feugiat turpis. Nam consequat ornare consectetur. Donec et ipsum ante, in gravida libero.
			Pellentesque vitae nibh ac urna semper venenatis eu nec est. Vestibulum venenatis, sem ac ultrices varius, lectus lorem vulputate massa, ut suscipit ipsum ligula sed odio. Donec tincidunt quam ac tortor luctus rutrum. Etiam fermentum pretium dictum. Maecenas condimentum sem volutpat mauris volutpat nec aliquam velit egestas. Curabitur iaculis purus et nulla egestas fringilla. Nam a magna mi. Suspendisse potenti. Fusce et nisl a sapien dictum dictum quis sed erat. Ut nisl ante, fermentum ac volutpat eget, iaculis ut turpis. Suspendisse vitae condimentum neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis sagittis, purus et egestas faucibus, tortor mi luctus orci, vel tincidunt lacus dui ut nibh. Nunc adipiscing congue semper. Aliquam erat volutpat. Nullam feugiat elit elit, commodo tincidunt leo.
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus dictum cursus ligula, eget pretium orci tincidunt vitae. Cras massa purus, aliquam at interdum eu, ornare facilisis diam. Quisque semper, diam id sodales accumsan, dolor mi dapibus libero, in vestibulum metus mauris sit amet orci. Phasellus dictum tortor facilisis dui tincidunt accumsan. Suspendisse varius arcu pretium tellus tempor varius eu non est. In ac justo quis leo pharetra accumsan ac ut lorem. Proin lacinia, nisi sit amet eleifend rutrum, sem turpis rutrum augue, a gravida lectus nibh sed eros. Nulla sagittis felis et mi mollis id feugiat risus ullamcorper. Cras facilisis, lacus sit amet accumsan pellentesque, nisl augue interdum nibh, id porta mi nisl vel enim. Sed sed urna libero. Donec auctor luctus erat, eu luctus massa condimentum id. Phasellus laoreet, leo quis dictum convallis, ligula libero gravida ipsum, at sagittis velit enim pellentesque ante. Integer dictum justo lobortis magna gravida blandit a at purus. Curabitur sed ipsum in est sagittis pellentesque vitae nec nisi.
			Quisque ac turpis at felis sagittis imperdiet sit amet quis dui. Curabitur faucibus ante ut velit laoreet id accumsan nulla tempor. Donec mollis, leo sit amet iaculis condimentum, odio turpis scelerisque nunc, id consectetur lacus mauris sed turpis. Quisque in sollicitudin turpis. Praesent non enim at leo varius interdum. Mauris a elit sit amet lorem pellentesque pulvinar a non leo. Praesent leo dolor, facilisis ac scelerisque vel, fermentum nec orci. Nulla vehicula lacinia massa, sed mollis sem fringilla nec. Curabitur malesuada justo vitae erat venenatis vitae scelerisque risus porta. Integer ac odio et mauris blandit scelerisque.
			Suspendisse semper, augue sed scelerisque convallis, magna elit rutrum enim, vitae condimentum arcu tortor eu tortor. Fusce orci lorem, ullamcorper ut porttitor ac, ornare faucibus risus. Aenean sapien tellus, sagittis id lobortis a, fermentum id ligula. Sed accumsan nisl nec turpis pretium varius. Aliquam tincidunt dignissim vestibulum. Aenean ultricies cursus porttitor. Aliquam odio odio, vehicula ut suscipit nec, iaculis a massa. Mauris ac felis id nisi pharetra sollicitudin vitae eu velit.
			Suspendisse nec dolor nisl, a tristique ipsum. Nullam semper consectetur ligula, vel vestibulum dolor dapibus ac. Nullam varius ante et orci luctus et volutpat nulla sagittis. Suspendisse potenti. Suspendisse potenti. Ut id nisi non turpis consequat viverra sit amet sed justo. Cras leo ipsum, iaculis sed viverra sed, posuere sit amet justo. Morbi interdum, dui ut ornare sollicitudin, ipsum massa lobortis sem, id tincidunt magna augue non ipsum. Fusce dignissim metus sed ipsum sodales pulvinar.
			In non felis eget orci bibendum sagittis quis vel neque. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Quisque id massa massa, non semper risus. Vivamus porta suscipit justo, cursus pretium eros vehicula pulvinar. Praesent nec eleifend nibh. Praesent ac massa augue, nec porta mi. Sed euismod accumsan purus, pretium volutpat ipsum rutrum eu. Ut aliquam leo nec felis mollis lobortis. Aliquam fringilla purus in tellus vestibulum dapibus at sit amet nunc. Nam aliquet vestibulum turpis sed porta. Donec id arcu quis nulla blandit luctus. Duis adipiscing lacinia elit, non euismod mi adipiscing id. Suspendisse velit odio, iaculis nec malesuada vestibulum, bibendum ut ligula.
			Integer dapibus, quam et cursus rhoncus, lorem lectus viverra odio, et mollis mauris purus vitae mauris. Ut metus diam, condimentum at rutrum sit amet, bibendum et sapien. Phasellus arcu risus, mollis vel sagittis sagittis, gravida vel purus. Curabitur nec libero tellus, eget semper nisl. Etiam auctor orci at eros lobortis varius. Morbi malesuada viverra elit, vitae pellentesque augue pharetra at. Etiam consequat massa eu est rutrum cursus. Integer ultricies orci eu urna varius nec congue risus tempor. Integer eu lacus eget magna tristique tincidunt ut quis velit. Suspendisse ultrices, velit a pulvinar posuere, dolor urna tempor est, eget mollis quam magna eu augue. Praesent nisl est, viverra id malesuada porta, accumsan ac tellus. Quisque eget tortor in quam cursus varius eget quis augue. Quisque placerat ullamcorper sollicitudin. Nulla facilisi. Integer cursus volutpat convallis. Nullam at nunc a risus fermentum vestibulum.
			Nam convallis est id felis volutpat convallis. Pellentesque porttitor arcu at felis sagittis vulputate. Fusce magna dui, luctus ut rutrum quis, aliquet sed orci. Cras sit amet orci vitae nulla ultrices varius sit amet at erat. Mauris quis purus ut diam mollis tincidunt. Aliquam luctus nibh in turpis ultricies et facilisis mauris tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis sed ligula justo, ut dictum nunc. Proin eu neque lorem.
			Nunc ullamcorper accumsan quam vel vestibulum. Nulla vel velit turpis, quis varius mauris. Aliquam libero dui, adipiscing sit amet mollis vitae, sagittis ac leo. Vestibulum vestibulum, enim non tristique tristique, nulla tellus convallis tortor, id convallis dolor elit et diam. Etiam sagittis feugiat lectus, ac porta arcu porta in. Maecenas blandit consectetur sem nec viverra. Ut urna sapien, pellentesque et aliquam ut, auctor at justo. Sed risus tellus, aliquam non blandit ac, commodo eu tellus. Pellentesque at est arcu, ut scelerisque ligula. Cras non mollis justo. Aliquam laoreet sagittis congue. Nulla facilisi. Phasellus nec bibendum lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
			Quisque nunc sem, aliquam ac consequat eget, vulputate eu libero. Maecenas gravida iaculis elit, et hendrerit nibh tincidunt vitae. Proin congue justo et ante cursus ac dapibus erat tincidunt. Phasellus vestibulum nisi sit amet neque pellentesque non vehicula est ultrices. Nulla fringilla tincidunt turpis, eget sollicitudin metus interdum eleifend. Etiam dignissim lacinia arcu, nec viverra eros dignissim nec. Integer id nibh nulla, eu feugiat turpis. Nam consequat ornare consectetur. Donec et ipsum ante, in gravida libero.
			Pellentesque vitae nibh ac urna semper venenatis eu nec est. Vestibulum venenatis, sem ac ultrices varius, lectus lorem vulputate massa, ut suscipit ipsum ligula sed odio. Donec tincidunt quam ac tortor luctus rutrum. Etiam fermentum pretium dictum. Maecenas condimentum sem volutpat mauris volutpat nec aliquam velit egestas. Curabitur iaculis purus et nulla egestas fringilla. Nam a magna mi. Suspendisse potenti. Fusce et nisl a sapien dictum dictum quis sed erat. Ut nisl ante, fermentum ac volutpat eget, iaculis ut turpis. Suspendisse vitae condimentum neque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis sagittis, purus et egestas faucibus, tortor mi luctus orci, vel tincidunt lacus dui ut nibh. Nunc adipiscing congue semper. Aliquam erat volutpat. Nullam feugiat elit elit, commodo tincidunt leo.
		";
		$return = substr ( $lorem, 0, $width );
		return $return;
	}
	
	/**
	 * function html()
	 * Vous permet de vÃ©rifier le style de tous vos contenus
	 * Appel : $lorem->html();
	 **/
	function html() {
		$lorem = "
			<h1>This is a H1 title</h1>
			<h2>This is a H2 title</h2>
			<h3>This is a H3 title</h3>
			<h4>This is a H4 title</h4>
			<h5>This is a H5 title</h5>
			<h6>This is a H6 title</h6>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus dictum cursus ligula, eget pretium orci tincidunt vitae. Cras massa purus, aliquam at interdum eu, ornare facilisis diam. Quisque semper, diam id sodales accumsan, dolor mi dapibus libero, in vestibulum metus mauris sit amet orci. Phasellus dictum tortor facilisis dui tincidunt accumsan. Suspendisse varius arcu pretium tellus tempor varius eu non est. In ac justo quis leo pharetra accumsan ac ut lorem. Proin lacinia, nisi sit amet eleifend rutrum, sem turpis rutrum augue, a gravida lectus nibh sed eros. Nulla sagittis felis et mi mollis id feugiat risus ullamcorper. Cras facilisis, lacus sit amet accumsan pellentesque, nisl augue interdum nibh, id porta mi nisl vel enim. Sed sed urna libero. Donec auctor luctus erat, eu luctus massa condimentum id. Phasellus laoreet, leo quis dictum convallis, ligula libero gravida ipsum, at sagittis velit enim pellentesque ante. Integer dictum justo lobortis magna gravida blandit a at purus. Curabitur sed ipsum in est sagittis pellentesque vitae nec nisi.</p>
			<p><a href='#' title=''>A simple link in a paragraph</a></p>
			<a href='#' title=''>A simple link out of paragraph</a>
			<p>Here is an <abbr title='Abbreviation'>&lt;abbr&gt;</abbr> tag</p>
			<ul>
				<li>Lorem ipsum dolor</li>
				<li>Lorem ipsum dolor
					<ul>
						<li>Lorem ipsum dolor
							<ul>
								<li>Lorem ipsum dolor</li>
								<li><a href='#' title=''>Lorem ipsum dolor</a></li>
							</ul>
						</li>
						<li><a href='#' title=''>Lorem ipsum dolor</a></li>
					</ul>
				</li>
				<li><a href='#' title=''>Lorem ipsum dolor</a></li>
				<li><a href='#' title=''>Lorem ipsum dolor</a></li>
			</ul>
			<ol>
				<li>Lorem ipsum dolor</li>
				<li>Lorem ipsum dolor
					<ul>
						<li>Lorem ipsum dolor
							<ul>
								<li>Lorem ipsum dolor</li>
								<li><a href='#' title=''>Lorem ipsum dolor</a></li>
							</ul>
						</li>
						<li><a href='#' title=''>Lorem ipsum dolor</a></li>
					</ul>
				</li>
				<li><a href='#' title=''>Lorem ipsum dolor</a></li>
					<li><a href='#' title=''>Lorem ipsum dolor</a></li>
			</ol>
			<dl>
				<dt>DL title</dt>
				<dd>DL data</dd>
				<dt>DL title</dt>
				<dd>DL data</dd>
				<dt>DL title</dt>
				<dd>DL data</dd>
			</dl>
			<pre>Some code in &lt;pre&gt; tag</pre>
			<code>Some code in &lt;code&gt; tag</code>	
			<blockquote>This is a &lt;blockquote&gt;</blockquote>			
			<table>
				<thead>
					<tr>
						<th colspan='3'>Table title</th>
					</tr>
					<tr>
						<th>Column #1</th>
						<th>Column #2</th>
						<th>Column #3</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th colspan='3'>Table footer</th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td>Cell #1</td>
						<td>Cell #2</td>
						<td>Cell #3</td>
					</tr>
					<tr>
						<td>Cell #1</td>
						<td>Cell #2</td>
						<td>Cell #3</td>
					</tr>	
					<tr>
						<td>Cell #1</td>
						<td>Cell #2</td>
						<td>Cell #3</td>
					</tr>				
				</tbody>
			</table>
			<hr/>
		";
		return $lorem;
	}
	
	/**
	 * function createList(nombre de li)
	 * Vous permet de gÃ©nÃ©rer une liste
	 * Appel : $lorem->createList(8);
	 **/
	function createList($width) {
		$lorem = '<ul>';
		for($i = 0; $i < $width; $i ++) {
			$lorem .= '<li><a href="#" title="">Lorem ipsum dolor</a></li>';
		}
		$lorem .= '</ul>';
		return $lorem;
	}
	
	/**
	 * function createForm()
	 * Vous permet de gÃ©nÃ©rer un formulaire contenant tous les types de champs
	 * Appel : $lorem->createForm();
	 **/
	function createForm() {
		$lorem = '
			<form method="post" action="">
				<label>Input text</label>
				<input type="text"/>
				<label>Input password</label>
				<input type="password"/>
				<label>Input file</label>
				<input type="file"/>
				<label>Select</label>
				<select>
					<option>First option</option>
					<option>Second option</option>
					<option>Third option</option>
				</select>
				<select multiple="multiple">
					<option>First option</option>
					<option>Second option</option>
					<option>Third option</option>
					<option>First option</option>
					<option>Second option</option>
					<option>Third option</option>
					<option>First option</option>
					<option>Second option</option>
					<option>Third option</option>
				</select>
				<label>Checkbox 1</label><input type="checkbox">
				<label>Checkbox 2</label><input type="checkbox">
				<label>Checkbox 3</label><input type="checkbox">
				<label>Radio 1</label><input type="radio">
				<label>Radio 2</label><input type="radio">
				<label>Radio 3</label><input type="radio">
			</form>
			<textarea>Some text here...</textarea>
			<input type="hidden">
			<input type="submit">
			</form>
		';
		return $lorem;
	}

}