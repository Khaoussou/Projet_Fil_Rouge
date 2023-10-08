<?php

namespace App\Traits;

trait Format
{
    public function response($status, $message, $data)
    {
        return [
            "statut" => $status,
            "message" => $message,
            "data" => $data
        ];
    }
}
