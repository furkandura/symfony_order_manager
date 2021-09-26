<?php

namespace App\Helper;

use stdClass;

class RequestHelper
{
    /**
     * Gelen istekteki jsonu parse eden func.
     *
     * @param string $body
     * @return stdClass
     */
    public static function parseJson(string $body): ?stdClass
    {
        return json_decode($body);
    }
}
