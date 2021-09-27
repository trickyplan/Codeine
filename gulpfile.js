    const { parallel } = require('gulp');
    const { series } = require('gulp');

    const gulp = require('gulp');
    const rename = require('gulp-rename');

    const sass = require('gulp-sass')(require('sass'));
    sass.compiler = require('node-sass');
    const less = require('gulp-less');
    const cleanCSS = require('gulp-clean-css');

    const terser = require('gulp-terser');

    const assets = 'src/Assets';

    function build_bootstrap_sass () {
        return gulp.src('node_modules/bootstrap/scss/bootstrap.scss')
            .pipe(sass().on('error', sass.logError))
            .pipe(gulp.dest(assets+'/bootstrap/css/'));
    }

    function build_bootstrap_less () {
        return gulp.src('node_modules/bootstrap/less/bootstrap.less')
            .pipe(less())
            .pipe(gulp.dest(assets+'/bootstrap/css/'))
            .pipe(cleanCSS())
            .pipe(rename({ suffix: '.min' }))
            .pipe(gulp.dest(
                function (file)
                {
                    return file.dirname;
                }
            ))
    }

    function copy_animate_css () {
        return gulp.src('node_modules/animate.css/animate.css')
            .pipe(gulp.dest(assets+'/animate/css'));
    };

    function copy_keymaster_js () {
        return gulp.src('node_modules/keymaster/keymaster.js')
            .pipe(gulp.dest(assets+'/keymaster/js/'));
    };

    function copy_bootstrap_js () {
        return gulp.src('node_modules/bootstrap/dist/js/*.js')
            .pipe(terser())
            .pipe(gulp.dest(assets+'/bootstrap/js'));
    }

    function copy_clipboard_js () {
        return gulp.src('node_modules/clipboard/dist/clipboard.js')
            .pipe(gulp.dest(assets+'/clipboard/js/'))
            .pipe(terser())
            .pipe(rename({ suffix: '.min' }))
            .pipe(gulp.dest(assets+'/clipboard/js'));
    }

    function copy_jquery_js () {
        return gulp.src('node_modules/jquery/dist/*.js')
            .pipe(terser())
            .pipe(gulp.dest(assets+'/jquery/js'));
    }

    function copy_moment_js () {
        return gulp.src(['node_modules/moment/moment.js', 'node_modules/moment/locale/ru.js'])
            .pipe(terser())
            .pipe(gulp.dest(assets+'/moment/js/'));
    }

    function copy_datetimepicker_js () {
        return gulp.src('node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')
            .pipe(gulp.dest(assets+'/eonasdan-bootstrap-datetimepicker/js/'));
    }

    function copy_datetimepicker_css () {
        return gulp.src('node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')
            .pipe(gulp.dest(assets+'/eonasdan-bootstrap-datetimepicker/css/'));
    }

    function build_fontawesome_sass () {
        return gulp.src(assets+'/font-awesome/scss/font-awesome.scss')
            .pipe(sass().on('error', sass.logError))
            .pipe(gulp.dest(assets+'/font-awesome/css/'));
    }

    function copy_fontawesome_fonts () {
        return gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/*')
            .pipe(gulp.dest(assets+'/font-awesome/fonts/'));
    }

exports.default = parallel(
    copy_animate_css,
    copy_bootstrap_js,
    copy_jquery_js,
    copy_moment_js,
    copy_datetimepicker_js,
    copy_clipboard_js,
    copy_keymaster_js,
    copy_datetimepicker_css,
    build_bootstrap_less,
    build_fontawesome_sass,
    copy_fontawesome_fonts
);