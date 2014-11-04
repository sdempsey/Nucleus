// Custom test for matchMedia support
Modernizr.addTest('matchmedia', function(){
    return !!(window.webkitMatchMedia || window.mozMatchMedia || window.oMatchMedia || window.msMatchMedia || window.matchMedia);
});

// Script loader
Modernizr.load([
    { // Media Queries & matchMedia
        test: Modernizr.mq,
        nope: SiteInfo.theme_directory + '/scripts/libraries/respond.src.js'
    },
    { // matchMedia
        test: Modernizr.matchmedia,
        nope: SiteInfo.theme_directory + '/scripts/libraries/matchMedia.js'
    },
    { // CSS3 pseudo selectors
        test: Modernizr.rgba,
        nope: SiteInfo.theme_directory + '/scripts/libraries/selectivizr-min.js'
    },
    { // Placeholders
        test: Modernizr.input.placeholder,
        nope: SiteInfo.theme_directory + '/scripts/libraries/jquery.placeholder.js',
        complete: function () {
            if (!Modernizr.input.placeholder) {
                jQuery('input, textarea').placeholder();
            }
        }
    }
]);