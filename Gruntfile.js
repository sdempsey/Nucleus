module.exports = function(grunt) {
	grunt.initConfig({
		jshint: { //development and production task - stops compiling when you write bad js.
			all: ['scripts/src/**/*.js']
		},
		concat: { //development and production task - concatenates .js files into one.
			debug: {
				src: 'scripts/src/*.js',
				dest: 'scripts/site/main.js'
			}
		},
		uglify: { //production task only - minifies js
			options: {
				mangle: {
					except: ['jQuery']
				},
				preserveComments: 'none',
				sourceMap: true
			},
			'scripts/site/main.min.js': ['scripts/site/main.js']
		},
		compass: { //compiles SASS, does other awesome stuff (www.compass-style.org)
			options: {
				require: 'susy',
				sassDir: 'sass',
				cssDir: 'css',
			},
			development: { //development task
				options: {
					sourcemap: true //allows sass sourcemap when enabled in browser.
									// (webkit(not sure about safari) and gecko).
				}
			},
			production: {}, //prodution task - does nothing.
		},
		cssc: { //production only task - condenses CSS
			options: {
				sortSelectors: true,
				lineBreaks: true,
				sortDeclarations: true,
				consolidateViaDeclarations: false,
				consolidateViaSelectors:false,
				consolidateMediaQueries:false,
			},
			files: { 'css/screen.min.css': 'css/screen.css' }
		},
		cmq: { //production only - combines media queries
			production: {
				files: { '.': ['style.min.css'] }
			}
		},
		cssmin: { //production only - minifies CSS
			production: {
				keepSpecialComments: 0,
				expand: true,
				cwd: 'css',
				src: ['*.css'],
				dest: 'css',
				ext: '.min.css'
			}
		},
		criticalcss: { //production only - inlines above the fold CSS 
			production: {
				options: {
					url: //string - actual url you want to scan ex: "http://localhost/siteName",
					outputfile:"criticalcss.php", //included as a partial between <style> tags.
					filename: "css/screen.css"
				}
			}
		},
		imagemin: { //development and production - optimizes images
			dynamic: {
				options: {
					optimizationLevel: 7
				},
				files: [{
					expand: true,
					cwd: 'images/src/',
					src: '**/*.{jpg,png,gif,svg}',
					dest: 'images/'
				}]
			}
		},
		grunticon: { //development and production - creates .png fallbacks for .svg icons
			myIcons: {
				files: [{
					expand: true,
					cwd: 'images/icons',
					src: ['*.svg', '*.png'],
					dest: 'images/icons/grunticon'
				}],
				options: {
					pngfoler: 'images/icons/png'
				}
			}
		},
		clean: ["**/tmp"],
		watch: {
			options: { livereload: true},
			scripts: {
				files: 'scripts/src/*.js',
				tasks: ['js']
			},
			css: {
				files: 'sass/*.scss',
				tasks: ['css']
			},
			img: {
				files: 'images/src/**/*.{jpg,gif,png,svg}',
				tasks: ['img']
			},
			php: {
				files: '*.php',
				tasks: []
			}
		}
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-cssc');
	grunt.loadNpmTasks('grunt-combine-media-queries');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-newer');
	grunt.loadNpmTasks('grunt-criticalcss');
	grunt.loadNpmTasks('grunt-grunticon');

	grunt.registerTask('js', ['jshint', 'concat', 'uglify', 'clean']);
	
}