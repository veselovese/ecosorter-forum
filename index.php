<?php
session_start();
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twittort</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="apple-touch-icon" sizes="180x180" href="pic/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="pic/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="pic/icon/favicon-16x16.png">
    <link rel="manifest" href="pic/icon/site.webmanifest">
</head>

<body>
    <header>
        <div>
            <a class="logo" href="./">Twittort</a>
            <nav>
                <?php if (!isset($_SESSION['user'])) { ?>
                    <a class="hed-link-singin" href="./registration">Зарегистрироваться</a>
                    <a class="hed-link-singup" href="./">Войти</a>
                <?php } ?>
                <?php if (isset($_SESSION['user'])) { ?>
                    <a class="hed-link-singin" href="./profile">@<?= $_SESSION['user']['login'] ?></a>
                    <a class="hed-link-singup" href="./logout">Выйти</a>
                <?php } ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="section wrapper">
            <div class="singup">
                <form class="singup-form" action="./login" method="post">
                    <label>Логин
                        <input type="text" name="login" placeholder="Введите логин" required>
                    </label>
                    <label>Пароль
                        <input type="password" name="password" placeholder="Введите пароль" required minlength='8'>
                    </label>
                    <div id="buttons">
                        <button type="submit">Войти</button>
                        <a href="./registration">Зарегистрироваться</a>
                    </div>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
                        unset($_SESSION['message']);
                    }
                    ?>
                </form>
                <div class="singup-lastpost">
                    <p class="p">Последний пост</p>
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
                            WHERE posts.save = 0 $channel_condition
                            ORDER BY posts.id DESC LIMIT 1";
                    $result = $connect->query($sql);
                    if ($result->num_rows > 0) {
                        if ($row = $result->fetch_assoc()) {
                            $hashtag_name = $row["hashtag_name"];
                            $message = $row["message"];
                            $sender = $row["sender"];
                            echo "<div class='news-line__item'>";
                            echo "<p class='news-line__user' style='color: var(--link-color);'>@" . $sender . "</p>";
                            echo "<p class='news-line__message'>" . $message . "</p>";
                            echo "<p class='news-line__hashtag'>#" . $hashtag_name . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p style='font-size: 2rem;'>Нет сообщений.</p>";
                    }
                    ?>
                    <a href="./view?channel=all">Все посты</a>
                </div>
            </div>
        </section>
    </main>
    <?php require('footer.php'); ?>
</body>

</html>