<html>
<head>
   <link rel="stylesheet" type="text/css" href="game_board_style.css">
</head>
<body>

<div class="game-board">
<?php
  $questionsDb = new SQLite3('gfm_furry_jeopary_2016.db');

  $selectCategoriesStmt = $questionsDb->prepare('SELECT id, title FROM categories WHERE round = :round');
  $selectCategoriesStmt->bindValue(':round', $_GET["round"], SQLITE3_INTEGER);
  $categoriesResult = $selectCategoriesStmt->execute();
  while($categoryRow = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
    ?>
    <ul class="category">
       <li class="display-box category-title">
         <div class="category-title-content"><?php echo $categoryRow['title']; ?></div>
       </li>

       <?php
         $selectQuestionsStmt = $questionsDb->prepare('SELECT question_text, answer, value FROM questions WHERE category_id = :category_id ORDER BY value ASC');
         $selectQuestionsStmt->bindValue(':category_id', $categoryRow['id'], SQLITE3_INTEGER);
         $questionsResult = $selectQuestionsStmt->execute();

         while($questionRow = $questionsResult->fetchArray(SQLITE3_ASSOC)) {
           echo '<li class="display-box question-box">$' . $questionRow['value'];
           echo '<p class="question-text">' . $questionRow['question_text'] . '</p>';
           echo '<p class="answer-text">' . $questionRow['answer'] . '</p>';
           echo '</li>';
         }
       ?>
    </ul>
    <?php
  }
?>
</div>
</body>
</html>
