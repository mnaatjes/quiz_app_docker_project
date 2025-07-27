<?php

    /**
     * Process and Convert Quiz Data to CSV Files
     * 
     * @since 7/26/25
     * @version 2.0
     * 
     */
    
    /**
     * 
     */
    function combineCsvFiles(string $sourceDirectory, string $outputFile): bool
    {
        // Check if the source directory exists and is readable
        if (!is_dir($sourceDirectory) || !is_readable($sourceDirectory)) {
            echo "Error: Source directory '{$sourceDirectory}' does not exist or is not readable.\n";
            return false;
        }

        // Get a list of all CSV files in the directory
        $csvFiles = glob($sourceDirectory . '/*.csv');

        if (empty($csvFiles)) {
            echo "No CSV files found in '{$sourceDirectory}'.\n";
            return false;
        }

        // Open the output file for writing
        $outputHandle = fopen($outputFile, 'w');
        if ($outputHandle === false) {
            echo "Error: Could not open output file '{$outputFile}' for writing.\n";
            return false;
        }

        $isFirstFile = true;

        foreach ($csvFiles as $filePath) {
            $inputHandle = fopen($filePath, 'r');
            if ($inputHandle === false) {
                echo "Warning: Could not open input file '{$filePath}'. Skipping.\n";
                continue; // Skip to the next file
            }

            // Extract category from the filename
            $filename = basename($filePath);
            $category = '';
            if (preg_match('/category_([a-zA-Z0-9_-]+)\.csv/', $filename, $matches)) {
                $category = $matches[1];
            } else {
                echo "Warning: Could not extract category from filename '{$filename}'. Using empty string for category.\n";
            }

            // Read and write the header only for the first file
            if ($isFirstFile) {
                $header = fgetcsv($inputHandle);
                if ($header !== false) {
                    $header[] = 'Category'; // Add the new 'Category' column header
                    fputcsv($outputHandle, $header);
                }
                $isFirstFile = false;
            } else {
                // For subsequent files, skip the header row
                fgetcsv($inputHandle);
            }

            // Read and write the rest of the rows, adding the category
            while (($row = fgetcsv($inputHandle)) !== false) {
                $row[] = $category; // Append the extracted category to the current row
                fputcsv($outputHandle, $row);
            }

            fclose($inputHandle); // Close the current input file
        }

        fclose($outputHandle); // Close the output file

        echo "Successfully combined CSV files into '{$outputFile}'.\n";
        return true;
    }