<script type="text/javascript">
$.noConflict();
jQuery(document).ready(function($) {
	var panelWidth = $('#mediaPicture').parent().width();
	$('#mediaPanel').css('width', panelWidth).css('z-index', panelWidth);
	$('#mediaPicture').css('width', panelWidth).css('z-index', panelWidth);

	$(document).bind("contextmenu", function(e) {
	    return false;
	});
	$(document).mousedown(function(e) {
    	var evt = e;
        $(this).mouseup(function(e) {
            var srcElement = $(this);
            $(this).unbind('mouseup');
            if (evt.button == 2) {
			    $('#contextmenu').css({
			        top: e.pageY+'px',
			        left: e.pageX+'px'
			    }).slideDown("slow");
			    $(document).unbind('click');
			    return false;
            }
            else
            {
			    $(document).click(function() {
			        $('#contextmenu').slideUp("slow");
			    });
            }
		});
      });  


	$('#mediafullscreen').click(function(event) {
		event.preventDefault();
		$('#mediaPicture').css('width', document.width).css('float', 'left').css('position', 'absolute').css('top', 0).css('left', 0);
    });


 	$('#mediaResize').click(function(event) {
		event.preventDefault();
		
    });   


    
});

</script>

<ul id="contextmenu" class="dropdown-menu">
  <li><a tabindex="-1" href="#" id="mediafullscreen">Show full screen</a></li>
  <li><a tabindex="-1" href="#" id="mediaResize">Resize</a></li>
</ul>

<div id="mediaPanel">

	<img src="<?php echo __CW_PATH . '/' . $urlImage; ?>" id="mediaPicture">
</div>