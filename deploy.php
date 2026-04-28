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

// Test environment on a dedicated Hetzner VPS (test.playaalta.com).
//   ./vendor/bin/dep deploy playaalta-test
host('playaalta-test')
    ->setHostname('test.playaalta.com')
    ->set('remote_user', 'gerrit')
    ->set('deploy_path', '/var/www/comer-test')
    ->set('branch', 'main')
    ->set('keep_releases', 3)
    ->set('bin/php', '/usr/bin/php8.4')
    ->set('php_fpm_service', 'php8.4-fpm.service');

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

// Reload PHP-FPM after deploy so OPcache picks up the new release path.
// Only runs on hosts that declare a `php_fpm_service` variable.
// Requires the deploy user to have NOPASSWD sudo for that exact command.
task('php-fpm:reload', function () {
    $service = get('php_fpm_service', '');
    if ($service === '') {
        return;
    }
    run("sudo /bin/systemctl reload {$service}");
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database and build assets before symlink swap.
before('deploy:symlink', 'artisan:migrate');
before('deploy:symlink', 'build:assets');

// After the symlink swap, reload PHP-FPM so the new release is served immediately.
after('deploy:symlink', 'php-fpm:reload');
