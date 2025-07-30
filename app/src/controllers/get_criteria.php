<?php

    /**
     * Collects Criteria and sends
     */
    function get_criteria($pdo): array{
        /**
         * Models
         */
        $category   = new CategoryModel($pdo);
        $diff       = new DiffModel($pdo);

        /**
         * Load Criteria and Send to User
         * - URI: /criteria/
         * - Load into assoc array
         * - Send
         */
        $criteria = [
            'difficulties'  => $diff->listCriteria(),
            'categories'    => $category->listCriteria()
        ];

        /**
         * Validate and return
         */
        if(empty($criteria)){
            throw new Exception("Cannot load criteria");
        }
        return $criteria;
    }