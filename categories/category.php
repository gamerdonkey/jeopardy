<?php
$questionsDb = new SQLite3('../db/mff_2016_questions.db');

if( isset($_GET['id']) && !empty($_GET['id']) ) {

   $categoryId = htmlentities($_GET['id']);

   $preparedStatement = $questionsDb->prepare('SELECT title from categories where rowid = (:id)');
   $preparedStatement->bindValue('id', $categoryId);
   $result = $preparedStatement->execute();
   if(!$result) {
     echo "An error occurred!";
     return 0;
   }

   $categoryTitle = $result->fetchArray(SQLITE3_ASSOC)['title'];
}

?>
<html>
<head>
  <title><?php echo $categoryTitle ?></title>
</head>
<body>

  <h1><?php echo $categoryTitle ?></h1>

  <table border="true">
    <tr>
      <th>Clue</th>
      <th>Answer</th>
      <th>Value</th>
      <th>Used</th>
      <th></th>
    </tr>
  <?php
    $selectQuestionsStmt = $questionsDb->prepare('SELECT rowid, question_text, answer, value  FROM questions WHERE category_id = (:id)');
    $selectQuestionsStmt->bindValue('id', $categoryId);
    $questionsResult = $selectQuestionsStmt->execute();
    while($questionRow = $questionsResult->fetchArray(SQLITE3_ASSOC)) {
      ?>
      <form action="./category.php" method="post">
        <input name="rowid" type="hidden" value="<?php echo $questionRow['rowid']; ?>" />
        <tr>
          <td><input name="question-text" type="text" class="form-input" size=100 value="<?php echo $questionRow['question_text']; ?>" /></td>
          <td><input name="answer" type="text" class="form-input" value="<?php echo $questionRow['answer']; ?>" /></td>
          <td><input name="value" type="text" class="form-input" size=5 value="<?php echo $questionRow['value']; ?>" /></td>
          <td></td>
          <td><input type="submit" class="button form-submit" value="Save" /></td>
        </tr>
      </form>
      <?php
    }
  ?>

    <form id="new-question-form" action="./category.php" method="post">
      <tr>
        <td><input name="question-text" type="text" class="form-input" size=100 placeholder="New Clue" /></td>
        <td><input name="answer" type="text" class="form-input" /></td>
        <td><input name="value" type="text" class="form-input" size=5 /></td>
        <td></td>
        <td><input type="submit" class="button form-submit" value="Add" /></td>
      </tr>
    </form>

  </table>

</body>
</html>
