<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()]))
  setcookie(session_name(), '', time() - 1000, '/');
session_destroy();
if (isset($_COOKIE["csrf-token"]))
  $_COOKIE["csrf-token"] = null;
header("Location: /login");
