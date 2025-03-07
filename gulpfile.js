import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import plumber from 'gulp-plumber'
import cache from 'gulp-cache';
import notify from 'gulp-notify';
import imagemin from 'gulp-imagemin';

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
}

export function css( done ) {
    src(paths.scss, {sourcemaps: true})
        .pipe(plumber())
        .pipe( sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError) )
        .pipe( dest('./public/build/css', {sourcemaps: '.'}) );
    done()
}

export function imagenes(done) {
    src(paths.imagenes)
    .pipe(cache(imagemin({ optimizationLevel: 3 })))
    .pipe(dest('./public/build/img'));
    done();
}

export function js( done ) {
    src(paths.js)
    .pipe(terser())
    .pipe(dest('./public/build/js'))
    done()
}

export function dev() {
    watch( paths.scss, css );
    watch( paths.js, js );
    watch(paths.imagenes, imagenes);
}

export const build = series(js, css, imagenes); // Solo compila
export default series( js, css, imagenes, dev )