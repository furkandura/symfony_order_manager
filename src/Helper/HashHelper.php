<?php 
namespace App\Helper;

class HashHelper {

    const ALGO = 1;

    /**
     * Bcrypt şifre hashleyen func.
     *
     * @param string $password
     * @return string
     */
    public static function hash(string $password) : string 
    {
        return password_hash($password, self::ALGO);
    }

    /**
     * Şifre eşleşiyor mu konrolü yapan func.
     *
     * @param string $password
     * @param string $hashPassword
     * @return boolean
     */
    public static function verify(string $password, string $hashPassword) : bool
    {
        $res = false;

        if (password_verify($password, $hashPassword)) {
            $res = true;
        }

        return $res;
    }
}