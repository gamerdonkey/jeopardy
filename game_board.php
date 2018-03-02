<html>
<head>
   <script src="jquery-2.2.3.min.js"></script>
   <script src="jquery.modal.min.js" type="text/javascript" charset="utf-8"></script>
   <link rel="stylesheet" href="jquery.modal.min.css" type="text/css" media="screen" />
   <link rel="stylesheet" type="text/css" href="game_board_style.css">
 </head>
<body>

<div id="scores"></div>

<div class="game-board">
<?php
  $configs = include('./config.php');
  $questionsDb = new SQLite3($configs['questionsDbFile']);

  $selectCategoriesStmt = $questionsDb->prepare('SELECT rowid, title FROM categories WHERE rowid in (SELECT distinct(category_id) FROM games WHERE round_id = :round)');
  $selectCategoriesStmt->bindValue(':round', $_GET['round'], SQLITE3_INTEGER);
  $categoriesResult = $selectCategoriesStmt->execute();
  while($categoryRow = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
?>
    <ul class="category">
       <li class="display-box category-title">
         <div class="category-title-content"><?php echo $categoryRow['title']; ?></div>
       </li>

       <?php
         $selectQuestionsStmt = $questionsDb->prepare("SELECT rowid, question_text, answer, value FROM questions WHERE category_id = :category_id and rowid in (select question_id from games where round_id = :round_id and category_id = :category_id) ORDER BY value ASC");
         $selectQuestionsStmt->bindValue(':category_id', $categoryRow['rowid'], SQLITE3_INTEGER);
         $selectQuestionsStmt->bindValue(':round_id', $_GET['round'], SQLITE3_INTEGER);
         $questionsResult = $selectQuestionsStmt->execute();

         while($questionRow = $questionsResult->fetchArray(SQLITE3_ASSOC)) {
           echo '<li class="display-box question-box"><span class="question-value">$' . $questionRow['value'] . '</span>';
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

<div id="question-modal" style="display:none;">
  <p id="question-display"></p>
  <p id="answer-display"></p>
  <button id="answer-button"></button>
</div>

</body>
<script>
  $(document).ready(function () {
    var interval = 1000;   //number of mili seconds between each call
    var refresh = function() {
        $.ajax({
            url: "scores.php",
            cache: false,
            success: function(html) {
                $('#scores').html(html);
                setTimeout(function() {
                    refresh();
                }, interval);
            }
        });
    };
    refresh();
  });

  $('.question-box').click(function() {
    $(this).find('.question-value').text('');
    $('#question-display').text($(this).find(".question-text").text());
    $('#answer-display').text($(this).find(".answer-text").text());
    $('#answer-button').html('Show Answer');
    $('#answer-display').hide();
    $('#question-modal').modal({fadeDuration: 300});
  });

  $('#answer-button').click(function() {
    $('#answer-button').html($('#answer-display').text());
  });
</script>
</html>
