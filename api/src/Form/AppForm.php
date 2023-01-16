<?php
namespace App\Form;

use Cake\Form\Form;

/**
 * Observador EHS
 * Validação para gravar uma classificação de risco de uma observação
 */
class AppForm extends Form
{
    
    public static $MESSAGE_REQUIRED_FIELD = 'Parâmetro requerido';    

    public static $MESSAGE_MAXLENGHT_FIELD = 'Parâmetro deve conter até %s caractere(s)';

    public static $MESSAGE_MINLENGHT_FIELD = 'Parâmetro deve ser maior que %s';

    public static $MESSAGE_NUMERIC_FIELD = 'Valor deve ser numérico';    

    public static $MESSAGE_INTEGER_FIELD = 'Valor deve ser do tipo Int';    
    
    public static $CODIGO_INT_SIZE = 10;
}