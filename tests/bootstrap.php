<?php
    /**
     * Create Autoloader
     */
    
    spl_autoload_register(function ($className) {
        $namespaces = [
            'mnaatjes\\DataAccess\\' => __DIR__ . '/../src/',
            'mnaatjes\\DataAccess\\Tests\\' => __DIR__ . '/'
        ];

        foreach ($namespaces as $prefix => $baseDir) {
            $len = strlen($prefix);
            if (strncmp($prefix, $className, $len) === 0) {
                $relativeClass = substr($className, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
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