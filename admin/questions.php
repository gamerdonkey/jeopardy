<html>
<head>
</head>
<body>
  <table border="true">
  <?php
    $questionsDb = new SQLite3('../db/mff_2016_questions.db');

    $selectCategoriesStmt = $questionsDb->prepare('SELECT rowid, title, round FROM categories');
    $categoriesResult = $selectCategoriesStmt->execute();
    while($categoryRow = $categoriesResult->fetchArray(SQLITE3_ASSOC)) {
      ?>
      <tr>
        <td><?php echo $categoryRow['rowid']; ?></td>
        <td><?php echo $categoryRow['title']; ?></td>
        <td><?php echo $categoryRow['round']; ?></td>
      </tr>
      <?php
    }
  ?>
  </table>
</body>
</html>
