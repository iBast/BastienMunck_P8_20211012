<?php

// clearing the cach
if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'],
        __DIR__
    ));
}

// refresh the DB
if (isset($_ENV['BOOTSTRAP_FIXTURES_LOAD'])) {
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:database:drop --force --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:database:create --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:schema:create --env=test';
    passthru($command);
    $command = 'php ' . dirname(__DIR__, 1) . '/bin/console doctrine:fixtures:load -n --env=test';
    passthru($command);
}

require __DIR__ . '/../config/bootstrap.php';
