<?php


namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseHelper
{

    /**
     * Response oluşturan func.
     *
     * @param integer $code
     * @param string $msg
     * @param array $data
     * @return JsonResponse
     */
    public static function create(int $code, string $msg, array $data = []) : JsonResponse
    {
        return new JsonResponse([
            "status" => self::checkStatusCode($code),
            "message" => $msg,
            "data" => $data
        ], $code);
    }

    /**
     * Status kodu belirleyen func.
     *
     * @param integer $code
     * @return string
     */
    private static function checkStatusCode(int $code): string
    {
        $status = "";

        if ($code == 200 || $code == 201) {
            $status = "success";
        } else {
            $status = "error";
        }

        return $status;
    }

    /**
     * Array gelen validate errorlarını string haline çeviren func.
     *
     * @param [type] $violations
     * @return string
     */
    public static function validateMsgArrayToString($violations): string
    {
        $stringError = "";

        for ($i = 0; $i < $violations->count(); $i++) {
            $violation = $violations->get($i);
            $stringError .= " ". $violation->getPropertyPath() . " => ". $violation->getMessage();
        }

        return $stringError;

    }
}
