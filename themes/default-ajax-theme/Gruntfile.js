module.exports = function(grunt) {

	// Project configuration. 
	grunt.initConfig({
		concat: {
			js: {
	    		src: ['static/js/main.js', 'static/js/lang-cookie.js'],
	    		dest: 'static/js/master.js',
	    	}
		},
		uglify: {
			options: {
				banner: '/*! This File was created by Nikolaus Höfer - ' +
						'<%= grunt.template.today("yyyy-mm-dd") %> - '+
						'It falls under the Wurzel Licence. */\n',
				mangle: true,
				compress: {
					drop_console: false // <- ENABLE for production
				},
				report: 'gzip',
				sourceMap: 'static/js/master.map.js'
			},
			my_target: {
				files: {
					'static/js/master.min.js': ['static/js/master.js']
				}
			}
		},
        compass: {
            dist: {                   // Target 
              options: {              // Target options 
                sassDir: 'static/scss',
                cssDir: 'static/css',
                environment: 'production'
              }
            },
            dev: {                    // Another target 
              options: {
                sassDir: 'static/scss',
                cssDir: 'static/css'
              }
            }
        },
		watch: {
			js: {
				files: ['js/**/*.js'],
				tasks: ['concat:js', 'uglify'],
			},
			compass: {
                files: ['sass/**/*.{scss,sass}'],
                tasks: ['compass:dev']
            },
		}
	});

	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.registerTask(
		'default', [
			'concat',
            'compass:dist',
			'uglify',
			'watch'
		]);

};