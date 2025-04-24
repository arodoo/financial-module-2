<?php
/**
 * Composer Installation Handler
 * 
 * This script provides a web interface for running Composer installation
 * with better output formatting and error handling.
 */

// Set the composer home directory
putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin');

// Output header with styling
echo "<pre style='background-color: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace;'>";
echo "=== Composer Installation Started ===\n\n";

// Check if composer is available
$composer_check = shell_exec('composer --version 2>&1');
if (strpos($composer_check, 'Composer version') === false) {
    echo "ERROR: Composer is not installed or not in the PATH.\n";
    echo "Please install Composer first: https://getcomposer.org/download/\n";
    echo "</pre>";
    exit(1);
}

// Run the installation with verbose output
echo "Running composer install...\n\n";
$output = shell_exec('composer install --profile --verbose 2>&1');

// Display the results and check for success
if ($output) {
    echo $output;
    
    if (strpos($output, 'successfully installed') !== false || strpos($output, 'Nothing to install') !== false) {
        echo "\n=== Composer Installation Completed Successfully ===";
    } else {
        echo "\n=== Composer Installation Completed with Possible Errors ===";
        echo "\nPlease review the output above for issues.";
    }
} else {
    echo "ERROR: Failed to execute composer. No output received.";
}

echo "</pre>";
?>
