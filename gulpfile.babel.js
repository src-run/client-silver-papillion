
/*
 * This file is part of the `src-run/web-app-grunt` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

"use strict";

import 'del';
import 'gulp';
import plugins from 'gulp-load-plugins';
import pkg from './package.json';
import ConfigParser from './.gulp/configuration-parser.js';

var p = plugins();
var c = new ConfigParser('./.gulp/config.json');

function cleanScripts(done) {
    return del(c.paths(['public.scripts']));
}

function cleanStyles(done) {
    return del(c.paths(['public.styles']));
}

function cleanImages(done) {
    return del(c.paths(['public.images']));
}

function cleanFonts(done) {
    return del(c.paths(['public.fonts']));
}

function testStyles() {
    return gulp.src(c.path('app', { post: '**/*.scss'}))
        .pipe(p.sassLint())
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

function compileStyles() {
    return gulp.src(c.file('app.styles'))
        .pipe(p.sourcemaps.init())
        .pipe(p.sass({
            includePaths: c.paths(['components'])
        }))
        .pipe(p.decomment.text())
        .pipe(p.banner(c.option('banner-text'), {
            pkg : pkg
        }))
        .pipe(p.autoprefixer(c.option('prefix-rule-set')))
        .pipe(p.csscomb({
            config: c.option('css-comb-rc')
        }))
        .pipe(gulp.dest(c.path('public.styles')))
        .pipe(p.rename({
            suffix: '.min'
        }))
        .pipe(p.minifyCss())
        .pipe(p.sourcemaps.write('.'))
        .pipe(gulp.dest(c.path('public.styles')))
        .pipe(p.notify({
            message: "Generated style <%= file.relative %> @ <%= options.date %>",
            templateOptions: {
                date: new Date()
            },
            onLast: true
        }))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

function watchStyles(){
    gulp.watch(c.paths(['app'], { post: '**/*.scss'}))
        .on('change', gulp.series(testStyles, cleanStyles, compileStyles));
}

function testScripts() {
    return gulp.src([c.path('app', { post: '**/*.js'}), '.gulp/**/*.js', 'gulpfile.babel.js'])
        .pipe(p.jslint())
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

function compileScripts() {
    return gulp.src(c.files(['plugins.scripts', 'app.scripts']))
        .pipe(p.sourcemaps.init())
        .pipe(p.concatSourcemap('app.js', {
            sourcesContent: true
        }))
        .pipe(p.decomment())
        .pipe(p.banner(c.option('banner-text'), {
            pkg : pkg
        }))
        .pipe(gulp.dest(c.path('public.scripts')))
        .pipe(p.rename({
            suffix: '.min'
        }))
        .pipe(p.uglify())
        .pipe(p.sourcemaps.write('.'))
        .pipe(gulp.dest(c.path('public.scripts')))
        .pipe(p.notify({
            message: "Generated script <%= file.relative %> @ <%= options.date %>",
            templateOptions: {
                date: new Date()
            },
            onLast: true
        }))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

function watchScripts(){
    gulp.watch(c.paths(['app'], { post: '**/*.js'}))
        .on('change', gulp.series(testScripts, cleanScripts, compileScripts));
}

function copyImages() {
    return gulp.src(c.files(['plugins.images', 'app.images']))
        .pipe(gulp.dest(c.path('public.images')))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

function copyFonts() {
    return gulp.src(c.files(['plugins.fonts', 'app.fonts']))
        .pipe(gulp.dest(c.path('public.fonts')))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
}

gulp.task('clean', gulp.parallel(cleanScripts, cleanStyles, cleanImages, cleanFonts));
gulp.task('clean').description = 'Remove all files generated from a previous build.';

gulp.task('scripts', gulp.series(testScripts, cleanScripts, compileScripts));
gulp.task('scripts').description = 'Compile javascripts into concatenated and minified versions.';

gulp.task('test-scripts', testScripts);
gulp.task('test-scripts').description = 'Lint all javascripts.';

gulp.task('watch-scripts', watchScripts);
gulp.task('watch-scripts').description = 'Watch all javascripts.';

gulp.task('styles', gulp.series(testStyles, cleanStyles, compileStyles));
gulp.task('styles').description = 'Compile stylesheets into concatenated and minified versions.';

gulp.task('test-styles', testStyles);
gulp.task('test-styles').description = 'Lint all stylesheets.';

gulp.task('watch-styles', watchStyles);
gulp.task('watch-styles').description = 'Watch all stylesheets.';

gulp.task('watch', gulp.parallel(watchStyles, watchScripts));
gulp.task('watch').description = 'Watch all stylesheets and javascripts.';

gulp.task('test', gulp.parallel(testStyles, testScripts));
gulp.task('test').description = 'Lint all stylesheets and javascripts.';

gulp.task('images', gulp.series(cleanImages, copyImages));
gulp.task('images').description = 'Copy plugin images to assets directory.';

gulp.task('fonts', gulp.series(cleanFonts, copyFonts));
gulp.task('fonts').description = 'Copy plugin fonts to assets directory.';

gulp.task('default', gulp.parallel('scripts', 'styles', 'images', 'fonts'));
gulp.task('default').description = 'Cleans prior build files, compile styles and scripts, copy required image and font files.';

/* EOF */
