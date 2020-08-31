<?php

namespace App\Hashers;

use Vinkla\Hashids\Facades\Hashids;

class UserHasher
{
    /**
     * Encode
     *
     * @param  $id
     * @return String
     */
    public static function encode(int $id) : string
    {
        return Hashids::connection('main')->encode($id);
    }

    /**
     * Decode
     *
     * @param  encoded string
     * @return Integer
     */
    public static function decode(string $enc) : int
    {
        $ids = Hashids::connection('main')->decode($enc);

        if (count($ids) < 1) {
            abort(404);
        }

        return $ids[0];
    }
}
