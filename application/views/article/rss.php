<?php loadFunction('TronqueHtml'); echo '<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL; ?>
<rss version="2.0">
    <channel>
        <title><?php echo $this->mvc->Page->getSiteTitle(); ?></title>
        <description>Flux générer par Crystal-Web</description>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <link><?php echo __CW_PATH; ?></link>
		<generator>Crystal-Web Solution <?php echo __VER; ?></generator>
		<?php foreach($article AS $k=>$v): ?>
        <item>
            <title><?php echo clean($v->titre,  'str'); ?></title>
			<guid isPermaLink="true"><?php echo Router::url('article/post/slug:'.clean($v->titre, 'slug').'/id:'.$v->id); ?></guid>
            <description><![CDATA[
			<?php echo TronqueHtml(clean($v->content, 'html'), 280, ' ', ' ...'); ?>
			]]></description>
            <pubDate><?php echo date('r', $v->date); ?></pubDate>
            <link><?php echo Router::url('article/post/slug:'.clean($v->titre, 'slug').'/id:'.$v->id); ?></link>
        </item>
		<?php endforeach; ?>
    </channel>
</rss>