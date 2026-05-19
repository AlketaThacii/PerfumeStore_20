<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "pass",
    "perfume_store"
);

if(!$conn){
    die("Connection failed");
}
?>