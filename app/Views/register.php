<?php
// quiz_app/app/Views/register.php
// This view displays a user registration form.
?>
<main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-md">
    <!-- The title and message can be passed from the controller -->
    <h1 class="text-4xl font-bold text-gray-800 mb-2 text-center">
        <?php echo htmlspecialchars($title ?? 'Register'); ?>
    </h1>
    <p class="text-lg text-gray-600 mb-6 text-center">
        <?php echo htmlspecialchars($message ?? 'Create your account to get started!'); ?>
    </p>

    <form action="/register" method="post" class="space-y-4">
        <!-- Username Input Field -->
        <div>
            <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Choose a username"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Email Input Field -->
        <div>
            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Enter your email"
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
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Password Confirmation Field -->
        <div>
            <label for="password_confirm" class="block text-gray-700 text-sm font-semibold mb-2">Confirm Password</label>
            <input 
                type="password" 
                id="password_confirm" 
                name="password_confirm" 
                placeholder="Re-enter your password"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="/index.php/" class="text-sm text-blue-500 hover:underline">Already have an account?</a>
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105"
            >
                Register
            </button>
        </div>
    </form>
</main>
