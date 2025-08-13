<?php
    /**
     * Create Autoloader
     */
    
    spl_autoload_register(function ($className) {

        // Define the namespace prefix that your autoloader handles
        $prefix = 'mnaatjes\\DataAccess\\';

        // Define the base directory for your classes
        $baseDir = __DIR__ . '/../src/';

        // Check if the class name has the same namespace prefix
        $len = strlen($prefix);
        if (strncmp($prefix, $className, $len) !== 0) {
            // No, it doesn't. Let another autoloader handle it.
            return;
        }

        // Get the relative class name by removing the namespace prefix
        $relativeClass = substr($className, $len);

        // Replace the namespace separator with the directory separator and append `.php`
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        // Check if the file exists
        if (file_exists($file)) {
            require $file;
        }
    });

    /**
     * Declare namespaces to use
     */
    use mnaatjes\DataAccess\utils\DotEnv;

    /**
     * Create .env reader instance
     */
    DotEnv::load(__DIR__ . "/.env");

    
?>