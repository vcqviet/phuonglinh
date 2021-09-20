const gulp = require('gulp');
const sass = require('gulp-sass');
const imagemin = require('gulp-imagemin');
const cache = require('gulp-cache');
const del = require('del');
const minify = require('gulp-minify');
const eslint = require('gulp-eslint');


gulp.task('admin:sass', function () {
    return gulp.src('../symfony/src/Public/admin/scss/**/*.scss')
        .pipe(sass({ outputStyle: 'compressed' }))
        .pipe(gulp.dest('assets/admin/css'));
});
gulp.task('admin:images', function () {
    return gulp.src('../symfony/src/Public/admin/img/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(cache(imagemin({
            interlaced: true
        })))
        .pipe(gulp.dest('assets/admin/img'))
});
gulp.task('admin:fonts', function () {
    return gulp.src('../symfony/src/Public/admin/fonts/**/*')
        .pipe(gulp.dest('assets/admin/webfonts'))
});
gulp.task('admin:js', function () {
    return gulp.src('../symfony/src/Public/admin/js/*.js')
        .pipe(minify())
        .pipe(gulp.dest('assets/admin/js'))
});


gulp.task('app:css', function () {
    return gulp.src('../symfony/src/Public/app/css/**/*')
        .pipe(gulp.dest('./css'));
});
gulp.task('app:sass', function () {
    return gulp.src('../symfony/src/Public/app/scss/**/*.+(scss|sass|css)')
        .pipe(sass({ outputStyle: 'compressed' }))
        .pipe(gulp.dest('assets/css'));
});
gulp.task('app:images', function () {
    return gulp.src('../symfony/src/Public/app/img/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(cache(imagemin({
            interlaced: true
        })))
        .pipe(gulp.dest('assets/img'))
});
gulp.task('app:js', function () {
    return gulp.src('../symfony/src/Public/app/js/*.js')
        .pipe(minify())
        .pipe(gulp.dest('assets/js'))
});
gulp.task('watch', gulp.series(['admin:sass', 'admin:images', 'admin:fonts', 'admin:js', 'app:css', 'app:sass', 'app:images', 'app:js'], function () {
    gulp.watch('../symfony/src/Public/**/*', gulp.series(['watch'], function () { }));
}));


gulp.task('clean:dist', function () {
    return del.sync('assets');
});

gulp.task('build', gulp.series(['admin:sass', 'admin:images', 'admin:fonts', 'app:css', 'admin:js', 'app:sass', 'app:images', 'app:js'], async function () {
    console.log('build completed !');
}));

gulp.task('default', gulp.series(['watch'], function () {
    console.log('build-dev completed !');
}));