$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        console.log($(this).width())
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })

})

var App = function (c,r,y,s,t,a,l, w,e,b) {
    function scrollLinkTracker(){
        y(r).on('click', 'a[href*=#]:not([href=#]).scroll', function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
                var target = y(this.hash);
                target = target.length ? target : y('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    y('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    }
    return {
        init: function() {
            scrollLinkTracker();
        }
    }

}(window, document, jQuery, console);