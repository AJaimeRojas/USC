/* Config Gulp Task */
var gulp = require('gulp'),
	plumber = require('gulp-plumber'),
	nib = require('nib'),
	concat = require('gulp-concat'),
	findPort = require('find-port'),
	stylus = require('gulp-stylus'),
	sass = require('gulp-sass'),
mqpacker = require('css-mqpacker'),
assets = require('postcss-assets'),
autoprefixer = require('autoprefixer'),
	postcss = require('gulp-postcss'),
	shell = require('gulp-shell');


var serverPath  = 'web/assets';
var configPaths = {
    img: {
        src: 'assets/img/**/*.*',
        dest: serverPath + '/img'
    },
    css: {
        src: 'assets/css/**/*.*',
        dest: serverPath + '/css'
    },
    sass: {
        src:  'assets/sass/**/*.*',
        file:  'assets/sass/main.scss',
        dest: serverPath + '/css'
    },
    fonts: {
        src:  'assets/fonts/**/*.*',
        dest: serverPath + '/fonts'
    },
    pug: {
        src:  'pages/**/*.pug',
        dest: serverPath
    },
    js: {
        src:       'assets/scripts/**/*',
        dest:      serverPath + 'assets/js',
        bootstrap: {
            key:     '__bootstrap__',
            replace: '../../bower_components/bootstrap/js/dist'
        }
    },
    bower: 'bower_components'
};
// Funciones Globales
function makeid(){
	var text = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for( var i=0; i < 8; i++ )
		text += possible.charAt(Math.floor(Math.random() * possible.length));

	return text;
}

function StartsWith(s1, s2) {
	return (s1.length >= s2.length && s1.substr(0, s2.length) == s2);
}

/*var server_port = 9090;
findPort(server_port, server_port+10, function(ports) {
		server_port = ports[0];
		console.log('using port '+server_port)
	});

console.log('using port '+server_port)*/

//Directorios de sistema
var path = {
	master_styl: 'web/static/stylus/styles.styl',
	production_styl: 'web/static/stylus/production.styl',
	stylus_dirs: 'web/static/stylus/**/*.styl',
	stylus_blocks_dir: 'web/static/stylus/blocks/*.styl',
	css_builds: 'web/static/css/builds/*.css',
	css_builds_dir: 'web/static/css/builds/',
	css: 'web/static/css/'
}


// Runserver
gulp.task('run', function () {
	findPort(9002, 9090, function(ports) {
			var server_port = ports[0];
			console.log('[00:00:00] Corriendo en puerto '+server_port)
			return gulp.src('*.js', {read: false})
				.pipe(shell([
					'php app/console server:run 0:'+server_port
				], {
					templateData: {
						f: function (s) {
							return s.replace(/$/, '.bak')
						}
					}
				}));
		});

});


// Concat Css
gulp.task('concat_css', function () {
	setTimeout(function () {
		return gulp.src(path.css_builds)
		.pipe(plumber())
		.pipe(concat('blocks_styl.css'))
		.pipe(plumber.stop())
		.pipe(gulp.dest(path.css)).on('end', function(){
				console.log('>>>>>>>>>> Css Concatenados perfectamente...');
			})
	}, 256);
});

// Stylus Compiler
gulp.task('stylus', function () {
	return gulp.src(path.master_styl)
	.pipe(plumber())
	.pipe(stylus({ use: nib(),  import: ['nib']}))
	.pipe(plumber.stop())
	.pipe(gulp.dest(path.css))});

gulp.task('sasss', function() {
      var postCssOpts = [
      assets({ loadPaths: [configPaths.img.dest] }),
      autoprefixer({ browsers: ['last 2 versions', '> 2%'] }),
      //mqpacker
      ];
    //   if (!devBuild) {
    //     postCssOpts.push(cssnano);
    //   }
      return gulp.src(configPaths.sass.file)
        .pipe(plumber({ errorHandler: (err)=> {
        }}))
        .pipe(sass({
          outputStyle: 'nested',
          imagePath: configPaths.img.dest,
          precision: 3,
          errLogToConsole: true
        }))
        .pipe(postcss(postCssOpts))
        .pipe(plumber.stop())
        .pipe(gulp.dest(configPaths.sass.dest))
    });

// Stylus Compiler
gulp.task('stylus_prod', function () {
	return gulp.src(path.production_styl)
	//.pipe(plumber())
	.pipe(stylus({ use: nib(),  import: ['nib']}))
	//.pipe(plumber.stop())
	.pipe(gulp.dest(path.css))});


// Stylus Compiler
gulp.task('stylus_blocks', function () {
	return gulp.src(path.stylus_blocks_dir)
	.pipe(plumber())
	.pipe(stylus({ use: nib(),  import: ['nib','../config/identity']}))
	.pipe(plumber.stop())
	.pipe(gulp.dest(path.css_builds_dir)).on('end', function(){
		setTimeout(function () {
			return gulp.src(path.css_builds)
			.pipe(plumber())
			.pipe(concat('blocks_styl.css'))
			.pipe(plumber.stop())
			.pipe(gulp.dest(path.css)).on('end', function(){
				console.log('>>>>>>>>>> Css Concatenados perfectamente...');
			})
		}, 1);
	})});


function styl_com_con(file){
	if (StartsWith(file.path,"/")){
		file_name = file.path.split('/')[file.path.split('/').length - 1];
	}else{
		file_name = file.path.split('\\')[file.path.split('\\').length - 1];
	}
	console.log('>>>>>>>>>> Compiling '+file_name+' : running tasks...');
	gulp.src(file.path)
	.pipe(plumber())
	.pipe(stylus({ use: nib(),  import: ['nib','../config/identity']}))
	.pipe(plumber.stop())
	.pipe(gulp.dest(path.css_builds_dir))
	console.log('>>>>>>>>>> Compiled! '+file_name);
}

// watchers filePaths #withReload
gulp.task('watch', function () {
        gulp.watch(configPaths.sass.src, ['sasss']);

	gulp.watch(path.stylus_blocks_dir, ['concat_css']).on('change', function(file) {return styl_com_con(file);});
	gulp.watch(path.stylus_dirs, ['stylus']);
});

gulp.task('default', ['stylus_blocks','sasss','watch']);
gulp.task('server', ['stylus_blocks','stylus','sasss','watch','run']);