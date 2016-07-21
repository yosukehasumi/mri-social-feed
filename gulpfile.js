// modules
var gulp          = require('gulp');
var gutil         = require('gulp-util');
var sass          = require('gulp-sass');
var autoprefixer  = require('gulp-autoprefixer');
var coffee        = require('gulp-coffee');
var minifyCSS     = require('gulp-minify-css');
var uglify        = require('gulp-uglify');
var concat        = require('gulp-concat');
var merge2        = require('merge2');

// tasks
gulp.task('scss', function () {
  gulp.src('css/scss/mri-social-feed.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer({ browsers: ['> 5%', 'last 2 versions'] }))
    .pipe(minifyCSS({keepSpecialComments: '*', keepBreaks: false}))
    .pipe(gulp.dest('css'));
});

gulp.task('library', function () {
  gulp.src([
      'js/library/*.js'
    ])
    .pipe(concat('library.js'))
    .pipe(uglify())
    .pipe(gulp.dest('js/'));
});

gulp.task('javascript', function () {
  var includesStream = gulp.src('js/library.js');

  var coffeeStream = gulp.src('js/coffee/master.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(uglify());

  merge2(includesStream, coffeeStream)
    .pipe(concat('mri-social-feed.js'))
    .pipe(gulp.dest('js/'));
});

// watch
gulp.task('default', function () {
  gulp.watch('css/scss/**/*.scss', ['scss']);
  gulp.watch('js/library/**/*.js', ['library']);
  gulp.watch('js/coffee/**/*.coffee', ['javascript']);
});
