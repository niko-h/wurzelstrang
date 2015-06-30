module.exports = function(grunt) {

	// Project configuration. 
	grunt.initConfig({
		concat: {
			js: {
	    		src: ['js/main.js', 'js/siteinfo.js', 'js/language.js', 'js/menu.js', 'js/user.js', 'js/entry.js', 'js/helpers.js'],
	    		dest: 'static/js/ws.js',
	    	},
	    	css: {
	    		options: {
					banner: '/*! This File was created by Nikolaus Höfer - ' +
	        				'<%= grunt.template.today("yyyy-mm-dd") %> - '+
	        				'It is part of the Wurzelstrang CMS and falls under its licence. */\n\n',
	        	},
	    		src: ['css/kube.css', 'css/main.css', 'css/popup.css', 'templates/*/*.css'],
	    		dest: 'static/css/ws.min.css',
	    	},
		},
		jshint: {
    		beforeconcat: ['js/**/*.js'],
    		afterconcat: ['static/js/ws.min.js']
		},
		uglify: {
			options: {
				banner: '/*! This File was created by Nikolaus Höfer - ' +
						'<%= grunt.template.today("yyyy-mm-dd") %> - '+
						'It is part of the Wurzelstrang CMS and falls under its licence. */\n',
				mangle: true,
				compress: {
					drop_console: true // <- ENABLE for production
				},
				report: 'gzip',
				sourceMap: 'static/js/ws.map.js'
			},
			my_target: {
				files: {
					'static/js/ws.min.js': ['static/js/ws.js']
				}
			}
		},
		//csslint: {
		//	lax: {
		//		options: {
		//			import: false
		//		},
		//		src: ['css/main.css', 'css/popup.css']
		//	}
		//},
		watch: {
			js: {
				files: ['js/**/*.js'],
				tasks: ['concat:js', 'jshint:beforeconcat', 'uglify'],
			},
			css: {
				files: ['css/**/*.css'],
				tasks: ['concat:css'],
			},
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask(
		'default', [
			'concat',
			'jshint:beforeconcat',
			'uglify',
			//'csslint',
			'watch'
		]);

};