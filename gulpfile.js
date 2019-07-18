const gulp         = require('gulp');
const rtlcss       = require('gulp-rtlcss');
const sass         = require('gulp-sass');
const sourcemaps   = require('gulp-sourcemaps');
const jshint       = require('gulp-jshint');
const concat       = require('gulp-concat');
const uglify       = require('gulp-uglify');
const sort         = require('gulp-sort');
const wppot        = require('gulp-wp-pot');
const gettext      = require('gulp-gettext');
const plumber      = require('gulp-plumber');
const autoprefixer = require('gulp-autoprefixer');
const gutil        = require('gulp-util');
const rename       = require('gulp-rename');
const map          = require('map-stream');
const browserlist  = ['last 2 version', '> 1%'];

const errorreporter = map(function(file, cb) {
	if (file.jshint.success) {
		return cb(null, file);
	}

	console.log('JSHINT fail in', file.path);

	file.jshint.results.forEach(function (result) {
		if (!result.error) {
			return;
		}

		const err = result.error
		console.log(`  line ${err.line}, col ${err.character}, code ${err.code}, ${err.reason}`);
	});

	cb(null, file);
});

gulp.task('default', function() {
	console.log('Use the following commands');
	console.log('--------------------------');
	console.log('gulp compile-css    to compile the scss to css');
	console.log('gulp compile-js     to compile the js to min.js');
	console.log('gulp watch          to continue watching the files for changes');
	console.log('gulp wordpress-lang to compile the lsx-projects.pot, en_EN.po and en_EN.mo');
});

gulp.task('styles', function (done) {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/scss']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		.pipe(sourcemaps.write('maps'))
		.pipe(gulp.dest('assets/css')),
		done();
});

gulp.task('styles-rtl', function (done) {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(sass({
			outputStyle: 'compact',
			includePaths: ['assets/css/scss']
		}).on('error', gutil.log))
		.pipe(autoprefixer({
			browsers: browserlist,
			casacade: true
		}))
		.pipe(rtlcss())
		.pipe(rename({
			suffix: '-rtl'
		}))
		.pipe(gulp.dest('assets/css')),
		done();
});

gulp.task('compile-css', gulp.series( ['styles', 'styles-rtl'], function(done) {
	done();
}));

gulp.task('js', function(done) {
	return gulp.src('assets/js/src/lsx-projects.js')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(jshint())
		//.pipe(errorreporter)
		.pipe(concat('lsx-projects.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js')),
		done();
});

gulp.task('admin-js', function(done) {
	return gulp.src('assets/js/src/lsx-projects-admin.js')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(jshint())
		//.pipe(errorreporter)
		.pipe(concat('lsx-projects-admin.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js')),
		done();
});

gulp.task('scporder-js', function(done) {
	return gulp.src('assets/js/src/scporder.js')
		.pipe(plumber({
			errorHandler: function(err) {
				console.log(err);
				this.emit('end');
			}
		}))
		.pipe(jshint())
		//.pipe(errorreporter)
		.pipe(concat('scporder.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest('assets/js')),
		done();
});

gulp.task('compile-js', gulp.series( ['js', 'admin-js', 'scporder-js'] , function(done) {
	done();
}));

gulp.task('watch-css', function (done) {
	done();
	return gulp.watch('assets/css/**/*.scss',  gulp.series('compile-js'));
});

gulp.task('watch-js', function (done) {
	done();
	return gulp.watch('assets/js/src/**/*.js', gulp.series('compile-js'));
});

gulp.task('watch', gulp.series( ['watch-css', 'watch-js'] , function(done) {
	done();
}));

gulp.task('wordpress-pot', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx-projects',
			package: 'lsx-projects',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/lsx-projects.pot')),
		done();
});

gulp.task('wordpress-po', function(done) {
	return gulp.src('**/*.php')
		.pipe(sort())
		.pipe(wppot({
			domain: 'lsx-projects',
			package: 'lsx-projects',
			team: 'LightSpeed <webmaster@lsdev.biz>'
		}))
		.pipe(gulp.dest('languages/en_EN.po')),
		done();
});

gulp.task('wordpress-po-mo', gulp.series( ['wordpress-po'], function() {
	return gulp.src('languages/en_EN.po')
		.pipe(gettext())
		.pipe(gulp.dest('languages'));
}));

gulp.task('wordpress-lang', gulp.series( ['wordpress-pot', 'wordpress-po-mo'] , function(done) {
	done();
}));
