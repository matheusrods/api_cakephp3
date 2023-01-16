<?php
namespace App\Utils;

class ConvertUtil
{
    /**
     * usado para converte string com acentuação e passar por api json
     *
     * @param string $string
     * @param string $in_charset
     * @return string
     */
    public function stringToBase64(string $string, string $in_charset = "UTF-8")
    {
        $string = utf8_encode(htmlentities(trim($string)));
        $string = base64_encode($string);
        return $string;
    }

}