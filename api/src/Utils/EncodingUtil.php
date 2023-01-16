<?php
namespace App\Utils;

class EncodingUtil
{
    /**
     * converte uma string para uma codificação específica
     * 
     * RHHealth tem banco de dados em Latin enquanto que a api do cake espera Utf8
     * 
     * @link https://www.php.net/manual/pt_BR/function.iconv.php
     * @param string $string
     * @param string $in_charset
     * @param string $out_charset
     * @return string
     */
    public function convert(string $string, string $in_charset = "UTF-8", string $out_charset = "CP1252")
    {
        $string = mb_convert_encoding ($string, $out_charset, $in_charset);
        return $string;
    }

    
    public function convertNew(string $string){
        return $this->ascii($this->utf8($this->convert($string)));
    }


    function checkMultibyte($string) {
        $length = strlen($string);

      for ($i = 0; $i < $length; $i++ ) {
           $value = ord(($string[$i]));
          if ($value > 128) {
                return true;
            }
       }
        return false;
    }


    function ascii($array) {
		$ascii = '';

		foreach ($array as $utf8) {
			if ($utf8 < 128) {
				$ascii .= chr($utf8);
			} elseif ($utf8 < 2048) {
				$ascii .= chr(192 + (($utf8 - ($utf8 % 64)) / 64));
				$ascii .= chr(128 + ($utf8 % 64));
			} else {
				$ascii .= chr(224 + (($utf8 - ($utf8 % 4096)) / 4096));
				$ascii .= chr(128 + ((($utf8 % 4096) - ($utf8 % 64)) / 64));
				$ascii .= chr(128 + ($utf8 % 64));
			}
		}
		return $ascii;
	}


    function utf8($string) {
		$map = array();

		$values = array();
		$find = 1;
		$length = strlen($string);

		for ($i = 0; $i < $length; $i++) {
			$value = ord($string[$i]);

			if ($value < 128) {
				$map[] = $value;
			} else {
				if (empty($values)) {
					$find = ($value < 224) ? 2 : 3;
				}
				$values[] = $value;

				if (count($values) === $find) {
					if ($find == 3) {
						$map[] = (($values[0] % 16) * 4096) + (($values[1] % 64) * 64) + ($values[2] % 64);
					} else {
						$map[] = (($values[0] % 32) * 64) + ($values[1] % 64);
					}
					$values = array();
					$find = 1;
				}
			}
		}
		return $map;
	}
}