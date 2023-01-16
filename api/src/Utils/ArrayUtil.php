<?php
namespace App\Utils;

class ArrayUtil
{

    /**
	 * Função para acrescentar valores em um array preservando as keys da mesma
	 * https://stackoverflow.com/questions/3353745/how-to-insert-element-into-arrays-at-specific-position?noredirect=1&lq=1
	 *
	 * @param array $arr
	 * @param array $arr_add
	 * @param integer $line posição na linha
	 * @return array
	 */
	public static function mergePreserveKeys($arr = array(), $arr_add = array(), $line = 0){
		return array_slice($arr, 0, $line, true) +	$arr_add + array_slice($arr, $line, count($arr)-$line, true);
    }

}
