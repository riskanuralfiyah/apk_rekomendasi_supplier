<?php

while (true) {
    $waktu = date('Y-m-d H:i:s');
    file_put_contents('scheduler-log.txt', "[$waktu] Scheduler running...\n", FILE_APPEND);
    shell_exec('php artisan schedule:run >> artisan-log.txt 2>&1');
    sleep(60);
}

