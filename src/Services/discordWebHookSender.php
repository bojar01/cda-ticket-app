<?php

namespace App\Services;

class discordWebHook {

    public function sender(string $url, string $name, string $tech)
    {
        $data = [
            "content" => "Il s'emblerait que ". $name ." a besoin d'aide sur " . $tech . ".",
            "username" => "ARINFO Ticket Manager"
        ];
        
        $webhook_url = $url;
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];
        
        $context = stream_context_create($options);
        file_get_contents($webhook_url, false, $context);
    }
}