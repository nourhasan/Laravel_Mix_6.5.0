const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/material-dashboard/font-awesome-4.7.0/scss/font-awesome.scss', 'public/css')
   .combine([
	   	'resources/material-dashboard/js/jquery.min.js',
	   	'resources/material-dashboard/js/popper.min.js',
	   	'resources/material-dashboard/js/bootstrap-material-design.min.js',
	   	'resources/material-dashboard/js/perfect-scrollbar.jquery.min.js',
	   	'resources/material-dashboard/js/moment.min.js',
	   	'resources/material-dashboard/js/sweetalert2.js',
	   	'resources/material-dashboard/js/jquery.validate.min.js',
	   	'resources/material-dashboard/js/jquery.bootstrap-wizard.js',
	   	'resources/material-dashboard/js/bootstrap-selectpicker.js',
	   	'resources/material-dashboard/js/bootstrap-datetimepicker.min.js',
	   	'resources/material-dashboard/js/jquery.dataTables.min.js',
	   	'resources/material-dashboard/js/bootstrap-tagsinput.js',
	   	'resources/material-dashboard/js/jasny-bootstrap.min.js',
	   	'resources/material-dashboard/js/fullcalendar.min.js',
	   	'resources/material-dashboard/js/jquery-jvectormap.js',
	   	'resources/material-dashboard/js/nouislider.min.js',
	   	'resources/material-dashboard/js/core.js',
	   	'resources/material-dashboard/js/arrive.min.js',
	   	'resources/material-dashboard/js/chartist.min.js',
	   	'resources/material-dashboard/js/bootstrap-notify.js',
	   	'resources/material-dashboard/js/material-dashboard.js'
   	], 'public/js/material-dashboard.js')
   .combine([
	   	'resources/material-dashboard/css/material-dashboard-icon.css',
	   	'resources/material-dashboard/css/material-dashboard.css',
	   	'resources/material-dashboard/demo/demo.css'
   	], 'public/css/material-dashboard.css');
