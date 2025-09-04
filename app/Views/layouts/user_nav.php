<?php
    // This partial should be included in your main layout file.
    // It assumes a session is active and checks for a 'user_id' to determine login status.
    // It also assumes a $user variable is available when logged in, containing user details.
    var_dump($sessionData);
    $isLoggedIn = isset($_SESSION['user_id']);
    if ($isLoggedIn) {
        // In a real app, this $user object would likely be populated by a BaseController or middleware
        // For now, we'll check if it's passed from the view's data payload.
        $username = $_SESSION['username'] ?? 'User';
        ?>
        <div class="w-full bg-gray-100 border-b border-gray-200">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-10">
                    <?php if ($isLoggedIn): ?>
                        <!-- Logged In State -->
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">Welcome,</span>
                            <span class="font-bold text-gray-800"><?php echo htmlspecialchars($username); ?>!</span>
                        </div>
                        <div>
                            <a href="/index.php/logout" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">Logout</a>
                        </div>
                    <?php else: ?>
                        <!-- Logged Out State -->
                        <div class="flex items-center text-sm text-gray-500">
                            <span>You are not currently logged in.</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/index.php/login" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">Login</a>
                            <a href="/index.php/register" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
?>
