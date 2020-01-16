<?php

$helpers = [
    'CustomHelper.php',
    'ConstHelper.php',
    'CacheHelper.php',
];

// 载入
foreach ($helpers as $helperFileName) {
    include __DIR__ . '/' .$helperFileName;
}