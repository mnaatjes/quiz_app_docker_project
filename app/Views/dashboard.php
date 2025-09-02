<?php
// quiz_app/app/Views/dashboard.php
// This view displays the user's dashboard with a list of quizzes.
?>
<main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <div>
        <small>
            <?php
            $user    = $data["user"];
            $quizzes = $data["user_quizzes"];
            ?>
        </small>
    </div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars($title ?? 'Dashboard'); ?>
        </h1>
        <a href="/index.php/quizzes/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            New Quiz
        </a>
    </div>
    <div>
        <b><?php echo($user["username"]);?></b>
    </div>

    <!-- Check if the quizzes data exists and is not empty -->
    <?php if (!empty($quizzes)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Completed
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Played
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Loop through the quizzes data passed from the controller -->
                    <?php foreach ($quizzes as $quiz): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($quiz['category']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo htmlspecialchars($quiz['is_completed']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo htmlspecialchars($quiz['last_played']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo htmlspecialchars($quiz['title']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/index.php/quizzes/<?php echo $quiz["id"];?>" class="text-blue-600 hover:text-blue-900">Play Again</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="bg-gray-50 p-6 rounded-lg text-center">
            <p class="text-lg text-gray-600">You haven't created or played any quizzes yet.</p>
            <a href="/index.php/quiz/create" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">
                Create Your First Quiz
            </a>
        </div>
    <?php endif; ?>
</main>
