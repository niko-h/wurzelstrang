module.exports = function(grunt) {

	// Project configuration. 
	grunt.initConfig({
		concat: {
	    	js: {
	    		src: ['js/helpers.js', 'js/listeners.js', 'js/calls.js', 'js/renderers.js', 'js/toJSON.js', 'js/main.js'],
	    		dest: 'static/js/ws.min.js',
	    	},
	    	css: {
	    		src: ['css/kube.css', 'css/main.css', 'css/popup.css'],
	    		dest: 'static/css/ws.min.css',
	    	},
		},
		watch: {
			js: {
				files: ['js/**/*.js'],
				tasks: ['concat:js', 'jshint:afterconcat'],
			},
			css: {
				files: ['css/**/*.css'],
				tasks: ['concat:css'],
			},
		},
		jshint: {
    		beforeconcat: ['js/**/*.js'],
    		afterconcat: ['static/js/ws.min.js']
		},
		csslint: {
			lax: {
				options: {
					import: false
				},
				src: ['css/main.css', 'css/popup.css']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.registerTask('default', ['concat', 'jshint:beforeconcat', 'csslint', 'watch']);

};