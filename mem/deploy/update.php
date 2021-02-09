<?php
if(function_exists('opcache_reset')) {
// Reset opcache if something new was pulled
    echo '[Master-DS] Resetting Opcache' . "\n";
    opcache_reset();
}

// Execute Migration
include __DIR__.'/migrate.php';