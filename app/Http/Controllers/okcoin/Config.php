<?php


namespace App\Http\Controllers\okcoin;


class Config
{
    public static $config=[
        "apiKey"=>"",
        "apiSecret"=>"",
        "passphrase"=>"",
    ];

    /**
     * @var int
     * 0 不debug
     * 1 全部debug
     * 2 jsut response body
     */
    public static $debug=0;
}
