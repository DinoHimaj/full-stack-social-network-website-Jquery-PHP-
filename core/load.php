<?php
include('core/database/connection.php');
include('core/classes/users.php');
include('core/classes/utils.php');
include('core/classes/post.php');

global $pdo;

$loadFromUser = new User($pdo);
$loadFromPost = new Post($pdo);
$loadFromUtils = new Utils();

define("BASE_URL", "http://localhost/facebook/");

?>