
/*
 * This file is part of the `src-run/web-app-grunt` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

"use strict";

import del  from 'del';
import gulp from 'gulp';
import browserify from 'browserify';
import babelify from 'babelify';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import pkg  from './package.json';
import PluginLoader from 'gulp-load-plugins';
import ConfigParser from './.gulp/configuration-parser.js';

let p = PluginLoader();
let c = new ConfigParser('./.gulp/config.json');

gulp.task('clean-scripts', () => {
    return del(c.paths(['public.scripts']));
});

gulp.task('clean-styles', () => {
    return del(c.paths(['public.styles']));
});

gulp.task('clean-images', () => {
    return del(c.paths(['public.images']));
});

gulp.task('clean-fonts', () => {
    return del(c.paths(['public.fonts']));
});

gulp.task('clean', gulp.parallel(
    'clean-styles',
    'clean-scripts',
    'clean-images',
    'clean-fonts'
));

gulp.task('tests-styles', () => {
    return gulp.src(c.globs(['tests.styles']))
        .pipe(p.sassLint())
        .on("error", p.notify.onError("Error: <%= error.message %>"));
});

gulp.task('tests-scripts', () => {
    return gulp.src(c.globs(['tests.scripts']))
        .pipe(p.jscs({fix: true, configPath: c.option('jscs-rc')}))
        .pipe(p.jscs.reporter())
        .pipe(p.jscs.reporter('fail'));
});

gulp.task('tests', gulp.parallel(
    'tests-styles',
    'tests-scripts'
));

gulp.task('assets-images', () => {
    return gulp.src(c.files(['plugins.images', 'app.images']))
        .pipe(gulp.dest(c.path('public.images')));
});

gulp.task('assets-fonts', () => {
    return gulp.src(c.files(['plugins.fonts', 'app.fonts']))
        .pipe(gulp.dest(c.path('public.fonts')));
});

gulp.task('assets', gulp.parallel(
    'assets-images',
    'assets-fonts'
));

gulp.task('make-styles', () => {
    return gulp.src(c.file('app.styles'))
        .pipe(p.sourcemaps.init())
        .pipe(p.sass({includePaths: c.paths(['components'])}))
        .pipe(p.decomment.text())
        .pipe(p.banner(c.option('banner-text'), {pkg : pkg}))
        .pipe(p.autoprefixer(c.option('prefix-rule-set')))
        .pipe(p.csscomb({config: c.option('css-comb-rc')}))
        .pipe(gulp.dest(c.path('public.styles')))
        .pipe(p.rename({suffix: '.min'}))
        .pipe(p.cleanCss())
        .pipe(p.sourcemaps.write('.'))
        .pipe(gulp.dest(c.path('public.styles')))
        .pipe(p.notify({onLast: true}))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
});

gulp.task('make-scripts-app-core', () => {
    return browserify({entries: c.file('app.scripts'), debug: true})
        .transform(babelify, {presets: ["es2015"]})
        .bundle()
        .pipe(source('app-core.js'))
        .pipe(buffer())
        .pipe(p.decomment())
        .pipe(p.babel({presets: ['es2015']}))
        .pipe(p.banner(c.option('banner-text'), {pkg : pkg}))
        .pipe(gulp.dest(c.path('public.scripts')))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
});

gulp.task('make-scripts-app-plugins', () => {
    return gulp.src(c.files(['plugins.scripts']))
        .pipe(p.sourcemaps.init())
        .pipe(p.concat('app-plugins.js'))
        .pipe(p.decomment())
        .pipe(p.banner(c.option('banner-text'), {pkg : pkg}))
        .pipe(gulp.dest(c.path('public.scripts')))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
});

gulp.task('make-scripts-app', () => {
    return gulp.src([c.path('public.scripts', {post: 'app-plugins.js'}), c.path('public.scripts', {post: 'app-core.js'})])
        .pipe(p.sourcemaps.init())
        .pipe(p.concatSourcemap('app.js', {sourcesContent: true}))
        .pipe(p.decomment())
        .pipe(p.banner(c.option('banner-text'), {pkg : pkg}))
        .pipe(gulp.dest(c.path('public.scripts')))
        .pipe(p.rename({suffix: '.min'}))
        .pipe(p.uglify())
        .pipe(p.sourcemaps.write('.'))
        .pipe(gulp.dest(c.path('public.scripts')))
        .pipe(p.notify({onLast: true}))
        .on("error", p.notify.onError("Error: <%= error.message %>"));
});

gulp.task('make-scripts', gulp.series(
    gulp.parallel('make-scripts-app-core', 'make-scripts-app-plugins'),
    'make-scripts-app'
));

gulp.task('make', gulp.parallel(
    'make-styles',
    'make-scripts'
));

gulp.task('build', gulp.series(
    gulp.parallel('tests', 'clean'),
    gulp.parallel('make', 'assets')
));

gulp.task('watch', () => {
    gulp.watch(c.globs(['tests.styles']),  gulp.series('tests-styles', 'make-styles'));
    gulp.watch(c.globs(['tests.scripts']), gulp.series('tests-scripts', 'make-scripts'));
});

gulp.task('default', gulp.series(
    'build',
    'watch'
));

/* EOF */
