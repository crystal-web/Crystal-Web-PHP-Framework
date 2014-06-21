<style>
    .example {
        position: relative;
        padding: 45px 15px 15px;
        margin: 0 -15px 15px;
        background-color: #fafafa;
        box-shadow: inset 0 3px 6px rgba(0,0,0,.05);
        border-color: #e5e5e5 #eee #eee;
        border-style: solid;
        border-width: 1px 0;
    }
    /* Echo out a label for the example */
    .example:after {
        content: "Exemple";
        position: absolute;
        top:  15px;
        left: 15px;
        font-size: 12px;
        font-weight: bold;
        color: #bbb;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Tweak display of the code snippets when following an example */
    .example + .highlight {
        margin: -15px -15px 15px;
        border-radius: 0;
        border-width: 0 0 1px;
    }

    .example > .btn-toolbar + .btn-toolbar {
        margin-top: 10px;
    }


    .page-header{padding-bottom:9px;margin:40px 0 20px;border-bottom:1px solid #eee}


    .headline {
        display: block;
        margin: 10px 0 25px 0;
        border-bottom: 1px dotted #e4e9f0;
    }
    .headline h1 {
        font-size:  3em;
    }
    .headline h2 {
        font-size:  2.5em;
    }
    .headline h3 {
        font-size:  2em;
    }
    .headline h4 {
        font-size:  1.5em;
    }
    .headline h5 {
        font-size:  1em;
    }
    .headline h1, .headline h2, .headline h3, .headline h4, .headline h5 {
        font-family:  Fairview Regular, sans-serif;
        display: inline-block;
        text-align: center;
        text-transform: uppercase;
        color: #681415;
        margin: 0 0 -2px 0;
        padding: 0 10px;
        /* display: inline-block; */
        border-bottom: 2px solid #681415;
    }
    .headline h1 a, .headline h2 a, .headline h3 a, .headline h4 a, .headline h5 a  {
        text-decoration: none;
    }
    .headline h1 span, .headline h2 span, .headline h3 span, .headline h4 span, .headline h5 span  {
        zoom: 0.75;
    }


    .panel{margin-bottom:20px;background-color:#fff;border:1px solid transparent;border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(0,0,0,.05)}
    .panel-body{padding:15px}
    .panel>.list-group{margin-bottom:0}
    .panel>.list-group .list-group-item{border-width:1px 0;border-radius:0}
    .panel>.list-group .list-group-item:first-child{border-top:0}
    .panel>.list-group .list-group-item:last-child{border-bottom:0}
    .panel>.list-group:first-child .list-group-item:first-child{border-top-left-radius:3px;border-top-right-radius:3px}
    .panel>.list-group:last-child .list-group-item:last-child{border-bottom-right-radius:3px;border-bottom-left-radius:3px}
    .panel-heading+.list-group .list-group-item:first-child{border-top-width:0}
    .panel>.table,.panel>.table-responsive>.table{margin-bottom:0}
    .panel>.table:first-child>thead:first-child>tr:first-child td:first-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child td:first-child,.panel>.table:first-child>tbody:first-child>tr:first-child td:first-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child td:first-child,.panel>.table:first-child>thead:first-child>tr:first-child th:first-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child th:first-child,.panel>.table:first-child>tbody:first-child>tr:first-child th:first-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child th:first-child{border-top-left-radius:3px}
    .panel>.table:first-child>thead:first-child>tr:first-child td:last-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child td:last-child,.panel>.table:first-child>tbody:first-child>tr:first-child td:last-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child td:last-child,.panel>.table:first-child>thead:first-child>tr:first-child th:last-child,.panel>.table-responsive:first-child>.table:first-child>thead:first-child>tr:first-child th:last-child,.panel>.table:first-child>tbody:first-child>tr:first-child th:last-child,.panel>.table-responsive:first-child>.table:first-child>tbody:first-child>tr:first-child th:last-child{border-top-right-radius:3px}
    .panel>.table:last-child>tbody:last-child>tr:last-child td:first-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child td:first-child,.panel>.table:last-child>tfoot:last-child>tr:last-child td:first-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child td:first-child,.panel>.table:last-child>tbody:last-child>tr:last-child th:first-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child th:first-child,.panel>.table:last-child>tfoot:last-child>tr:last-child th:first-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child th:first-child{border-bottom-left-radius:3px}
    .panel>.table:last-child>tbody:last-child>tr:last-child td:last-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child td:last-child,.panel>.table:last-child>tfoot:last-child>tr:last-child td:last-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child td:last-child,.panel>.table:last-child>tbody:last-child>tr:last-child th:last-child,.panel>.table-responsive:last-child>.table:last-child>tbody:last-child>tr:last-child th:last-child,.panel>.table:last-child>tfoot:last-child>tr:last-child th:last-child,.panel>.table-responsive:last-child>.table:last-child>tfoot:last-child>tr:last-child th:last-child{border-bottom-right-radius:3px}
    .panel>.panel-body+.table,.panel>.panel-body+.table-responsive{border-top:1px solid #ddd}
    .panel>.table>tbody:first-child>tr:first-child th,.panel>.table>tbody:first-child>tr:first-child td{border-top:0}
    .panel>.table-bordered,.panel>.table-responsive>.table-bordered{border:0}
    .panel>.table-bordered>thead>tr>th:first-child,.panel>.table-responsive>.table-bordered>thead>tr>th:first-child,.panel>.table-bordered>tbody>tr>th:first-child,.panel>.table-responsive>.table-bordered>tbody>tr>th:first-child,.panel>.table-bordered>tfoot>tr>th:first-child,.panel>.table-responsive>.table-bordered>tfoot>tr>th:first-child,.panel>.table-bordered>thead>tr>td:first-child,.panel>.table-responsive>.table-bordered>thead>tr>td:first-child,.panel>.table-bordered>tbody>tr>td:first-child,.panel>.table-responsive>.table-bordered>tbody>tr>td:first-child,.panel>.table-bordered>tfoot>tr>td:first-child,.panel>.table-responsive>.table-bordered>tfoot>tr>td:first-child{border-left:0}
    .panel>.table-bordered>thead>tr>th:last-child,.panel>.table-responsive>.table-bordered>thead>tr>th:last-child,.panel>.table-bordered>tbody>tr>th:last-child,.panel>.table-responsive>.table-bordered>tbody>tr>th:last-child,.panel>.table-bordered>tfoot>tr>th:last-child,.panel>.table-responsive>.table-bordered>tfoot>tr>th:last-child,.panel>.table-bordered>thead>tr>td:last-child,.panel>.table-responsive>.table-bordered>thead>tr>td:last-child,.panel>.table-bordered>tbody>tr>td:last-child,.panel>.table-responsive>.table-bordered>tbody>tr>td:last-child,.panel>.table-bordered>tfoot>tr>td:last-child,.panel>.table-responsive>.table-bordered>tfoot>tr>td:last-child{border-right:0}
    .panel>.table-bordered>thead>tr:first-child>th,.panel>.table-responsive>.table-bordered>thead>tr:first-child>th,.panel>.table-bordered>tbody>tr:first-child>th,.panel>.table-responsive>.table-bordered>tbody>tr:first-child>th,.panel>.table-bordered>tfoot>tr:first-child>th,.panel>.table-responsive>.table-bordered>tfoot>tr:first-child>th,.panel>.table-bordered>thead>tr:first-child>td,.panel>.table-responsive>.table-bordered>thead>tr:first-child>td,.panel>.table-bordered>tbody>tr:first-child>td,.panel>.table-responsive>.table-bordered>tbody>tr:first-child>td,.panel>.table-bordered>tfoot>tr:first-child>td,.panel>.table-responsive>.table-bordered>tfoot>tr:first-child>td{border-top:0}
    .panel>.table-bordered>thead>tr:last-child>th,.panel>.table-responsive>.table-bordered>thead>tr:last-child>th,.panel>.table-bordered>tbody>tr:last-child>th,.panel>.table-responsive>.table-bordered>tbody>tr:last-child>th,.panel>.table-bordered>tfoot>tr:last-child>th,.panel>.table-responsive>.table-bordered>tfoot>tr:last-child>th,.panel>.table-bordered>thead>tr:last-child>td,.panel>.table-responsive>.table-bordered>thead>tr:last-child>td,.panel>.table-bordered>tbody>tr:last-child>td,.panel>.table-responsive>.table-bordered>tbody>tr:last-child>td,.panel>.table-bordered>tfoot>tr:last-child>td,.panel>.table-responsive>.table-bordered>tfoot>tr:last-child>td{border-bottom:0}
    .panel>.table-responsive{margin-bottom:0;border:0}
    .panel-heading{padding:10px 15px;border-bottom:1px solid transparent;border-top-left-radius:3px;border-top-right-radius:3px}
    .panel-heading>.dropdown .dropdown-toggle{color:inherit}
    .panel-title{margin-top:0;margin-bottom:0;font-size:16px;color:inherit}
    .panel-title>a{color:inherit}
    .panel-footer{padding:10px 15px;background-color:#f5f5f5;border-top:1px solid #ddd;border-bottom-right-radius:3px;border-bottom-left-radius:3px}
    .panel-group{margin-bottom:20px}
    .panel-group .panel{margin-bottom:0;overflow:hidden;border-radius:4px}
    .panel-group .panel+.panel{margin-top:5px}
    .panel-group .panel-heading{border-bottom:0}
    .panel-group .panel-heading+.panel-collapse .panel-body{border-top:1px solid #ddd}
    .panel-group .panel-footer{border-top:0}
    .panel-group .panel-footer+.panel-collapse .panel-body{border-bottom:1px solid #ddd}
    .panel-default{border-color:#ddd}
    .panel-default>.panel-heading{color:#333;background-color:#f5f5f5;border-color:#ddd}
    .panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#ddd}
    .panel-default>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#ddd}
    .panel-primary{border-color:#428bca}
    .panel-primary>.panel-heading{color:#fff;background-color:#428bca;border-color:#428bca}
    .panel-primary>.panel-heading+.panel-collapse .panel-body{border-top-color:#428bca}
    .panel-primary>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#428bca}
    .panel-success{border-color:#d6e9c6}
    .panel-success>.panel-heading{color:#3c763d;background-color:#dff0d8;border-color:#d6e9c6}
    .panel-success>.panel-heading+.panel-collapse .panel-body{border-top-color:#d6e9c6}
    .panel-success>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#d6e9c6}
    .panel-info{border-color:#bce8f1}
    .panel-info>.panel-heading{color:#31708f;background-color:#d9edf7;border-color:#bce8f1}
    .panel-info>.panel-heading+.panel-collapse .panel-body{border-top-color:#bce8f1}
    .panel-info>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#bce8f1}
    .panel-warning{border-color:#faebcc}
    .panel-warning>.panel-heading{color:#8a6d3b;background-color:#fcf8e3;border-color:#faebcc}
    .panel-warning>.panel-heading+.panel-collapse .panel-body{border-top-color:#faebcc}
    .panel-warning>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#faebcc}
    .panel-danger{border-color:#ebccd1}
    .panel-danger>.panel-heading{color:#a94442;background-color:#f2dede;border-color:#ebccd1}
    .panel-danger>.panel-heading+.panel-collapse .panel-body{border-top-color:#ebccd1}
    .panel-danger>.panel-footer+.panel-collapse .panel-body{border-bottom-color:#ebccd1}


    .callout {
        margin: 20px 0;
        padding: 20px;
        border-left: 3px solid #eee;
    }
    .callout h4 {
        margin-top: 0;
        margin-bottom: 5px;
    }
    .callout p:last-child {
        margin-bottom: 0;
    }
    .callout code {
        background-color: #fff;
        border-radius: 3px;
    }

    /* Variations */
    .callout-danger {
        background-color: #fdf7f7;
        border-color: #d9534f;
    }
    .callout-danger h4 {
        color: #d9534f;
    }
    .callout-warning {
        background-color: #fcf8f2;
        border-color: #f0ad4e;
    }
    .callout-warning h4 {
        color: #f0ad4e;
    }
    .callout-info {
        background-color: #f4f8fa;
        border-color: #5bc0de;
    }
    .callout-info h4 {
        color: #5bc0de;
    }
</style>

<h3 id="pageheader" class="page-header">PAGE-HEADER</h3>
<div class="example">
    <h3 class="page-header">PAGE-HEADER</h3>
</div>
<pre class="highlight">
&lt;h3 class="page-header"&gt;PAGE-HEADER&lt;/h3&gt;
</pre>

<h3 id="headline" class="page-header">HEADLINE</h3>

<div class="example">
    <div class="headline">
        <h1>H1 Headline <span>span</span></h1>
    </div>
    <div class="headline">
        <h2>H3 Headline <span>span</span></h2>
    </div>
    <div class="headline">
        <h3>H3 Headline <span>span</span></h3>
    </div>
    <div class="headline">
        <h4>H4 Headline <span>span</span></h4>
    </div>
    <div class="headline">
        <h5>H5 Headline <span>span</span></h5>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="headline"&gt;
    &lt;h1&gt;H1 Headline &lt;span&gt;span&lt;/span&gt;&lt;/h1&gt;
&lt;/div&gt;
&lt;div class="headline"&gt;
    &lt;h2&gt;H3 Headline &lt;span&gt;span&lt;/span&gt;&lt;/h2&gt;
&lt;/div&gt;
&lt;div class="headline"&gt;
    &lt;h3&gt;H3 Headline &lt;span&gt;span&lt;/span&gt;&lt;/h3&gt;
&lt;/div&gt;
&lt;div class="headline"&gt;
    &lt;h4&gt;H4 Headline &lt;span&gt;span&lt;/span&gt;&lt;/h4&gt;
&lt;/div&gt;
&lt;div class="headline"&gt;
    &lt;h5&gt;H5 Headline &lt;span&gt;span&lt;/span&gt;&lt;/h5&gt;
&lt;/div&gt;
</pre>
</div>


<h3 id="panel" class="page-header">PANEL</h3>

<div class="example">

<div class="panel panel-default">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-default"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="panel-footer">panel-footer</div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-primary"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="panel-footer">panel-footer</div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-success"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="panel-footer">panel-footer</div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-info"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="panel-footer">panel-footer</div>
</div>

<div class="panel panel-warning">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-warning"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="panel-footer">panel-footer</div>
</div>

<div class="panel panel-danger">
    <div class="panel-heading">panel-heading</div>
    <div class="panel-body">
<pre>
&lt;div class="panel panel-danger"&gt;
    &lt;div class="panel-heading"&gt;panel-heading&lt;/div&gt;
    &lt;div class="panel-body"&gt;panel-body&lt;/div&gt;
    &lt;div class="panel-footer"&gt;panel-footer&lt;/div&gt;
&lt;/div&gt;
</pre>
     </div>
    <div class="panel-footer">panel-footer</div>
</div>

</div>


<h3 id="callout" class="page-header">Callout</h3>
<div class="example">
    <div class="callout callout-info">
        <h4>Titre en H4</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.</p>
    </div>
    <div class="callout callout-warning">
        <h4>Titre en H4</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.</p>
    </div>
    <div class="callout callout-danger">
        <h4>Titre en H4</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.</p>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="callout callout-info"&gt;
    &lt;h4&gt;Titre en H4&lt;/h4&gt;
    &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.&lt;/p&gt;
&lt;/div&gt;
&lt;div class="callout callout-warning"&gt;
    &lt;h4&gt;Titre en H4&lt;/h4&gt;
    &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.&lt;/p&gt;
&lt;/div&gt;
&lt;div class="callout callout-danger"&gt;
    &lt;h4&gt;Titre en H4&lt;/h4&gt;
    &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores culpa cum deleniti ea eligendi, et excepturi facilis iusto maiores necessitatibus neque nisi recusandae saepe sed soluta tempore velit voluptas, voluptatem.&lt;/p&gt;
&lt;/div&gt;
</pre>
</div>

<h3 id="buttons" class="page-header">Boutons</h3>
<div class="example">
    <div class="btn-group">
        <button type="button" class="btn btn-default">Left</button>
        <button type="button" class="btn btn-default">Middle</button>
        <button type="button" class="btn btn-default">Right</button>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Dropdown
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#">Dropdown link</a></li>
                <li><a href="#">Dropdown link</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-default"&gt;Left&lt;/button&gt;
    &lt;button type="button" class="btn btn-default"&gt;Middle&lt;/button&gt;
    &lt;button type="button" class="btn btn-default"&gt;Right&lt;/button&gt;
        &lt;div class="btn-group"&gt;
            &lt;button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;
                Dropdown
                &lt;span class="caret"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;ul class="dropdown-menu"&gt;
                &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
                &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
            &lt;/ul&gt;
        &lt;/div&gt;
&lt;/div&gt;
</pre>
</div>

<div class="example">
    <div class="btn-toolbar" role="toolbar" style="margin: 0;">
        <div class="btn-group">
            <button type="button" class="btn btn-default">1</button>
            <button type="button" class="btn btn-default">2</button>
            <button type="button" class="btn btn-default">3</button>
            <button type="button" class="btn btn-default">4</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default">5</button>
            <button type="button" class="btn btn-default">6</button>
            <button type="button" class="btn btn-default">7</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default">8</button>
        </div>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="btn-toolbar" role="toolbar" style="margin: 0;"&gt;
	&lt;div class="btn-group"&gt;
		&lt;button type="button" class="btn btn-default"&gt;1&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;2&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;3&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;4&lt;/button&gt;
	&lt;/div&gt;
	&lt;div class="btn-group"&gt;
		&lt;button type="button" class="btn btn-default"&gt;5&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;6&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;7&lt;/button&gt;
	&lt;/div&gt;
	&lt;div class="btn-group"&gt;
		&lt;button type="button" class="btn btn-default"&gt;8&lt;/button&gt;
	&lt;/div&gt;
&lt;/div&gt;
</pre>
</div>

<div class="example">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-lg">
            <button type="button" class="btn btn-default">Left</button>
            <button type="button" class="btn btn-default">Middle</button>
            <button type="button" class="btn btn-default">Right</button>
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group">
            <button type="button" class="btn btn-default">Left</button>
            <button type="button" class="btn btn-default">Middle</button>
            <button type="button" class="btn btn-default">Right</button>
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-default">Left</button>
            <button type="button" class="btn btn-default">Middle</button>
            <button type="button" class="btn btn-default">Right</button>
        </div>
    </div>
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-xs">
            <button type="button" class="btn btn-default">Left</button>
            <button type="button" class="btn btn-default">Middle</button>
            <button type="button" class="btn btn-default">Right</button>
        </div>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="btn-toolbar" role="toolbar"&gt;
	&lt;div class="btn-group btn-group-lg"&gt;
		&lt;button type="button" class="btn btn-default"&gt;Left&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Middle&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Right&lt;/button&gt;
	&lt;/div&gt;
&lt;/div&gt;
&lt;div class="btn-toolbar" role="toolbar"&gt;
	&lt;div class="btn-group"&gt;
		&lt;button type="button" class="btn btn-default"&gt;Left&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Middle&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Right&lt;/button&gt;
	&lt;/div&gt;
&lt;/div&gt;
&lt;div class="btn-toolbar" role="toolbar"&gt;
	&lt;div class="btn-group btn-group-sm"&gt;
		&lt;button type="button" class="btn btn-default"&gt;Left&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Middle&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Right&lt;/button&gt;
	&lt;/div&gt;
&lt;/div&gt;
&lt;div class="btn-toolbar" role="toolbar"&gt;
	&lt;div class="btn-group btn-group-xs"&gt;
		&lt;button type="button" class="btn btn-default"&gt;Left&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Middle&lt;/button&gt;
		&lt;button type="button" class="btn btn-default"&gt;Right&lt;/button&gt;
	&lt;/div&gt;
&lt;/div&gt;
</pre>
</div>

<div class="example">
    <div class="btn-group-vertical">
        <button type="button" class="btn btn-default">Button</button>
        <button type="button" class="btn btn-default">Button</button>
        <div class="btn-group">
            <button id="btnGroupVerticalDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Dropdown
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop1">
                <li><a href="#">Dropdown link</a></li>
                <li><a href="#">Dropdown link</a></li>
            </ul>
        </div>
        <button type="button" class="btn btn-default">Button</button>
        <button type="button" class="btn btn-default">Button</button>
        <div class="btn-group">
            <button id="btnGroupVerticalDrop2" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Dropdown
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop2">
                <li><a href="#">Dropdown link</a></li>
                <li><a href="#">Dropdown link</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button id="btnGroupVerticalDrop3" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Dropdown
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop3">
                <li><a href="#">Dropdown link</a></li>
                <li><a href="#">Dropdown link</a></li>
            </ul>
        </div>
        <div class="btn-group">
            <button id="btnGroupVerticalDrop4" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Dropdown
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop4">
                <li><a href="#">Dropdown link</a></li>
                <li><a href="#">Dropdown link</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="highlight">
<pre>
&lt;div class="btn-group-vertical"&gt;
    &lt;button type="button" class="btn btn-default"&gt;Button&lt;/button&gt;
    &lt;button type="button" class="btn btn-default"&gt;Button&lt;/button&gt;
    &lt;div class="btn-group"&gt;
        &lt;button id="btnGroupVerticalDrop1" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;
        Dropdown
        &lt;span class="caret"&gt;&lt;/span&gt;
    &lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop1"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
    &lt;/div&gt;
        &lt;button type="button" class="btn btn-default"&gt;Button&lt;/button&gt;
        &lt;button type="button" class="btn btn-default"&gt;Button&lt;/button&gt;
        &lt;div class="btn-group"&gt;
        &lt;button id="btnGroupVerticalDrop2" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;
            Dropdown
            &lt;span class="caret"&gt;&lt;/span&gt;
        &lt;/button&gt;
            &lt;ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop2"&gt;
                &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
                &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
            &lt;/ul&gt;
        &lt;/div&gt;
    &lt;div class="btn-group"&gt;
        &lt;button id="btnGroupVerticalDrop3" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;
            Dropdown
            &lt;span class="caret"&gt;&lt;/span&gt;
        &lt;/button&gt;
        &lt;ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop3"&gt;
            &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
            &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
    &lt;div class="btn-group"&gt;
        &lt;button id="btnGroupVerticalDrop4" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;
            Dropdown
            &lt;span class="caret"&gt;&lt;/span&gt;
        &lt;/button&gt;
        &lt;ul class="dropdown-menu" role="menu" aria-labelledby="btnGroupVerticalDrop4"&gt;
            &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
            &lt;li&gt;&lt;a href="#"&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
        &lt;/ul&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
</div>

<div class="example">
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Default <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
    <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Primary <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Success <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
    <div class="btn-group">
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Info <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
    <div class="btn-group">
        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Warning <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
    <div class="btn-group">
        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Danger <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
        </ul>
    </div><!-- /btn-group -->
</div>
<div class="highlight">
<pre>
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"&gt;Default &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"&gt;Primary &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"&gt;Success &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"&gt;Info &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"&gt;Warning &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
&lt;div class="btn-group"&gt;
    &lt;button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"&gt;Danger &lt;span class="caret"&gt;&lt;/span&gt;&lt;/button&gt;
    &lt;ul class="dropdown-menu" role="menu"&gt;
        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
        &lt;li class="divider"&gt;&lt;/li&gt;
        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
    &lt;/ul&gt;
&lt;/div&gt;&lt;!-- /btn-group --&gt;
</pre>
</div>


<h3 id="tabs" class="page-header">Tabs</h3>
<div class="example">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Messages</a></li>
    </ul>
</div>
<div class="highlight">
<pre>
&lt;ul class="nav nav-tabs"&gt;
    &lt;li class="active"&gt;&lt;a href="#"&gt;Home&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Profile&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Messages&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</pre>
</div>

<h3 id="pills" class="page-header">Pills</h3>
<div class="example">
    <ul class="nav nav-pills">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Messages</a></li>
    </ul>
</div>
<div class="highlight">
<pre>
&lt;ul class="nav nav-pills"&gt;
    &lt;li class="active"&gt;&lt;a href="#"&gt;Home&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Profile&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Messages&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</pre>
</div>


<div class="example">
    <ul class="nav nav-tabs">
        <li class=""><a href="#home" data-toggle="tab">Home</a></li>
        <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
        <li class="dropdown">
            <a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                <li><a href="#dropdown1" tabindex="-1" data-toggle="tab">@fat</a></li>
                <li><a href="#dropdown2" tabindex="-1" data-toggle="tab">@mdo</a></li>
            </ul>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="home">
            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
        </div>
        <div class="tab-pane fade active in" id="profile">
            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
        </div>
        <div class="tab-pane fade" id="dropdown1">
            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
        </div>
        <div class="tab-pane fade" id="dropdown2">
            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
        </div>
    </div>
</div>
<div class="highlight">
<pre>
&lt;ul class="nav nav-tabs"&gt;
    &lt;li class=""&gt;&lt;a href="#home" data-toggle="tab"&gt;Home&lt;/a&gt;&lt;/li&gt;
    &lt;li class="active"&gt;&lt;a href="#profile" data-toggle="tab"&gt;Profile&lt;/a&gt;&lt;/li&gt;
    &lt;li class="dropdown"&gt;
        &lt;a href="#" id="myTabDrop1" class="dropdown-toggle" data-toggle="dropdown"&gt;Dropdown &lt;b class="caret"&gt;&lt;/b&gt;&lt;/a&gt;
        &lt;ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1"&gt;
            &lt;li&gt;&lt;a href="#dropdown1" tabindex="-1" data-toggle="tab"&gt;@fat&lt;/a&gt;&lt;/li&gt;
            &lt;li&gt;&lt;a href="#dropdown2" tabindex="-1" data-toggle="tab"&gt;@mdo&lt;/a&gt;&lt;/li&gt;
        &lt;/ul&gt;
    &lt;/li&gt;
&lt;/ul&gt;
&lt;div class="tab-content"&gt;
    &lt;div class="tab-pane fade" id="home"&gt;
        &lt;p&gt;Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="tab-pane fade active in" id="profile"&gt;
        &lt;p&gt;Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="tab-pane fade" id="dropdown1"&gt;
        &lt;p&gt;Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="tab-pane fade" id="dropdown2"&gt;
        &lt;p&gt;Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
</div>

<h3 id="stacked" class="page-header">Stacked</h3>
<div class="example">
    <ul class="nav nav-stacked">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Profile</a></li>
        <li><a href="#">Messages</a></li>
    </ul>
</div>
<div class="highlight">
<pre>
&lt;ul class="nav nav-stacked"&gt;
    &lt;li class="active"&gt;&lt;a href="#"&gt;Home&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Profile&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#"&gt;Messages&lt;/a&gt;&lt;/li&gt;
&lt;/ul&gt;
</pre>
</div>


<h3 id="navigation" class="page-header">Navigation</h3>
<div class="example">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Brand</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Link</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div>
<div class="highlight">
<pre>
&lt;nav class="navbar navbar-default" role="navigation"&gt;
    &lt;div class="container-fluid"&gt;
        &lt;!-- Brand and toggle get grouped for better mobile display --&gt;
        &lt;div class="navbar-header"&gt;
            &lt;button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"&gt;
                &lt;span class="sr-only"&gt;Toggle navigation&lt;/span&gt;
                &lt;span class="icon-bar"&gt;&lt;/span&gt;
                &lt;span class="icon-bar"&gt;&lt;/span&gt;
                &lt;span class="icon-bar"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;a class="navbar-brand" href="#"&gt;Brand&lt;/a&gt;
        &lt;/div&gt;

        &lt;!-- Collect the nav links, forms, and other content for toggling --&gt;
        &lt;div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"&gt;
            &lt;ul class="nav navbar-nav"&gt;
                &lt;li class="active"&gt;&lt;a href="#"&gt;Link&lt;/a&gt;&lt;/li&gt;
                &lt;li&gt;&lt;a href="#"&gt;Link&lt;/a&gt;&lt;/li&gt;
                &lt;li class="dropdown"&gt;
                    &lt;a href="#" class="dropdown-toggle" data-toggle="dropdown"&gt;Dropdown &lt;b class="caret"&gt;&lt;/b&gt;&lt;/a&gt;
                    &lt;ul class="dropdown-menu" role="menu"&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="divider"&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="divider"&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;One more separated link&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/li&gt;
            &lt;/ul&gt;
            &lt;form class="navbar-form navbar-left" role="search"&gt;
                &lt;div class="form-group"&gt;
                    &lt;input type="text" class="form-control" placeholder="Search"&gt;
                &lt;/div&gt;
                &lt;button type="submit" class="btn btn-default"&gt;Submit&lt;/button&gt;
            &lt;/form&gt;
            &lt;ul class="nav navbar-nav navbar-right"&gt;
                &lt;li&gt;&lt;a href="#"&gt;Link&lt;/a&gt;&lt;/li&gt;
                &lt;li class="dropdown"&gt;
                    &lt;a href="#" class="dropdown-toggle" data-toggle="dropdown"&gt;Dropdown &lt;b class="caret"&gt;&lt;/b&gt;&lt;/a&gt;
                    &lt;ul class="dropdown-menu" role="menu"&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Action&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Another action&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="divider"&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="#"&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/li&gt;
            &lt;/ul&gt;
        &lt;/div&gt;&lt;!-- /.navbar-collapse --&gt;
    &lt;/div&gt;&lt;!-- /.container-fluid --&gt;
&lt;/nav&gt;
</pre>
</div>


<h3 id="breadcrumbs" class="page-header">Fil ariant</h3>
<div class="example">
    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li><a href="#">Library</a></li>
        <li class="active">Data</li>
    </ol>
</div>
<div class="highlight">
<pre>
&lt;ol class="breadcrumb"&gt;
  &lt;li&gt;&lt;a href="#"&gt;Home&lt;/a&gt;&lt;/li&gt;
  &lt;li&gt;&lt;a href="#"&gt;Library&lt;/a&gt;&lt;/li&gt;
  &lt;li class="active"&gt;Data&lt;/li&gt;
&lt;/ol&gt;
</pre>
</div>

