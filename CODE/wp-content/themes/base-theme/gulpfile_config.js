"use strict";
module.exports = {
    "debug": true,
    "output" : {
        "names" : {
            "css" : {
                "head" : "theme.css",
                "footer" : "theme-footer.css"
            },
            "js" : {
                "head" : "vendors-head.js",
                "footer" : "theme.js"
            },
        },
        "directories": {
            "css": "dist/css",
            "js": "dist/js",
            "fonts": "dist/fonts"
        },
    },
    "files": {
        "js": {
            "head": [
                "vendor/components/modernizr/modernizr.js",
                "assets/scripts/conditionizr.min.js",
                "assets/scripts/isMobile.min.js"
            ],
            "footer": [
                //the whole bootstrap. We can use the second index to select specifically which parts of bootstrap do we want to load.
                //"vendor/twbs/bootstrap/js/affix.js",
                //"vendor/twbs/bootstrap/js/alert.js",
                //"vendor/twbs/bootstrap/js/button.js",
                "vendor/twbs/bootstrap/js/carousel.js",
                //"vendor/twbs/bootstrap/js/collapse.js",
                //"vendor/twbs/bootstrap/js/dropdown.js",
                //"vendor/twbs/bootstrap/js/modal.js",
                //"vendor/twbs/bootstrap/js/popover.js",
                //"vendor/twbs/bootstrap/js/scrollspy.js",
                //"vendor/twbs/bootstrap/js/tab.js",
                //"vendor/twbs/bootstrap/js/tooltip.js",
                //"vendor/twbs/bootstrap/js/transition.js"
                "vendor/twbs/bootstrap-sass/dist/js/bootstrap.min.js",
                "assets/lib/owl-carousel/*.min.js",
                "assets/lib/gsap/ScrollMagic.min.js",
                "assets/lib/gsap/TweenMax.min.js",
                "assets/lib/gsap/animation.velocity.min.js",
                "assets/lib/gsap/animation.gsap.min.js",
                "assets/lib/gsap/debug.addIndicators.min.js",
                "!scripts/_*.js",            // Will be ignore.
                "scripts/*.js"
            ]
        },
        "css": {
            "head": [
                "vendor/twbs/bootstrap/dist/css/bootstrap.min.css",    //the whole bootstrap. This should be optimized/configured.
                //"vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css",
                "vendor/components/font-awesome/css/font-awesome.min.css",
                "assets/lib/owl-carousel/*.css",

                "!assets/style/_*.css",     // Will be ignore.
                "!scss/_*.scss",            // Will be ignore.
                "assets/style/*.css",
                "scss/*.scss"
            ],
            "footer": []
        },
        "fonts": [
            "vendor/twbs/bootstrap/dist/fonts/*",
            "vendor/components/font-awesome/fonts/*"
        ]
    }
};
