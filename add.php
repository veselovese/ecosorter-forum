<?php
session_start();
require('connect.php');

$userid = $_SESSION['user']['id'];
$text = $_POST["post"];

preg_match_all('/#\w+/', $text, $matches);
$hashtags = $matches[0];
$channels = ['style', 'design', 'moscow'];

$text_without_hashtags = preg_replace('/#\w+\s*/', '', $text);

foreach ($hashtags as $hashtag) {
    $hashtag = ltrim($hashtag, '#');
    
    $count = mysqli_num_rows($connect->query("SELECT id FROM hashtags WHERE name = '$hashtag'"));
    if($count > 0) {
        $result = mysqli_query($connect, "SELECT hashtags.id as hashtag_id FROM hashtags WHERE hashtags.name = '$hashtag'");
        $hashtag_id = $result->fetch_assoc()['hashtag_id'];
    } else {
        $sql_insert_hashtag = "INSERT INTO hashtags (name) VALUES ('$hashtag')";
        $connect->query($sql_insert_hashtag);
        $hashtag_id = $connect->insert_id;
    }

    $channel_id = array_search($hashtag, $channels) !== false ? array_search($hashtag, $channels) + 1 : null;
    
    $sql_insert_posts = "INSERT INTO posts (hashtag_id, channel_id, description, user_id) VALUES ('$hashtag_id', '$channel_id', '$text_without_hashtags', '$userid')";
    if (mysqli_query($connect, $sql_insert_posts)) {
        $_SESSION['message'] = 'Пост успешно добавлен';
    } else {
        echo "Error: " . $sql_insert_posts . "<br>" . mysqli_error($connect);
    }
}

header('Location: view.php?channel=all');
