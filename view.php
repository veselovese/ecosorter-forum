<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css">
  <title>Посты</title>
</head>

<body>
  <header>
    <div>
      <h1>Twittort</h1>
      <nav>
        <?php if (!isset($_SESSION['user'])) { ?>
          <a class="hed-link-singin" href="registration.php">Зарегистрироваться</a>
          <a class="hed-link-singup" href="index.php">Войти</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
          <a class="hed-link-singin" href="profile.php">@<?= $_SESSION['user']['login'] ?></a>
          <a class="hed-link-singup" href="logout.php">Выйти</a>
        <?php } ?>
      </nav>
    </div>
  </header>
  <main>
    <section class="post">
      <ul class="post__list">
        <li class="post__item">
          <a class="post__link <?php if ($_GET['channel'] == 'all') echo 'active'; ?>" href="view.php?channel=all">Все</a>
        </li>
        <li class="post__item">
          <a class="post__link <?php if ($_GET['channel'] == 'style') echo 'active'; ?>" href="?channel=style">Стиль</a>
        </li>
        <li class="post__item">
          <a class="post__link <?php if ($_GET['channel'] == 'design') echo 'active'; ?>" href="?channel=design">Дизайн</a>
        </li>
        <li class="post__item">
          <a class="post__link <?php if ($_GET['channel'] == 'moscow') echo 'active'; ?>" href="?channel=moscow">Москва</a>
        </li>
      </ul>
    </section>
    <section class="news-line">
      <div>
        <ul class="news-line__list">
          <?php
          require('connect.php');
          if (!isset($_GET['channel'])) {
            $channel = 'all';
          } else {
            $channel = $_GET['channel'];
          }
          $channel_condition = $channel !== 'all' ? "AND channels.name = '$channel'" : '';
          $sql = "SELECT hashtags.name AS hashtag_name, posts.description AS message, users.login AS sender
                  FROM posts
                  JOIN hashtags ON posts.hashtag_id = hashtags.id
                  JOIN users ON posts.user_id = users.id
                  LEFT JOIN channels ON posts.channel_id = channels.id
                  WHERE posts.save = 0 $channel_condition";
          $result = $connect->query($sql);
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $hashtag_name = $row["hashtag_name"];
              $message = $row["message"];
              $sender = $row["sender"];
              echo "<li class='news-line__item'>";
              echo "<p class='news-line__user' style='color: var(--link-color);'>@" . $sender . "</p>";
              echo "<p class='news-line__message'>" . $message . "</p>";
              echo "<p class='news-line__hashtag'>#" . $hashtag_name . "</p>";
              echo "</li>";
            }
          } else {
            echo "<p style='font-size: 1.6rem;'>Постов ещё нет</p>";
          }
          ?>
        </ul>
        <?php if (!isset($_SESSION['user'])) { ?>
          <p>Чтобы написать пост, надо <a class="" href="index.php">войти</a></p>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
          <div id="add-post">
            <form class="add-form" action="add.php" method="post">
              <div>
                <label>Пост
                  <textarea name="post" class="message__txt" placeholder="Ел сегодня пельмени.." required></textarea>
                </label>
              </div>
              <button class="message__btn" type="submit">Добавить запись</button>
              <?php
              if (isset($_SESSION['message'])) {
                echo '<p  style="font-size: 1.6rem;" class="msg"> ' . $_SESSION['message'] . ' </p>';
                unset($_SESSION['message']);
              }
              ?>
            </form>
          </div>
        <?php } ?>
      </div>
    </section>
  </main>
  <footer>
  </footer>
</body>

</html>