<?php

    /**
     * Register the autoloader for the application.
     *
     * This function handles class loading based on a predefined directory structure,
     * adhering to the PSR-4 standard for file naming and organization. It searches
     * for class files in the 'app/models', 'app/controllers', and 'app/services'
     * directories.
     */
    spl_autoload_register(function ($className) {
        /**
         * Define an array of base directories where your classes are located.
         * The order of these directories matters if class names might conflict.
         * The project root is one directory up from where the autoloader script is.
         * This makes the paths independent of where the autoloader script is run.
         */
        $projectRoot = dirname(__DIR__);

        // Define an array of base directories where your classes are located.
        $directories = [
            $projectRoot . '/app/models/',
            $projectRoot . '/app/controllers/',
            $projectRoot . '/app/repositories/',
            $projectRoot . '/app/services/'
        ];

        // Convert the class name from its namespace to a file path format.
        $fileName = str_replace('\\', '/', $className) . '.php';

        // Loop through the directories to find the class file.
        foreach ($directories as $directory) {
            $filePath = $directory . $fileName;

            // If the file exists, include it and stop searching.
            if (file_exists($filePath)) {
                require $filePath;
                // It's good practice to return here to prevent further checks.
                return;
            }
        }
    });
    
    $db = Database::getInstance();
?>