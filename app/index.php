<?php
session_start();
require('config/dbase.php');
require('libs/database.php');
require('libs/bootstrap.php');
require('libs/view.php');
require('libs/site.php');
require('libs/controller.php');
require('libs/user.php');
$db = new Database;
$site = new Site("Baza Pathfinders");
$user = new User;
$view = new View;
$site->loadFunctions();
$app = new Bootstrap;