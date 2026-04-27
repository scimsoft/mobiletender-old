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
    ->setHostname('playaalta.com')
    ->set('deploy_path', '/var/www/staging')
    ->set('branch', 'main');


host('demo')
    ->setHostname('demo.playaalta.com')
    ->set('deploy_path', '/var/www/demo');

host('bar')
    ->setHostname('bar.playaalta.com')
    ->set('deploy_path', '/var/www/sergio');

host('playaalta')
    ->setHostname('comer.playaalta.com')
    ->set('deploy_path', '/var/www/comer');

// Test environment on the same server, separate directory + subdomain.
// Point test.playaalta.com (or your test domain) DNS at the same server,
// add an nginx vhost for /var/www/comer-test/current/public, then:
//   ./vendor/bin/dep deploy playaalta-test
host('playaalta-test')
    ->setHostname('comer.playaalta.com')
    ->set('deploy_path', '/var/www/comer-test')
    ->set('branch', 'main');

host('copas')
    ->setHostname('copas.playaalta.com')
    ->set('deploy_path', '/var/www/copas');

host('latertulia')
    ->setHostname('latertulia.horecalo.com')
    ->set('deploy_path', '/var/www/latertulia');

host('tertulia')
    ->setHostname('tertulia.horecalo.com')
    ->set('deploy_path', '/var/www/tertulia')
    ->set('branch', 'tertulia');

host('horecalo')
    ->setHostname('demo.horecalo.com')
    ->set('deploy_path', '/var/www/horecalo')
    ->set('branch', 'horecalo');

// Tasks

// Build front-end assets (npm) on the server.
// Hooks into the deploy flow before the symlink swaps to the new release.
task('build:assets', function () {
    run('cd {{release_path}} && npm ci --no-audit --no-fund && npm run build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database and build assets before symlink swap.
before('deploy:symlink', 'artisan:migrate');
before('deploy:symlink', 'build:assets');
