<?php
    $sh = file_get_contents('/sdcard/temp/log.txt');
    echo $sh;
    file_put_contents('/sdcard/temp/log.txt','');
?>
