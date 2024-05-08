<?php

session_start();
require('connect.php');

$name = $_POST['name'];
$login = $_POST['login'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];

$resultLogin = mysqli_query($connect, "SELECT * FROM users WHERE login = '$login'");
$resultEmail = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email'");

if (!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*).{8,}$/', $password) or strlen($password)<8) {
    $_SESSION['message'] = 'Пароль минимум 8 символов, должны быть числа, знаки !? и буквы латинского алфавита';
    header('Location: registration.php');
} else if (mysqli_num_rows($resultLogin) > 0) {
    $_SESSION['message'] = 'Такой логин уже существует';
    header('Location: registration.php');
} else if (mysqli_num_rows($resultEmail) > 0) {
    $_SESSION['message'] = 'Такая почта уже зарегистрирована';
    header('Location: registration.php');
} else if ($password === $password_confirm) {

    $password = md5($password);

    mysqli_query($connect, "INSERT INTO `users` (`id`, `name`, `login`, `email`, `password`) VALUES (NULL, '$name', '$login', '$email', '$password')");

    $_SESSION['message'] = 'Регистрация прошла успешно!';
    header('Location: index.php');

} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: registration.php');
}