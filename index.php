<?php
require "backend/config/autoload.php";
session_start();
$r = new Router();
$r->handleRequest($_GET);