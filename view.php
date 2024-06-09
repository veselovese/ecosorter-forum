<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css">
  <title>Посты в Twittort</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="apple-touch-icon" sizes="180x180" href="pic/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="pic/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="pic/icon/favicon-16x16.png">
  <link rel="manifest" href="pic/icon/site.webmanifest">
</head>

<body>
  <header>
    <div>
      <a class="logo" href="./view?channel=all">Twittort</a>
      <nav>
        <?php if (!isset($_SESSION['user'])) { ?>
          <a class="hed-link-singin" href="./registration">Зарегистрироваться</a>
          <a class="hed-link-singup" href="./">Войти</a>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
          <a class="hed-link-prof" href="./profile">@<?= $_SESSION['user']['login'] ?></a>
          <a class="hed-link-singout" href="./logout">Выйти</a>
        <?php } ?>
      </nav>
    </div>
  </header>
  <main>
    <section class="post section wrapper">
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
    <section class="news-line wrapper">
      <div class="new-line-div">
        <ul class="news-line__list">
          <?php
          require('connect.php');
          if (!isset($_GET['channel'])) {
            $channel = 'all';
          } else {
            $channel = $_GET['channel'];
          }
          $channel_condition = $channel !== 'all' ? "AND channels.name = '$channel'" : '';
          $sql_post = "SELECT hashtags.name AS hashtag_name, posts.description AS message, users.login AS sender, posts.id AS i
                  FROM posts
                  JOIN hashtags ON posts.hashtag_id = hashtags.id
                  JOIN users ON posts.user_id = users.id
                  LEFT JOIN channels ON posts.channel_id = channels.id
                  WHERE posts.save = 0 $channel_condition";
          $result_post = $connect->query($sql_post);
          if ($result_post->num_rows > 0) {
            while ($row_post = $result_post->fetch_assoc()) {
              $hashtag_name = $row_post["hashtag_name"];
              $message = $row_post["message"];
              $sender = $row_post["sender"];
              $i = $row_post['i'];
              echo "<li class='news-line__item__li'>";
              echo "<div class='news-line__item'>";
              echo "<p class='news-line__user' style='color: var(--link-color);'>@" . $sender . "</p>";
              echo "<p class='news-line__message'>" . $message . "</p>";
              if ($hashtag_name != null) {
                echo "<p class='news-line__hashtag'>#" . $hashtag_name . "</p>";
              }
          ?>
              <?php if (isset($_SESSION['user'])) { ?>
                <form class='news-line__form' action='./reply' method='post'>
                  <textarea class='news-line__input' type='text' name='reply' placeholder='Ответить..' required></textarea>
                  <button class='news-line__button' type='submit'>Ответить</button>
                  <input type='hidden' name='reply_id' value='<?=$i?>'>
                </form>
              <?php } ?>
          <?php
              echo "</div>";
              $sql_reply = "SELECT replies.description AS reply, users.login AS replier, replies.reply_id AS who
              FROM replies
              JOIN users ON replies.user_id = users.id
              JOIN posts ON replies.reply_id = posts.id
              WHERE replies.reply_id = " . $i;
              $result_reply = $connect->query($sql_reply);
              if ($result_reply->num_rows > 0) {
                echo "<ul class='reply-line__list'>";
                while ($row_reply = $result_reply->fetch_assoc()) {
                  $replier = $row_reply['replier'];
                  $reply = $row_reply['reply'];
                  echo "<li class='reply-line__item'>";
                  echo "<p class='reply-line__replier'>@" . $replier . ": ";
                  echo "<span class='reply-line__reply'>" . $reply . "</span></p>";
                  echo "</li>";
                }
                echo "</ul>";
              }
              echo "</li>";
            }
          } else {
            echo "<p style='font-size: 1.6rem;'>Постов ещё нет</p>";
          }
          ?>
        </ul>
        <?php if (!isset($_SESSION['user'])) { ?>
          <p>Чтобы написать пост, надо <a class="" href="./">войти</a></p>
        <?php } ?>
        <?php if (isset($_SESSION['user'])) { ?>
          <div id="add-post">
            <form class="add-form" action="./add" method="post">
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
  <?php require('footer.php'); ?>
</body>

</html>