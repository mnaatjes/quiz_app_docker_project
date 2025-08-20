<?php
    // quiz_app/app/Views/quiz_view.php
    // This view displays a list of questions and their corresponding answers.

    /**
     * @var array $questions
     */
    $questions = $data["questions"];

    /**
     * @var array $quiz
     */
    $quiz = $data["quiz"];
?>

<section class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-2">
        <?php echo htmlspecialchars($quiz["title"]); ?>
    </h1>

    <div class="flex flex-col sm:flex-row sm:space-x-6 text-gray-600 font-medium text-lg">
        <p class="mb-1 sm:mb-0">
            Category: <span class="text-blue-600 font-semibold"><?php echo htmlspecialchars($quiz["category_id"] ?? 'General'); ?></span>
        </p>
        <p>
            Difficulty: <span class="text-blue-600 font-semibold"><?php echo htmlspecialchars($quiz["difficulty_id"] ?? 'Not specified'); ?></span>
        </p>
    </div>
    <form method="POST">
    <?php
        // Questions Loop
        foreach($questions as $question_index => $question):
            /**
             * @var int $question_num
             */
            $question_num = $question_index + 1;

            /**
             * @var array $answer_bullets
             */
            $answer_bullets = ["a", "b", "c", "d"];

            /**
             * @var array $answers
             */
            $answers = $question["answers"];
    ?>
        <div class="bg-gray-50 p-6 rounded-lg mb-6 shadow-sm">
            <p class="text-lg font-semibold text-gray-800 mb-3">
                <?php echo $question_num . ") " . $question["question_text"]; ?>
            </p>
            <div class="flex flex-col space-y-2">
                <?php
                    foreach ($answers as $answer_index => $answer):
                ?>
                    <span class="text-sm font-medium">
                        <input type="radio" name="q_id_<?php echo $question["id"]?>" id="a_id_<?php echo $answer["id"] ?>" value="<?php echo $answer["id"] ?>">
                        <?php echo htmlspecialchars($answer_bullets[$answer_index] . ") " . $answer['answer_text']); ?>
                    </span>
                <?php
                    endforeach;
                ?>
            </div>
        </div>
    <?php
        endforeach;
    ?>
        <div class="mt-8 flex justify-end space-x-4">
            <button type="reset" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg shadow-lg transition-transform duration-200 hover:scale-105">
                Reset
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition-transform duration-200 hover:scale-105">
                Submit Quiz
            </button>
        </div>
    </form>
    </section>