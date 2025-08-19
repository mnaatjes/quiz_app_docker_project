<?php
// quiz_app/app/Views/quiz/create.php
// This view displays the form to create a new quiz.
?>
<main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-md">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        <?php echo htmlspecialchars($title ?? 'Create New Quiz'); ?>
    </h1>

    <!-- The form for creating a new quiz -->
    <form action="/index.php/quiz/store" method="POST" class="space-y-6">
        <!-- Quiz Title Input -->
        <div>
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Quiz Title</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                placeholder="e.g., General Knowledge Trivia"
                value="Here is a Dummy Title"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Category Dropdown -->
        <div>
            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select 
                id="category_id" 
                name="category_id"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
                <option value="">-- Select a Category --</option>
                <!-- These options are manually added based on the screenshot data -->
                <option value="4">animals</option>
                <option value="5">celebrities</option>
                <option value="6">entertainment</option>
                <option value="8">geography</option>
                <option value="9">history</option>
                <option value="10">hobbies</option>
                <option value="11">humanities</option>
                <option value="7">kids</option>
                <option value="12">literature</option>
                <option value="13">movies</option>
                <option value="14">music</option>
                <option value="15">newest</option>
                <option value="16">people</option>
                <option value="17">rated</option>
                <option value="18">religion</option>
                <option value="19">science</option>
                <option value="20">sports</option>
                <option value="21">television</option>
                <option value="22">video-games</option>
                <option value="23">world</option>
            </select>
        </div>

        <!-- Difficulty Dropdown -->
        <div>
            <label for="difficulty_id" class="block text-sm font-semibold text-gray-700 mb-2">Difficulty</label>
            <select 
                id="difficulty_id" 
                name="difficulty_id"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
                <option value="">-- Select a Difficulty --</option>
                <!-- Options updated based on the new screenshot data -->
                    <option value="1">Beginner</option>
                    <option value="2">Intermediate</option>
                    <option value="3">Advanced</option>
                    <option value="4">Expert</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105"
            >
                Start Creating Questions
            </button>
        </div>
    </form>
</main>
