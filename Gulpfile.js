
/*
 * This file is part of the `src-run/web-app-grunt` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

"use strict";

var del       = require('del');
var spawn     = require('child_process').spawn;
var gulp      = require('gulp');
var cache     = require('gulp-cached');
var util      = require('gulp-util');
var debug     = require('gulp-debug');
var rename    = require('gulp-rename');
var notify    = require('gulp-notify');
var maps      = require('gulp-sourcemaps');
var concat    = require('gulp-concat-sourcemap');
var decomment = require('gulp-decomment');
var banner    = require('gulp-banner');
var uglify    = require('gulp-uglify');
var sass      = require('gulp-sass');
var sasslint  = require('gulp-sass-lint');
var jslint    = require('gulp-jslint');
var prefix    = require('gulp-autoprefixer');
var comb      = require('gulp-csscomb');
var minify    = require('gulp-minify-css');
var pkg       = require('./package.json');
var config    = new (require('./.gulp/configuration-parser.js'))();

function cleanScripts(done) {
    return spawn('rm', ['-fr', config.path('public.scripts')]);
}

function cleanStyles(done) {
    return spawn('rm', ['-fr', config.path('public.styles')]);
}

function cleanImages(done) {
    return spawn('rm', ['-fr', config.path('public.images')]);
}

function cleanFonts(done) {
    return spawn('rm', ['-fr', config.path('public.fonts')]);
}

function testStyles() {
    return gulp.src(config.path('app', { post: '**/*.scss'}))
        .pipe(sasslint())
        .on("error", notify.onError("Error: <%= error.message %>"));
}

function compileStyles() {
    return gulp.src(config.file('app.styles'))
        .pipe(maps.init())
        .pipe(sass({
            includePaths: config.paths(['components'])
        }))
        .pipe(decomment.text())
        .pipe(banner(config.option('banner-text'), {
            pkg : pkg
        }))
        .pipe(prefix(config.option('prefix-rule-set')))
        .pipe(comb({
            config: config.option('css-comb-rc')
        }))
        .pipe(gulp.dest(config.path('public.styles')))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(minify())
        .pipe(maps.write('.'))
        .pipe(gulp.dest(config.path('public.styles')))
        .pipe(notify({
            message: "Generated style <%= file.relative %> @ <%= options.date %>",
            templateOptions: {
                date: new Date()
            },
            onLast: true
        }))
        .on("error", notify.onError("Error: <%= error.message %>"));
}

function testScripts() {
    return gulp.src([config.path('app', { post: '**/*.js'}), '.gulp/**/*.js', 'Gulpfile.js'])
        .pipe(jslint())
        .on("error", notify.onError("Error: <%= error.message %>"));
}

function compileScripts() {
    return gulp.src(config.files(['plugins.scripts', 'app.scripts']))
        .pipe(maps.init())
        .pipe(concat('app.js', {
            sourcesContent: true
        }))
        .pipe(decomment())
        .pipe(banner(config.option('banner-text'), {
            pkg : pkg
        }))
        .pipe(gulp.dest(config.path('public.scripts')))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(uglify())
        .pipe(maps.write('.'))
        .pipe(gulp.dest(config.path('public.scripts')))
        .pipe(notify({
            message: "Generated script <%= file.relative %> @ <%= options.date %>",
            templateOptions: {
                date: new Date()
            },
            onLast: true
        }))
        .on("error", notify.onError("Error: <%= error.message %>"));
}

function copyImages() {
    return gulp.src(config.files(['plugins.images', 'app.images']))
        .pipe(gulp.dest(config.path('public.images')))
        .on("error", notify.onError("Error: <%= error.message %>"));
}

function copyFonts() {
    return gulp.src(config.files(['plugins.fonts', 'app.fonts']))
        .pipe(gulp.dest(config.path('public.fonts')))
        .on("error", notify.onError("Error: <%= error.message %>"));
}

gulp.task('clean', gulp.parallel(cleanScripts, cleanStyles, cleanImages, cleanFonts));
gulp.task('clean').description = 'Remove all files generated from a previous build.';

gulp.task('scripts', gulp.series(testScripts, cleanScripts, compileScripts));
gulp.task('scripts').description = 'Compile javascripts into concatenated and minified versions.';

gulp.task('test-scripts', gulp.series(testScripts));
gulp.task('test-scripts').description = 'Lint all javascripts.';

gulp.task('styles', gulp.series(testStyles, cleanStyles, compileStyles));
gulp.task('styles').description = 'Compile stylesheets into concatenated and minified versions.';

gulp.task('test-styles', gulp.series(testStyles));
gulp.task('test-styles').description = 'Lint all stylesheets.';

gulp.task('test', gulp.parallel(testStyles, testScripts));
gulp.task('test').description = 'Lint all stylesheets and javascripts.';

gulp.task('images', gulp.series(cleanImages, copyImages));
gulp.task('images').description = 'Copy plugin images to assets directory.';

gulp.task('fonts', gulp.series(cleanFonts, copyFonts));
gulp.task('fonts').description = 'Copy plugin fonts to assets directory.';

gulp.task('default', gulp.parallel('scripts', 'styles', 'images', 'fonts'));
gulp.task('default').description = 'Cleans prior build files, compile styles and scripts, copy required image and font files.';

/* EOF */
