<?php
/**
 * Created on Nov 2, 2018
 * Time Created	: 11:51:34 PM
 * Filename		: diy.templates.php
 *
 * @filesource	diy.templates.php
 *
 * @author		wisnuwidi@gmail.com - 2018
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
return [
	'admin' => [
		'default' => [
			'position' => [
				'top' => [
					'js'	=> [
						'vendor/plugins/nodes/jquery/dist/jquery.min.js',
						'vendor/plugins/nodes/popper.js/dist/umd/popper.min.js',
						'vendor/plugins/nodes/bootstrap/dist/js/bootstrap.min.js',
						'vendor/plugins/nodes/ion-sound/js/ion.sound.min.js',
						'js/sidebar.js',
						'js/firscripts.js'
					],
					'css'	=> [
    					'vendor/plugins/nodes/bootstrap/dist/css/bootstrap.css'
					]
				],
				'bottom'	=> [
					'first'	=> [
						'js'	=> [
							'vendor/plugins/jquery-ui/jquery-ui.min.js',
							'vendor/plugins/jquery-cookie/jquery.cookie.js',
							'js/metisMenu.min.js',
							'vendor/plugins/nodes/owl.carousel/dist/owl.carousel.min.js',
							'vendor/plugins/nodes/jquery-slimscroll/jquery.slimscroll.min.js',
							'vendor/plugins/nodes/slicknav/dist/jquery.slicknav.min.js',
							'vendor/plugins/jquery-nicescroll/jquery.nicescroll.min.js'
						],
						'css'	=> ['css/config.css']
					],
					'last'	=> [
						'js'	=> [
							'js/plugins.js',
							'js/scripts.js',
							'js/diyscripts.js'
						],
						'css'	=> ['css/app.css']
					]
				]
			],
		    
		    
			'datatable' => [
				'js'	=> [
					'vendor/plugins/nodes/datatables/js/responsive/jquery.dataTables.10.01.19.min.js',// 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
					'vendor/plugins/nodes/datatables/js/responsive/dataTables.responsive.2.2.3.min.js',// 'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js',
					
					'vendor/plugins/nodes/datatables/js/dataTables.bootstrap.js',
					'vendor/plugins/nodes/datatables/extentions/dataTables.buttons.min.js',
					'vendor/plugins/nodes/datatables/extentions/buttons.html5.min.js',
					'vendor/plugins/nodes/datatables/extentions/buttons.colVis.min.js',
					'vendor/plugins/nodes/datatables/extentions/buttons.print.min.js',
					'vendor/plugins/nodes/datatables/extentions/jszip.min.js',
					'vendor/plugins/nodes/datatables/extentions/pdfmake.min.js',
					'vendor/plugins/nodes/datatables/extentions/vfs_fonts.js',
					'vendor/plugins/nodes/datatables/extentions/buttons.flash.min.js',
					'js/datatables/filter.js'
				],
				'css'	=> [
					'vendor/plugins/nodes/datatables/css/dataTables.bootstrap.css',
					'vendor/plugins/nodes/datatables/css/buttons.dataTables.min.css',
					'vendor/plugins/nodes/datatables/css/datatables.responsive.css',
				]
			],
		    
			'textarea'	=> [
				'js'	=> ['vendor/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js'],
				'css'	=> [null]
			],
		    
			'tagsinput' => [
				'js'	=> ['vendor/plugins/nodes/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js'],
				'css'	=> ['vendor/plugins/nodes/bootstrap-tagsinput/dist/bootstrap-tagsinput.css']
			],
		    
			'file' => [
				'js'	=> ['vendor/plugins/nodes/jasny-bootstrap/dist/js/jasny-bootstrap.min.js'],
				'css'	=> ['vendor/plugins/nodes/jasny-bootstrap/dist/css/jasny-bootstrap.min.css']
			],
			
			'select' => [
				'js'	=> ['vendor/plugins/nodes/chosen-js/chosen.jquery.min.js'],
				'css'	=> ['vendor/plugins/nodes/chosen-js/chosen.min.css']
			],
			
			'selectMonth' => [
				'js'	=> ['vendor/plugins/nodes/chosen-js/chosen.jquery.min.js'],
				'css'	=> ['vendor/plugins/nodes/chosen-js/chosen.min.css']
			],
		    
			'date' => [
				'js'	=> [
					'vendor/plugins/nodes/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
					'last:js/form.picker.js'
				],
				'css'	=> ['vendor/plugins/nodes/jquery-datetimepicker/build/jquery.datetimepicker.min.css']
			],
		    
			'datetime'	=> [
				'js'	=> [
					'vendor/plugins/nodes/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
					'last:js/form.picker.js'
				],
				'css'	=> ['vendor/plugins/nodes/jquery-datetimepicker/build/jquery.datetimepicker.min.css']
			],
		    
			'daterange' => [
				'js'	=> [
					'vendor/plugins/moment/min/moment.min.js',
					'vendor/plugins/bootstrap-daterangepicker/daterangepicker.js',
					'last:js/form.picker.js'
				],
				'css'	=> ['vendor/plugins/bootstrap-daterangepicker/daterangepicker.css']
			],
		    
			'time' => [
				'js'	=> [
					'vendor/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
					'last:js/form.picker.js'
				//	'vendor/plugins/prettify/prettify.js'
				],
				'css'	=> ['vendor/plugins/bootstrap-timepicker/css/timepicker.css']
			],
			
			'highcharts' => [
				'js'  => [
				//	'charts/highcharts/highcharts.js'
					'vendor/plugins/highcharts/js/highcharts.js',
					'vendor/plugins/highcharts/js/modules/exporting.js'
					
				],
				'css' => [null]
			],
			
			'chartjs' => [
				'js'  => [
					'charts/chartjs/Chart.min.js'
				],
				'css' => [null]
			]
		]
	]
];