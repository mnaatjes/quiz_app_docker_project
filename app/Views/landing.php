<?php
// quiz_app/app/Views/home.php
// This view displays a login form to the user.
?>
<main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-md">
    <!-- The title and message are passed from the controller -->
    <h1 class="text-4xl font-bold text-gray-800 mb-2 text-center">
        <?php echo htmlspecialchars($title ?? 'Log In'); ?>
    </h1>
    <p class="text-lg text-gray-600 mb-6 text-center">
        <?php echo htmlspecialchars($message ?? 'Please enter your credentials to continue.'); ?>
    </p>

    <form action="/index.php/login" method="POST" class="space-y-4">
        <!-- Username/Email Input Field -->
        <div>
            <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username or Email</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="test_username"
                placeholder="Enter your username or email"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>
        
        <!-- Password Input Field -->
        <div>
            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Enter your password"
                value="my_secret_password"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="/index.php/register" class="text-sm text-blue-500 hover:underline">Don't have an account?</a>
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105"
            >
                Log In
            </button>
        </div>
    </form>
</main>
