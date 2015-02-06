window.getCookie = function(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
};
window.setCookie = function(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
};

window.onEvent = function(event, func) {
    if (typeof document[event] == 'undefined') {
        document[event] = new Array(func);
    } else {
        document[event].push(func);
    }
};
window.listenEvent = function(event) {
    if (typeof document[event] != 'undefined') {
        for (var key in document[event]) {
            document[event][key].call(this);
        }
    }
};

/**
 * Prototype
 */
String.prototype.isMail = function() {
    var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
    return reg.test(this.toString());
};

/**
 * Ajout de la méthode timeConverter sur les type Number et String
 * @type {timeConverter}
 */
Number.prototype.timeConverter = String.prototype.timeConverter = function() {
    var a = new Date(this.toString()*1000);
    var months = ['janvier','f\351vrier','mars','april','mai','juin','juillet','ao\333t','septembre','octobre','novembre','d\351cembre'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date+' '+month+' '+year+' \340  '+hour+':'+min ;
    return time;
}

var bench = function (method, iterations, args, context) {
    var time = 0;
    var timer = function (action) {
        var d = Date.now();
        if (time < 1 || action === 'start') {
            time = d;
            return 0;
        } else if (action === 'stop') {
            var t = d - time;
            time = 0;
            return t;
        } else {
            return d - time;
        }
    };

    var result = [];
    var i = 0;
    timer('start');
    while (i < iterations) {
        result.push(method.apply(context, args));
        i++;
    }

    var execTime = timer('stop');
    if ( typeof console === "object") {
        console.log("Mean execution time was: ", execTime / iterations);
        console.log("Sum execution time was: ", execTime);
        console.log("Result of the method call was:", result[0]);
    }
    return execTime;
};

var CrystalWeb = function (c,r,y,s,t,a,l, w,e,b) {


    function handleBootstrap() {
        if(window.self !== window.top)
            top.location.href=window.location.pathname;

        /*Bootstrap Carousel*/
        jQuery('.carousel').carousel({
            interval: 15000,
            pause: 'hover'
        });

        /*Tooltips*/
        jQuery('.tooltips').tooltip();
        jQuery('.tooltips-show').tooltip('show');
        jQuery('.tooltips-hide').tooltip('hide');
        jQuery('.tooltips-toggle').tooltip('toggle');
        jQuery('.tooltips-destroy').tooltip('destroy');

        /*Popovers*/
        jQuery('.popovers').popover();
        jQuery('.popovers-show').popover('show');
        jQuery('.popovers-hide').popover('hide');
        jQuery('.popovers-toggle').popover('toggle');
        jQuery('.popovers-destroy').popover('destroy');
    }

    function imgError() {
        y('img').error(function(evt) {
            var src = y(this).attr('src');
            y(this)
                .attr('alt', 'Cette image manque')
                .attr('src', '/assets/plugins/crystal-web/alerte-image-manquante.png');
        });
    }

    return {
        init: function () {
            console.log('Initialisation de Crystal-Web');
            y.noConflict();
            handleBootstrap();

            imgError();

        }
    }
}(window, document, jQuery);


jQuery(function(){
    CrystalWeb.init();
});