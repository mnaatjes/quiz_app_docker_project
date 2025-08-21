<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;
    /**
     * Represents the Category table in the database.
     * This class extends BaseModel to leverage core framework functionality.
     */
    class CategoryModel extends BaseModel {

        /**
         * @var int The primary key of the category. Corresponds to the 'id' column.
         */
        private $id;

        /**
         * @var string The name of the category. Corresponds to the 'name' column.
         */
        private $name;

        /**
         * @var string|null A detailed description of the category. Corresponds to the 'description' column.
         */
        private $description;

        /**
         * @var string The URL slug for the category, used for friendly URLs. Corresponds to the 'slug' column.
         */
        private $slug;

        /**
         * @var string|null The URL for an image associated with the category. Corresponds to the 'image_url' column.
         */
        private $imageUrl;

        /**
         * @var string|null The name of an icon associated with the category. Corresponds to the 'icon_name' column.
         */
        private $iconName;

        /**
         * @var int The sort order for displaying categories. Corresponds to the 'sort_order' column.
         */
        private $sortOrder;

        /**
         * @var int A boolean-like value (1 or 0) indicating if the category is active. Corresponds to the 'is_active' column.
         */
        private $isActive;

        /**
         * @var DateTime The timestamp when the record was created. Corresponds to the 'created_at' column.
         */
        private $createdAt;

        /**
         * @var DateTime The timestamp when the record was last updated. Corresponds to the 'updated_at' column.
         */
        private $updatedAt;

        /**
         * @var int|null The ID of the parent category, for nested categories. Corresponds to the 'parent_category_id' column.
         */
        private $parentCategoryId;
        
        /**
         * @var int The number of times the category has been selected. Corresponds to the 'selection_count' column.
         */
        private $selectionCount;
    }
?>