const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const debug = require('gulp-debug');
const rename = require('gulp-rename');
const del = require('del');

var buildDir = 'public/assets';
var assetsDir = 'resources/assets';

//Arquivos sass
var scssFiles = [
	assetsDir + '/sass/app.scss',
]

var scssWatchFiles = [
	assetsDir + '/sass/**/*.scss',
	assetsDir + '/css/**/*.css'
]

//Arquivos js
var jsFiles = [
	assetsDir + '/js/*.js',
	assetsDir + '/js/pages/*.js',
]

//Arquivos externos js
var vendorJsFiles = [
	'node_modules/jquery/dist/jquery.js',
	'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
	'node_modules/@popperjs/core/dist/umd/popper.min.js',
	// 'node_modules/bootstrap/dist/js/bootstrap.min.js',
	'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
	'node_modules/bootstrap-select/js/i18n/defaults-pt_BR.js',
	'node_modules/select2/dist/js/select2.min.js',
	'node_modules/moment/moment.js',
]

//Arquivos externos css
var vendorCssFiles = [
	'node_modules/bootstrap/dist/css/bootstrap.min.css',
	'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
	'node_modules/@fortawesome/fontawesome-free/css/all.css',
	'node_modules/select2/dist/css/select2.min.css'
]

/**
 * Concatena os arquivos sass, converte e comprime em css
 * @param env
 * @returns
 */
function sass2Css(files, outputName, env = 'dev') {

	var sassConfig = { outputStyle: env == 'prod' ? 'compressed' : 'expanded' };

	return gulp.src(files)
		.pipe(debug({ title: 'css-debug' }))
		.pipe(concat(outputName + '.scss'))
		.pipe(sass(sassConfig).on('error', sass.logError))
		.pipe(rename(outputName + '.min.css'))
		.pipe(gulp.dest(buildDir + '/css'));
}

/**
 * Concatena e mimifica os arquivos css se estiver em produÃ§Ã£o
 * @param files
 * @param outputName
 * @param env
 * @returns
 */
function css(files, outputName, env = 'dev') {

	var sassConfig = { outputStyle: env == 'prod' ? 'compressed' : 'expanded' };

	return gulp.src(files)
		.pipe(debug({ title: 'css-debug' }))
		.pipe(concat(outputName + '.css'))
		.pipe(rename(outputName + '.min.css'))
		.pipe(gulp.dest(buildDir + '/css'));
}

/**
 * Gera os arquivos css em desenvolvimento
 * @returns
 */
function cssDev() {
	return sass2Css(scssFiles, 'styles');
}

/**
 * Gera os arquivos css em produÃ§Ã£o
 * @returns
 */
function cssProd() {
	return sass2Css(scssFiles, 'styles', 'prod');
}

/**
 * Gera os arquivos css vendor em desenvolvimento
 * @returns
 */
function vendorCssDev() {
	return css(vendorCssFiles, 'vendor');
}

/**
 * Gera os arquivos css vendor em produÃ§Ã£o
 * @returns
 */
function vendorCssProd() {
	return css(vendorCssFiles, 'vendor', 'prod');
}

/**
 * Concatena os arquivos js e comprime em js
 * @param env
 * @returns
 */
function js2Mimify(files, outputName, env = 'dev') {

	var obj = null;


	if (env == 'prod') {
		obj = gulp.src(files)
			.pipe(terser())
			.pipe(debug({ title: 'js-debug' }))
			.pipe(concat(outputName + '.js'));

	} else {

		obj = gulp.src(files)
			.pipe(debug({ title: 'js-debug' }))
			.pipe(concat(outputName + '.js'));

	}

	return obj
		.pipe(rename(outputName + '.min.js'))
		.pipe(gulp.dest(buildDir + '/js'));
}

/**
 * Gera os arquivos js em desenvolvimento
 * @returns
 */
function jsDev() {
	return js2Mimify(jsFiles, 'scripts');
}

/**
 * Gera os arquivos js em produÃ§Ã£o
 * @returns
 */
function jsProd() {
	return js2Mimify(jsFiles, 'scripts', 'prod');
}

/**
 * Gera os arquivos js vendor em desenvolvimento
 * @returns
 */
function vendorJsDev() {
	return js2Mimify(vendorJsFiles, 'vendor');
}

/**
 * Gera os arquivos js vendor em produÃ§Ã£o
 * @returns
 */
function vendorJsProd() {
	return js2Mimify(vendorJsFiles, 'vendor', 'prod');
}

/**
 * Limpa o diretÃ³rio de build
 * @returns
 */
function cleanBuild() {
	return del([buildDir]);
}

/**
 * Copia as imagens da aplicaÃ§Ã£o
 * @returns
 */
function images() {

	return gulp.src("img/**/*", { cwd: assetsDir })
		.pipe(gulp.dest("public/assets/img"));
}

/**
 * Copia as fontes de aplicaÃ§Ã£o
 * @returns
 */
function fonts() {

	return gulp.src("fonts/**/*", { cwd: assetsDir })
		.pipe(gulp.dest("public/assets/fonts")) &&
		
		gulp.src('node_modules/@fortawesome/fontawesome-free/webfonts/*')
			.pipe(gulp.dest('public/assets/webfonts')) &&

		gulp.src('node_modules/bootstrap/fonts/*')
			.pipe(gulp.dest('public/assets/fonts'));

}

/**
 * Monitora a alteraÃ§Ã£o e realiza a publicaÃ§Ã£o dos arquivos
 * @returns
 */
function watch() {
	gulp.watch(scssWatchFiles, cssDev);
	gulp.watch(jsFiles, jsDev);
}

/**
 * Tasks
 */
gulp.task('clean', gulp.series(cleanBuild));
gulp.task('build', gulp.series(cleanBuild, jsProd, cssProd, vendorJsProd, vendorCssProd, images, fonts));
gulp.task('default', gulp.series(cleanBuild, jsDev, cssDev, vendorJsDev, vendorCssDev, images, fonts));
gulp.task('watch', gulp.series('default', watch));	