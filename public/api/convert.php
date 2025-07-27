<?php

    /**
     * Require Packages
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/utils/raw_data_converter.php');
    //require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');
    
    /**
     * Enable Errors
     */
    enable_errors();

    /**
     * Debugging
     */
    $csv_dir    = '/var/www/app/src/data/raw/';
    $output_fp  = '/var/www/app/src/data/categories.csv';

    combineCsvFiles($csv_dir, $output_fp);

    /**
     * Execution
     */
    echo "Converter Running...";