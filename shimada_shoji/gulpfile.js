var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    browserSync = require('browser-sync').create();

var DEST = 'build/';

gulp.task('scripts', function() {
    return gulp.src([
            'src/js/helpers/*.js',
            'src/js/*.js',
        ])
        .pipe(concat('custom.js'))
        .pipe(gulp.dest(DEST + '/js'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest(DEST + '/js'));
    // .pipe(browserSync.stream());
});

// TODO: Maybe we can simplify how sass compile the minify and unminify version
var compileSASS = function(filename, options) {
    return gulp.src('src/scss/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSLight = function(filename, options) {
    return gulp.src('src/scss_light/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSPlayful = function(filename, options) {
    return gulp.src('src/scss_playful/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSVintage = function(filename, options) {
    return gulp.src('src/scss_vintage/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSPink = function(filename, options) {
    return gulp.src('src/scss_pink/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSElegant = function(filename, options) {
    return gulp.src('src/scss_elegant/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSDecode = function(filename, options) {
    return gulp.src('src/scss_decode/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSDiaries = function(filename, options) {
    return gulp.src('src/scss_diaries/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSWitty = function(filename, options) {
    return gulp.src('src/scss_witty/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
var compileSASSAngel = function(filename, options) {
    return gulp.src('src/scss_angel/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(concat(filename))
        .pipe(gulp.dest(DEST + '/css'));
    // .pipe(autoprefixer('last 2 versions', '> 5%'))
    // .pipe(concat(filename))
    // .pipe(gulp.dest(DEST + '/css'));
    // .pipe(browserSync.stream());
};
gulp.task('sass', function() {
    return compileSASS('custom.css', {});
});

gulp.task('sass-minify', function() {
    return compileSASS('custom.min.css', { style: 'compressed' });
});

gulp.task('sass-light', function() {
    return compileSASSLight('light_theme.css', {});
});

gulp.task('sass-minify-light', function() {
    return compileSASSLight('light_theme.min.css', { style: 'compressed' });
});

gulp.task('sass-playful', function() {
    return compileSASSPlayful('playful_theme.css', {});
});

gulp.task('sass-minify-playful', function() {
    return compileSASSPlayful('playful_theme.min.css', { style: 'compressed' });
});

gulp.task('sass-vintage', function() {
    return compileSASSVintage('vintage_theme.css', {});
});

gulp.task('sass-minify-vintage', function() {
    return compileSASSVintage('vintage_theme.min.css', { style: 'compressed' });
});

gulp.task('sass-pink', function() {
    return compileSASSPink('pink_theme.css', {});
});

gulp.task('sass-minify-pink', function() {
    return compileSASSPink('pink_theme.min.css', { style: 'compressed' });
});

gulp.task('sass-elegant', function() {
    return compileSASSElegant('elegant_theme.css', {});
});

gulp.task('sass-minify-elegant', function() {
    return compileSASSElegant('elegant_theme.min.css', { style: 'compressed' });
});
gulp.task('sass-decode', function() {
    return compileSASSDecode('decode_theme.css', {});
});

gulp.task('sass-minify-decode', function() {
    return compileSASSDecode('decode_theme.min.css', { style: 'compressed' });
});
gulp.task('sass-diaries', function() {
    return compileSASSDiaries('diaries_theme.css', {});
});

gulp.task('sass-minify-diaries', function() {
    return compileSASSDiaries('diaries_theme.min.css', { style: 'compressed' });
});
gulp.task('sass-witty', function() {
    return compileSASSWitty('witty_theme.css', {});
});

gulp.task('sass-minify-witty', function() {
    return compileSASSWitty('witty_theme.min.css', { style: 'compressed' });
});
gulp.task('sass-angel', function() {
    return compileSASSAngel('angel_theme.css', {});
});

gulp.task('sass-minify-angel', function() {
    return compileSASSAngel('angel_theme.min.css', { style: 'compressed' });
});

gulp.task('browser-sync', function() {
    browserSync.init({
        server: {
            baseDir: './'
        },
        startPath: './production/index.html'
    });
});

gulp.task('watch', function() {
    // Watch .html files
    gulp.watch('production/*.html', browserSync.reload);
    // Watch .js files
    gulp.watch('src/js/*.js', ['scripts']);
    // Watch .scss files
    gulp.watch('src/scss/*.scss', ['sass', 'sass-minify']);
    // Watch .scss files
    gulp.watch('src/scss_light/*.scss', ['sass-light', 'sass-minify-light']);
    // Watch .scss files
    gulp.watch('src/scss_playful/*.scss', ['sass-playful', 'sass-minify-playful']);
    // Watch .scss files
    gulp.watch('src/scss_vintage/*.scss', ['sass-vintage', 'sass-minify-vintage']);
    // Watch .scss files
    gulp.watch('src/scss_pink/*.scss', ['sass-pink', 'sass-minify-pink']);
    // Watch .scss files
    gulp.watch('src/scss_elegant/*.scss', ['sass-elegant', 'sass-minify-elegant']);
    // Watch .scss files
    gulp.watch('src/scss_decode/*.scss', ['sass-decode', 'sass-minify-decode']);
    // Watch .scss files
    gulp.watch('src/scss_diaries/*.scss', ['sass-diaries', 'sass-minify-diaries']);
    // Watch .scss files
    gulp.watch('src/scss_witty/*.scss', ['sass-witty', 'sass-minify-diaries']);
    // Watch .scss files
    gulp.watch('src/scss_angel/*.scss', ['sass-angel', 'sass-minify-angel']);
});

// Default Task
gulp.task('default', [ /*'browser-sync', */ 'scripts', 'sass', 'sass-minify', 'sass-light', 'sass-minify-light', 'sass-playful', 'sass-minify-playful', 'sass-vintage', 'sass-minify-vintage', 'sass-pink', 'sass-minify-pink', 'sass-elegant', 'sass-minify-elegant', 'sass-decode', 'sass-minify-decode', 'sass-diaries', 'sass-minify-diaries', 'sass-witty', 'sass-minify-witty', 'sass-angel', 'sass-minify-angel','watch']);