module.exports = function(grunt) {

	// Project configuration. 
	grunt.initConfig({
		concat: {
	    	js: {
	    		src: ['js/helpers.js', 'js/listeners.js', 'js/calls.js', 'js/renderers.js', 'js/toJSON.js', 'js/main.js'],
	    		dest: 'static/js/ws.js',
	    	},
	    	css: {
	    		src: ['css/kube.css', 'css/main.css', 'css/popup.css'],
	    		dest: 'static/css/ws.min.css',
	    	},
		},
		jshint: {
    		beforeconcat: ['js/**/*.js'],
    		afterconcat: ['static/js/ws.min.js']
		},
		uglify: {
			options: {
				mangle: true,
				compress: {
					drop_console: false // <- ENABLE for production
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
				tasks: ['concat:js', 'jshint:beforeconcat'],
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