<?php
    // Extract the main data objects for easier access

use App\Utils\Utility;

    //Utility::printJSON($data);
    $userQuiz = $data["user_quiz"];
    $quiz = $data["quiz"];

    // Determine score color based on performance
    $score_int  = $userQuiz["correct_answers_count"] / $userQuiz["total_questions"];
    $score      = $score_int * 100;
    $scoreColorClass = 'text-gray-800'; // Default color
    if ($score >= 80) {
        $scoreColorClass = 'text-green-600';
    } elseif ($score >= 50) {
        $scoreColorClass = 'text-yellow-600';
    } else {
        $scoreColorClass = 'text-red-600';
    }

    // Determine correct class
    $correctBgClass = ($score > 75) ? "bg-green-100" : "bg-gray-50";
    $incorrectBgClass = ($score < 75) ? "bg-gray-50" : "bg-red-100";
    $skippedBgClass = ($userQuiz["skipped_questions_count"] > 0) ? "bg-red-100" : "bg-gray-50";

?>

<main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md max-w-2xl">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
            Quiz Results
        </h1>
        <p class="mt-1 text-xl text-gray-600 font-medium">
            <?php echo htmlspecialchars($quiz["title"]); ?>
        </p>
    </div>

    <!-- Main Score Display -->
    <div class="text-center bg-gray-50 rounded-lg p-8 my-6">
        <p class="text-lg font-medium text-gray-500 uppercase tracking-wider">Your Score</p>
        <p class="text-7xl font-bold mt-2 <?php echo $scoreColorClass; ?>">
            <?php echo round($score); ?>%
        </p>
    </div>

    <!-- Detailed Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
        <div class="<?php echo $correctBgClass;?> p-4 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Correct</p>
            <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $userQuiz["correct_answers_count"]; ?></p>
        </div>
        <div class="<?php echo $incorrectBgClass;?> p-4 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Incorrect</p>
            <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $userQuiz["incorrect_answers_count"]; ?></p>
        </div>
        <div class="<?php echo $skippedBgClass;?> p-4 rounded-lg shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Skipped</p>
            <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo $userQuiz["skipped_questions_count"]; ?></p>
        </div>
    </div>

    <!-- Metadata -->
    <div class="mt-6 border-t border-gray-200 pt-4 text-sm text-gray-500">
        <div class="flex justify-between">
            <span>Completed On:</span>
            <span class="font-medium text-gray-700"><?php echo date("F j, Y, g:i a", strtotime($userQuiz["completed_at"])); ?></span>
        </div>
        <div class="flex justify-between mt-1">
            <span>Total Questions:</span>
            <span class="font-medium text-gray-700"><?php echo $userQuiz["total_questions"]; ?></span>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mt-8 text-center">
        <a href="/index.php/dashboard" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105">
            Back to Dashboard
        </a>
        <a href="/index.php/quizzes/<?php echo $quiz["id"]; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform transition-transform duration-200 hover:scale-105" style="margin-left: 6px;">
            Replay Quiz
        </a>
    </div>
</main>
