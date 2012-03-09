<?php
$this->mvc->Page->setHeaderCss(__CDN.'/files/css/carousel/carousel.css');
$this->mvc->Page->setHeaderJs(__CDN.'/files/js/carousel/carousel.js');

	if(count($sliderList))
	{
	?>
		<script type="text/javascript">
			$(function() {
				$("#slider").carouFredSel({
					width: 870,
					items 		: 1,
					direction	: "up",
					auto : {
						easing: "linear",
						 fx: "crossfade",
						duration	: 1000,
						pauseDuration: 3000,
						pauseOnHover: true,
					},
					prev	: {	
						button	: "#foo2_prev",
						key		: "left"
					},
					next	: { 
						button	: "#foo2_next",
						key		: "right"
					},
					pagination	: "#foo2_pag"
	
				}).find(".slide").hover(
					function() { $(this).find("div").slideDown(); },
					function() { $(this).find("div").slideUp();	}
				);

			});
		</script>
<div class="html_carousel">
	<div id="slider">
	
	
	
	<?php
	foreach($sliderList AS $key => $data){ ?>
		<div class="slide">
			<a href="<?php echo $data->link; ?>">
			<img src="<?php echo $data->image; ?>" width="870" height="400" title="<?php echo $data->title; ?>">
			</a>
			<div>
				<h4><?php echo $data->title; ?></h4>
				<p><?php echo $data->description; ?></p>
			</div>
		</div>
	<?php } ?>

	</div>
	<div class="clearfix"></div>
	
	<a class="carPrev" id="foo2_prev" href="#"><span>prev</span></a>
	<a class="carNext" id="foo2_next" href="#"><span>next</span></a>
	<div class="carPagination" id="foo2_pag"></div>

</div>
<?php } ?>