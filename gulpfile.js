// Include the required packages.
var gulp = require('gulp');
var watch = require('gulp-watch');
var connect = require('gulp-connect-php');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var stylus = require('gulp-stylus');
var nib = require('nib'); // Cross-browser CSS3 mixins for Stylus
var autoprefixer = require('gulp-autoprefixer');
var gutil = require( 'gulp-util' );
var sftp = require( 'gulp-sftp' );
var browserSync = require('browser-sync').create();

function sftpOpts(path) {
    return {
        host: "my.remote.host.com",
        user: "remoteuser",
        remotePath: "/base/project/path/" + (path || "")
    };
}

// Process Stylus and stream changes to browser
gulp.task('process-stylus', function () {
  gulp.src('./assets/stylus/main.styl')
    .pipe(stylus( {
      compress: true
    }))
    .pipe(concat('main.css'))
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
   }))
    .pipe(gulp.dest('./assets/dist/'))
    .pipe(browserSync.stream());
});

// Uglify JavaScript and stream changes to browser
gulp.task('process-javascript', function () {
  gulp.src(['./assets/js/**/*.js'])
    .pipe(concat('app.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/dist/'))
    .pipe(browserSync.stream());
});

// Watch *.php files in the sites folder and stream changes to browser
gulp.task('php', function(){
  gulp.src('site/*/*.php')
    .pipe(browserSync.stream());
});

gulp.task('watch', function() {
  gulp.watch('./assets/stylus/**/*.styl', ['process-stylus']);
  gulp.watch('./assets/js/**/*.js', ['process-javascript']);
  gulp.watch('site/*/*.php', ['php']);
});

// Start loal server
gulp.task('connect', function() {
    connect.server();
});

// Open site with BrowserSync enabled
gulp.task('open', function(){
  browserSync.init({
      proxy: "http://127.0.0.1:8000",
      notify: false
  });
});

// Default gulp task to run
gulp.task('default', ['watch', 'connect', 'open']);

// Deploy the site through FTP to a given webserver
gulp.task( 'deploy', function() {

    var globs = [
        '**',
        '.htaccess',
        '!**/node_modules/**'
    ];

    function sftpOpts(path) {
      return {
          host: "",
          user: "",
          password: "",
          remotePath: "" + (path || "")
      };
    }

    return gulp.src( globs )
      .pipe(sftp(sftpOpts("test")));

} );