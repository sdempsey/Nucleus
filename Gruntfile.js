module.exports = function(grunt) {
	grunt.initConfig({
		jshint: { // stops compiling when you write bad js.
			all: ['scripts/src/**/*.js']
		},
		concat: { //concatenates .js files into one.
			debug: {
				src: 'scripts/src/*.js',
				dest: 'scripts/site/main.js'
			}
		},
		compass: { //compiles SASS, does other awesome stuff (www.compass-style.org)
			debug: {
				options: {
					require: 'susy',
					sassDir: 'sass',
					cssDir: '.',
					sourcemap:true
				}
			},
			editor: {
				options: {
					sassDir: 'sass/editor',
					cssDir: 'css'
				}
			}
		},
		cmq: { //combines media queries
			debug: {
				files: { '.': ['style.css'] }
			}
		},
		imagemin: { //optimizes images
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
		grunticon: { //creates .png fallbacks for .svg icons
			myIcons: {
				files: [{
					expand: true,
					cwd: 'images/svg',
					src: ['*.svg', '*.png'],
					dest: 'images/icons'
				}],
				options: {
					pngfoler: 'images/icons/png',
					colors: { //set custom colors here.  
						vitalOrange: "#f05327",
						plainWhite: "#fff"
					}
				}
			}
		},
		watch: { //checks for specified changes, refreshes browser if plugin is installed
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
	grunt.loadNpmTasks('grunt-contrib-compass')
	grunt.loadNpmTasks('grunt-combine-media-queries');
	grunt.loadNpmTasks('grunt-contrib-imagemin');
	grunt.loadNpmTasks('grunt-newer');
	grunt.loadNpmTasks('grunt-grunticon');

	grunt.registerTask('js', ['jshint', 'concat']);
	grunt.registerTask('css', ['compass', 'cmq']);
	grunt.registerTask('img', ['newer:imagemin', 'newer:grunticon']);
	grunt.registerTask('default', ['js', 'css', 'img']);	
}