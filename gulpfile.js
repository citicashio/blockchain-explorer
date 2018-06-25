var gulp = require('gulp');
var less = require('gulp-less');
var babel = require('gulp-babel');
var concat = require('gulp-concat');
var prefix = require('gulp-autoprefixer');
var browserSync = require('browser-sync');
var cleanCSS = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');
var reload = browserSync.reload;


gulp.task('less', function() {
	return gulp.src('assets/less/*.less')
		.pipe(sourcemaps.init())
		.pipe(less())
		.pipe(prefix("last 2 versions", "> 1%", "ie 8", "Android 2", "Firefox ESR"))
		.pipe(cleanCSS({compatibility: 'ie8'})) 
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest('www/build/css'))
		.pipe(reload({stream:true}));
});

gulp.task('javascript', function() {
	return gulp.src('assets/js/*.js')
		.pipe(sourcemaps.init())
		.pipe(babel())
		.pipe(concat('main.js'))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('www/build/js'))
});

gulp.task('browser-sync', function() {
	browserSync({
		proxy: "localhost:8000"
	})
});

gulp.task('watch', function() {
	gulp.watch('assets/less/**', ['less']);
	gulp.watch('assets/js/**', ['javascript']);
	gulp.watch('www/build/css/**' ['minify-css']);
	gulp.watch(['../app/presenters/**/*.php', '../app/presenters/templates/**/*.latte', 'build/js/*.js']).on('change', browserSync.reload);
});


gulp.task('default', ['less', 'javascript', 'browser-sync', 'watch']);
