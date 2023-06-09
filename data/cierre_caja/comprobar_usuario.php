<?php
session_start();
$id = $_SESSION['id'];
$data = 0;
if ($id == 1 ) {
    $data = 1;
}
echo $data;
