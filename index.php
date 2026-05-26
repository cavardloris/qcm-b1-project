<?php
session_start();
require "backend/config/autoload.php";
$r = new Router();
$r->handleRequest($_GET);