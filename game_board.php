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
       <li class="display-box">$200</li>
       <li class="display-box">$400</li>
       <li class="display-box">$600</li>
       <li class="display-box">$800</li>
       <li class="display-box">$1000</li>
    </ul>
    <?php
  }
?>
</div>
</body>
</html>
