<?php

include('core/database/connection.php');
include('core/classes/users.php');
include('core/classes/post.php');

global $pdo;

$loadFromUser = new User($pdo);
$loadFromPost = new Post($pdo);

define("BASE_URL", "http://localhost/facebook/");

?>