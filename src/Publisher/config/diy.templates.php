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
						'vendor/node_modules/jquery/dist/jquery.min.js',
						'vendor/node_modules/popper.js/dist/umd/popper.min.js',
						'vendor/node_modules/bootstrap/dist/js/bootstrap.min.js',
						'vendor/node_modules/ion-sound/js/ion.sound.min.js',
						'js/firscripts.js'
					],
					'css'	=> [
    					'vendor/node_modules/bootstrap/dist/css/bootstrap.css'
					]
				],
				'bottom'	=> [
					'first'	=> [
						'js'	=> [
							'vendor/plugins/jquery-ui/jquery-ui.min.js',
							'vendor/plugins/jquery-cookie/jquery.cookie.js',
							'js/metisMenu.min.js',
							'vendor/node_modules/owl.carousel/dist/owl.carousel.min.js',
							'vendor/node_modules/jquery-slimscroll/jquery.slimscroll.min.js',
							'vendor/node_modules/slicknav/dist/jquery.slicknav.min.js',
							'vendor/plugins/jquery-nicescroll/jquery.nicescroll.min.js'
						],
						'css'	=> ['css/config.css']
					],
					'last'	=> [
						'js'	=> [
							'js/plugins.js',
							'js/scripts.js'
						],
						'css'	=> []
					]
				]
			],
		    
		    
			'datatable' => [
				'js'	=> [
					'vendor/node_modules/datatables/js/responsive/jquery.dataTables.10.01.19.min.js',// 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
					'vendor/node_modules/datatables/js/responsive/dataTables.responsive.2.2.3.min.js',// 'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js',
					
					'vendor/node_modules/datatables/js/dataTables.bootstrap.js',
					'vendor/node_modules/datatables/extentions/dataTables.buttons.min.js',
					'vendor/node_modules/datatables/extentions/buttons.html5.min.js',
					'vendor/node_modules/datatables/extentions/buttons.colVis.min.js',
					'vendor/node_modules/datatables/extentions/buttons.print.min.js',
					'vendor/node_modules/datatables/extentions/jszip.min.js',
					'vendor/node_modules/datatables/extentions/pdfmake.min.js',
					'vendor/node_modules/datatables/extentions/vfs_fonts.js',
					'vendor/node_modules/datatables/extentions/buttons.flash.min.js'
				],
				'css'	=> [
					'vendor/node_modules/datatables/css/dataTables.bootstrap.css',
					'vendor/node_modules/datatables/css/buttons.dataTables.min.css',
					'vendor/node_modules/datatables/css/dataTables.responsive.css',
				]
			],
		    
			'textarea'	=> [
				'js'	=> ['vendor/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js'],
				'css'	=> ['']
			],
		    
			'tagsinput' => [
				'js'	=> ['vendor/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js'],
				'css'	=> ['vendor/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css']
			],
		    
			'file' => [
				'js'	=> ['vendor/node_modules/jasny-bootstrap/dist/js/jasny-bootstrap.min.js'],
				'css'	=> ['vendor/node_modules/jasny-bootstrap/dist/css/jasny-bootstrap.min.css']
			],
			
			'select' => [
				'js'	=> ['vendor/node_modules/chosen-js/chosen.jquery.min.js'],
				'css'	=> ['vendor/node_modules/chosen-js/chosen.min.css']
			],
			
			'selectMonth' => [
				'js'	=> ['vendor/node_modules/chosen-js/chosen.jquery.min.js'],
				'css'	=> ['vendor/node_modules/chosen-js/chosen.min.css']
			],
		    
			'date' => [
				'js'	=> [
					'vendor/node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
					'last:js/form.picker.js'
				],
				'css'	=> ['vendor/node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css']
			],
		    
			'datetime'	=> [
				'js'	=> [
					'vendor/node_modules/jquery-datetimepicker/build/jquery.datetimepicker.full.min.js',
					'last:js/form.picker.js'
				],
				'css'	=> ['vendor/node_modules/jquery-datetimepicker/build/jquery.datetimepicker.min.css']
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
			]
		]
	]
];