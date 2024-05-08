<?php
session_start();
require('connect.php');

$user_id = $_SESSION['user']['id'];
$reply = $_POST['reply'];
$reply_id = $_POST['reply_id'];

mysqli_query($connect, "INSERT INTO `replies` (`id`, `reply_id`, `user_id`, `description`) VALUES (NULL, $reply_id, '$user_id', '$reply')");

header('Location: view.php?channel=all');
