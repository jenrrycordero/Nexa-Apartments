"use strict";

const gulp    = require('gulp');
const gulpIf  = require('gulp-if');
const rename  = require('gulp-rename');
const concat  = require('gulp-concat');
const uglify  = require('gulp-uglify');
const concatCss  = require('gulp-concat-css');
const compass = require('gulp-compass');
const less = require('gulp-less');
const cssNano = require('gulp-cssnano');
const runSequence = require('run-sequence');
const autoprefix = require('gulp-autoprefixer');
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const babel = require('gulp-babel');
const gc = require("./gulpfile_config");

//  -------    Sequence Tasks.
gulp.task("default", function(callback){
    gulp.run("build");
});


gulp.task("build", function(callback){
    runSequence('vendorHead', 'vendorFooter', 'themeCss', 'themeJs', 'fonts', callback);
});
gulp.task('vendor', function(callback){
    runSequence(['vendorHead', 'vendorFooter'], callback);
});
gulp.task('theme', function(callback){
    runSequence(['themeCss', 'themeJs', 'fonts'], callback);
});


//  ---------------------
//  Vendor Tasks.
//  ---------------------
gulp.task('vendorHead', function() {
    if ( gc.debug ) console.log('Compiling the vendor/libraries:', gc.files.js.head);

    return gulp.src( gc.files.js.head )
        .pipe( concat( gc.output.names.js.head ) )
        .pipe( gulp.dest( gc.output.directories.js ) )
        .pipe( rename({
            suffix: ".min"
        }) )
        .pipe( uglify() )
        .pipe( gulp.dest( gc.output.directories.js) );
});

gulp.task('vendorFooter', function() {
    if ( gc.debug ) console.log('Compiling the vendor/libraries:', gc.files.js.footer);

    return gulp.src(gc.files.js.footer)
        .pipe( concat( gc.output.names.js.footer ) )
        .pipe( gulp.dest( gc.output.directories.js ) )
        .pipe( rename({
            suffix: '.min'
        }) )
        .pipe( uglify() )
        .pipe( gulp.dest( gc.output.directories.js) );
});


//  ---------------------
//  Theme Tasks.
//  ---------------------
gulp.task('themeCss', function() {
    if ( gc.debug ) console.log('Compiling the Theme CSS files:', gc.files.css.head);

    let errorFree = true;
    let onError = function(err) {
        errorFree = false;

        let s = err.message.replace( "\u001b[31m","").replace("\u001b[0m","");
        let match = s.match( /.*error \s*(.*) \((.*)/ );

        notify.onError({
            title:    "themeCss",
            subtitle: match && match[1] && "File: " + match[1] || "Error",
            message:  match && match[2] || s,
            sound: false
        })(err);

        this.emit('end');
    };

    return gulp.src( gc.files.css.head )
        .pipe( plumber({
            errorHandler: onError,
        }) )
        .pipe( gulpIf("*.scss", compass({
            config_file: './compass-config.rb',
            sass: 'scss',
            css: gc.output.directories.css + "/compass"
        }) ) )
        .pipe( gulpIf("*.less", less() ) )
        .pipe( concatCss( gc.output.names.css.head, {
            rebaseUrls: false
        } ) )
        .pipe( autoprefix({
            browsers: ['last 2 versions', 'safari 5', 'ie 7', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'],
        }))
        .pipe( gulp.dest( gc.output.names.css.head ) )
        .pipe( rename({
            suffix: '.min'
        }))
        .pipe( cssNano({
            zindex: false,
            reduceIdents: {
                keyframes: false
            },
            discardUnused: {
                keyframes: false
            }
        }) )
        .pipe( notify( function(f) {
            return errorFree ? {
                title: 'themeCss',
                subtitle: 'success',
                message: 'SCSS ready',
            }
            : false ;
        }))
        .pipe( gulp.dest( gc.output.directories.css ) );
});


gulp.task('themeJs', function() {
    if ( gc.debug ) console.log('Compiling the Theme JS files:', gc.files.js.footer);

    let errorFree = true;
    let onError = function(err) {
        errorFree = false;

        let filePath = err.message.split("/");
        let match = filePath[ filePath.length-1 ].match(/(.*): (.*)/);
        let subtitle = match && match[1] && 'File: ${match[1]}' || "Error";
        let message = match && match[2] || err.message;

        console.log(filePath[ filePath.length-1 ]);
        notify.onError({
            title:    "themeJs",
            subtitle: subtitle,
            message:  message,
            sound: false
        })(err);

        this.emit('end');
    };

    return gulp.src( gc.files.js.footer )
        .pipe( plumber({
            errorHandler: onError,
        }) )
        .pipe( babel({
            presets: ["es2015", "stage-0"],
            // plugins: ["transform-runtime"],
        }))
        .pipe( concat( gc.output.names.js.footer ) )
        .pipe( gulp.dest( gc.output.directories.js ) )
        .pipe( rename({
            suffix: '.min'
        }))
        //.pipe( rename('theme.min.js') )
        .pipe( uglify() )
        .pipe( notify( function(f) {
            return errorFree ? {
                title: 'themeJs',
                subtitle: 'success',
                message: 'JS ready',
            }
            : false ;
        }))
        .pipe( plumber.stop() )
        .pipe( gulp.dest( gc.output.directories.js) );
});


// Fonts
gulp.task('fonts', function() {
    if ( gc.debug ) console.log('Compiling the Theme Fonts files:', gc.files.fonts);
    return gulp.src( gc.files.fonts )
        .pipe( gulp.dest( gc.output.directories.fonts ));
});



// #######################################        Watchers       ###########################################
gulp.task('watch', function() {
    gulp.watch("scss/*.scss", ['themeCss'] );
    gulp.watch("scripts/*.js", ['themeJs'] );
});

