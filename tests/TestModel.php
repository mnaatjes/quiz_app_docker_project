<?php

    class TestModel {
        private int $id;
        private string $text;
        private bool $isActive;
        private string $createdAt;
        private string $updatedAt;

        public function __construct(
            int $id,
            string $text,
            bool $isActive
        ){
            $this->id       = $id;
            $this->text     = $text;
            $this->isActive = $isActive;
        }

        private function hello(){}
    }
?>