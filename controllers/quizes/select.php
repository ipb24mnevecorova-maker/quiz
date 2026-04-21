<?php

$topics = $db->query("SELECT * FROM topics")->fetchAll(PDO::FETCH_ASSOC);
require "views/quizes/select.view.php";