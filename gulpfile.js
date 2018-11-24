const gulp = require('gulp');
const less = require('gulp-less');
const browserSync = require('browser-sync').create();
const header = require('gulp-header');
const cleanCSS = require('gulp-clean-css');
const rename = require("gulp-rename");
const pkg = require('./package.json');

// Set the banner content
const banner = ['/*!\n',
    ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
    ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
    ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n',
    ' */\n',
    ''
].join('');

const destFolder = "public/";
const srcFolder = "src/Client/";
// Compile LESS files from /less into /css
gulp.task('less', function() {
    return gulp.src(srcFolder + 'Less/SbAdmin2.less')
        .pipe(less())
        .pipe(header(banner, { pkg: pkg }))
        .pipe(gulp.dest(destFolder + 'css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// Minify compiled CSS
gulp.task('minify-css', ['less'], function() {
    return gulp.src(destFolder + 'css/SbAdmin2.css')
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(destFolder + 'css'))
        .pipe(browserSync.reload({
            stream: true
        }))
});

// Copy vendor libraries from /bower_components into /vendor
gulp.task('copy', function() {
    gulp.src(['bower_components/bootstrap/dist/**/*', '!**/npm.js', '!**/bootstrap-theme.*', '!**/*.map'])
        .pipe(gulp.dest(destFolder + 'vendor/bootstrap'));

    gulp.src(['bower_components/bootstrap-social/*.css', 'bower_components/bootstrap-social/*.less', 'bower_components/bootstrap-social/*.scss'])
        .pipe(gulp.dest(destFolder + 'vendor/bootstrap-social'));

    gulp.src(['bower_components/datatables/media/**/*'])
        .pipe(gulp.dest(destFolder + 'vendor/datatables'));

    gulp.src(['bower_components/datatables-plugins/integration/bootstrap/3/*'])
        .pipe(gulp.dest(destFolder + 'vendor/datatables-plugins'));

    gulp.src(['bower_components/datatables-responsive/css/*', 'bower_components/datatables-responsive/js/*'])
        .pipe(gulp.dest(destFolder + 'vendor/datatables-responsive'));

    gulp.src(['bower_components/flot/*.js'])
        .pipe(gulp.dest(destFolder + 'vendor/flot'));

    gulp.src(['bower_components/flot.tooltip/js/*.js'])
        .pipe(gulp.dest(destFolder + 'vendor/flot-tooltip'));

    gulp.src(['bower_components/font-awesome/**/*', '!bower_components/font-awesome/*.json', '!bower_components/font-awesome/.*'])
        .pipe(gulp.dest(destFolder + 'vendor/font-awesome'));

    gulp.src(['bower_components/jquery/dist/jquery.js', 'bower_components/jquery/dist/jquery.min.js'])
        .pipe(gulp.dest(destFolder + 'vendor/jquery'));

    gulp.src(['bower_components/metisMenu/dist/*'])
        .pipe(gulp.dest(destFolder + 'vendor/metisMenu'));

    gulp.src(['bower_components/morrisjs/*.js', 'bower_components/morrisjs/*.css', '!bower_components/morrisjs/Gruntfile.js'])
        .pipe(gulp.dest(destFolder + 'vendor/morrisjs'));

    gulp.src(['bower_components/raphael/raphael.js', 'bower_components/raphael/raphael.min.js'])
        .pipe(gulp.dest(destFolder + 'vendor/raphael'));

    gulp.src(['bower_components/eonasdan-bootstrap-datetimepicker/build/**/*'])
        .pipe(gulp.dest(destFolder + 'vendor/datetimepicker'));

    gulp.src(['bower_components/moment/min/**/*'])
        .pipe(gulp.dest(destFolder + 'vendor/moment'));

    gulp.src(['bower_components/country-region-dropdown-menu/dist/**/*'])
        .pipe(gulp.dest(destFolder + 'vendor/countries'));
});

// Run everything
gulp.task('default', ['less', 'minify-css', 'copy']);
