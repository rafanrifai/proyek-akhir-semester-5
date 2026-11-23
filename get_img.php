<?php
$url = "https://en.wikipedia.org/w/api.php?action=query&titles=File:Emblem_of_Minahasa_Regency.svg&prop=imageinfo&iiprop=url&format=json";
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0\r\n"
    ]
]);
$data = json_decode(file_get_contents($url, false, $context), true);
$pages = $data['query']['pages'];
$first = reset($pages);
echo $first['imageinfo'][0]['url'] ?? "Not found";
