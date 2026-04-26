<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'PlayaAltaPos');

// Project repository
set('repository', 'https://github.com/scimsoft/mobiletender.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', ['nodeprinterbridge.js']);
add('shared_dirs', []);

// Writable dirs by Web server 
add('writable_dirs', []);
set('writable_use_sudo', false);

// Hosts

host('staging')
    ->hostname('staging.playaalta.com')
    ->set('deploy_path', '/var/www/staging')
    ->set('branch', 'main');


host('demo')
    ->hostname('demo.playaalta.com')
    ->set('deploy_path', '/var/www/demo');

host('bar')
    ->hostname('bar.playaalta.com')
    ->set('deploy_path', '/var/www/sergio');

host('playaalta')
    ->hostname('comer.playaalta.com')
    ->set('deploy_path', '/var/www/comer');

host('copas')
    ->hostname('copas.playaalta.com')
    ->set('deploy_path', '/var/www/copas');

host('latertulia')
    ->hostname('latertulia.horecalo.com')
    ->set('deploy_path', '/var/www/latertulia');

host('tertulia')
    ->hostname('tertulia.horecalo.com')
    ->set('deploy_path', '/var/www/tertulia')
    ->set('branch','tertulia');

host('horecalo')
    ->hostname('demo.horecalo.com')
    ->set('deploy_path', '/var/www/horecalo')
    ->set('branch','horecalo');
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

