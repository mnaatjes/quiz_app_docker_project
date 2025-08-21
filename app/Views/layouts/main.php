<?php
// quiz_app/app/Views/layouts/main.php

// This is the main layout file for your application.
// It includes the HTML boilerplate, header, footer, and a placeholder for the view's content.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Use a dynamic title that can be set in the view's data array -->
    <title><?php echo $title ?? 'My MVC App'; ?></title>
    <!-- We'll assume you have a Tailwind CSS setup for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col antialiased">

    <!-- The Header and Nav -->
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/index.php/" class="text-xl font-bold text-gray-800">Quiz App</a>
            <ul class="flex space-x-4">
                <li><a href="/index.php/dashboard" class="text-gray-600 hover:text-gray-900">Home</a></li>
                <li><a href="/index.php/about" class="text-gray-600 hover:text-gray-900">About</a></li>
                <li><a href="/index.php/contact" class="text-gray-600 hover:text-gray-900">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- The main content of the page is injected here -->
    <main class="flex-grow">
        <?php echo $content; ?>
    </main>

    <!-- The Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; <?php echo date('Y'); ?> Quiz App. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
