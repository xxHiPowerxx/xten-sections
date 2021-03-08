'use strict';

var gulp = require('gulp');
var newer = require('gulp-newer');
var sass = require('gulp-sass');
var cssnano = require('gulp-cssnano');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var del = require("del");

// Configuration file to keep your code DRY
var cfg = require("./gulpconfig.json");
var paths = cfg.paths;

// Styles/CSS
gulp.task('styles', function (done) {
	var stream = gulp
		.src([
			`${paths.sass}/**/*.scss`,
			'!src/sass/import/', //exclude import'
			'!src/sass/import/**/*'
		])
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(
			autoprefixer({
				cascade: false
			})
		)
		.pipe(
			cssnano({
				discardComments: { removeAll: true },
				convertValues: { length: false }
			})
		)
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(paths.css));

	return stream;
});

// Scripts/JS
gulp.task('scripts', function (done) {
	gulp
		.src([
			'./src/js/**/*.js',
			'!src/js/import/', //exclude import'
			'!src/js/import/**/*'
		])
		.pipe(uglify())
		.pipe(gulp.dest('./assets/js/'));

	done();
});

// Images
var imgDest = './assets/img/';
gulp.task('images', function (done) {
	gulp
		.src('./src/img/**/*')
		.pipe(newer(imgDest))
		.pipe(imagemin())
		.pipe(gulp.dest(imgDest));

	done();
});

gulp.task('watch', function () {
	gulp.watch('./src/sass/**/*.scss', gulp.series('styles'));
	gulp.watch('./src/js/**/*.js', gulp.series('scripts'));
	gulp.watch('./src/img/**/*', gulp.series('images'));
});


// Run:
// gulp watch-bs
// Starts watcher with browser-sync. Browser-sync reloads page automatically on your browser
// gulp.task("watch", gulp.parallel("watch", "scripts", "styles", "imagemin"));
// Run:
// Deleting any file inside the /dist folder
gulp.task("clean-dist", function () {
	return del([`${paths.dist}/**`]);
});

// Deleting any file inside the /src folder
gulp.task("clean-assets", function () {
	return del(["assets/**/*"]);
});

// Run
// gulp dist
// Copies the files to the /dist folder for distribution as simple theme
gulp.task(
	"create-dist",
	gulp.series("clean-dist", function copyToDistFolder() {
		const ignorePaths = [
			`!${paths.node}`,
			`!${paths.src}`,
			`!${paths.dist}`,
			`!${paths.dist}/**`
		],
			ignoreFiles = [
				"!package.json",
				"!package-lock.json",
				"!gulpfile.js",
				"!gulpconfig.json",
				"!jshintignore",
				"!.gitignore"
			];

		console.log({ ignorePaths, ignoreFiles });

		return gulp
			.src(["**/*", "assets/**/**.*", "*", ...ignorePaths, ...ignoreFiles], {
				buffer: false
			})
			.pipe(gulp.dest(paths.dist));
	})
);

// Build project
gulp.task(
	"build",
	gulp.series(
		"clean-assets",
		gulp.series("styles", "scripts", "images", "create-dist")
	)
);