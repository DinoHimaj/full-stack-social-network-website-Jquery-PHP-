<?php

include('database/connection.php');
include('classes/user.php');
include('classes/post.php');

global $pdo;

$loadFromUser = new User($pdo);

?>