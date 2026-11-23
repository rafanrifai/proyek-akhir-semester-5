<?php
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
]);
$data = file_get_contents('https://upload.wikimedia.org/wikipedia/commons/4/4b/Emblem_of_Minahasa_Regency.png', false, $context);
file_put_contents(__DIR__ . '/public/images/minahasa.png', $data);
echo "Done";
