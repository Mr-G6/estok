module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            dist: {
                src: [
                    'resources/assets/js/*.js',
                ],
                dest: 'public/assets/js/app.js',
            }
        },


        uglify: {
            build: {
                src:  'public/assets/js/app.js',
                dest: 'public/assets/js/app.min.js'
            }
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'public/assets/css/app.min.css' : 'resources/assets/sass/app.scss'
                }
            }
        },


        watch: {
            scripts: {
                files: [
                    'resources/assets/js/*.js'
                ],
                tasks: ['concat', 'uglify']
            },

            css: {
                files: [
                    'resources/assets/sass/*.scss',

                ],
                tasks: ['sass'],
                options: {
                    spawn: false,
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['concat', 'sass', 'uglify', 'watch']);
};
