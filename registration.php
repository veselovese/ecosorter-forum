<?php
session_start();
?>

<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="apple-touch-icon" sizes="180x180" href="pic/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="pic/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="pic/icon/favicon-16x16.png">
    <link rel="manifest" href="pic/icon/site.webmanifest">
</head>

<body>
    <header>
        <div>
            <a class="logo" href="index.php">Twittort</a>
            <nav>
                <?php if (!isset($_SESSION['user'])) { ?>
                    <a class="hed-link-singin" href="./registration.php">Зарегистрироваться</a>
                    <a class="hed-link-singup" href="./index.php">Войти</a>
                <?php } ?>
                <?php if (isset($_SESSION['user'])) { ?>
                    <a class="hed-link" href="./profile.php">Профиль</a>
                <?php } ?>
            </nav>
        </div>
    </header>

    <main>
        <section class="section wrapper">
            <form class="singin-form" action="signup.php" method="post">
                <div class="singin-form-inputs">
                    <div>
                        <label>Имя:
                            <input class="input" type="text" name="name" placeholder="Введите имя" required>
                        </label>
                        <label>Логин:
                            <input class="input" type="text" name="login" placeholder="Введите логин" required>
                        </label>
                        <label>Email:
                            <input class="input" type="email" name="email" placeholder="Введите email" required>
                        </label>
                    </div>
                    <div>
                        <label>Пароль:
                            <input class="input" type="password" name="password" placeholder="Введите пароль" required minlength="8">
                        </label>
                        <label>Подтвердите пароль:
                            <input class="input" type="password" name="password_confirm" placeholder="Подтвердите пароль" required minlength="8">
                        </label>
                        <div id="buttons">
                            <button type="submit">Зарегистрироваться</button>
                            <a class="link" href="index.php">Войти</a>
                        </div>
                    </div>
                    <div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo '<p class="message"> ' . $_SESSION['message'] . ' </p>';
                            unset($_SESSION['message']);
                        }
                        ?>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <?php require('footer.php'); ?>
</body>

</html>