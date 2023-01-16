<?php
namespace App\Utils;

use Carbon\Carbon;
use App\Utils\Comum;
class DatetimeUtil
{

    // protected $timezone = 'America/Sao_Paulo';
    protected $timezone = 'America/Recife';

    // formato datetime sql server Y-m-d H:i:s.v
    protected $format = 'Y-m-d H:i:s';

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($tz)
    {
        $this->timezone = $tz;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($fmt)
    {
        $this->format = $fmt;
    }

    public function now($tz = null)
    {
        return Carbon::now()
                ->setTimezone($this->getTimezone())
                ->format($this->getFormat());
    }
    /**
     * Converte uma data em formato brasileiro para Americano seguindo padrão do banco
     * 
     * ex recebe 08/04/1976 
     * converte para 1976-08-04  desde que utilize o formato padrao 'Y-m-d'
     *
     * @param string $date
     * @param string $format
     * @return void
     */
    public function convertDate($date = null, $format = 'Y-m-d')
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format($format);
    }

    /**
     * Valida se tempo atual esta dentro de um periodo fornecido que pode ser data, hora, minuto ou segundo
     * 
     * Horas
     * Tipo = 2 
     * ex recebe horaInicio 0900 para 09:00 hs e horaFim 1700 para 17:00
     *
     * @param string $date
     * @param string $format
     * @return bool
     */
    public function nowInTimeInterval($tempoInicio = null, $tempoFim = null, $tipo = 2)
    {
        $tz = $this->timezone;
        // data e hora de hoje/agora
        $data_hora_hoje = Carbon::now($tz);

        // cria data e hora para manipular 
        $data_abre = Carbon::createFromTime(substr($tempoInicio, 0, -2), substr($tempoInicio, -2), 0, $tz); // 11:00
        $data_fecha = Carbon::createFromTime(substr($tempoFim, 0, -2), substr($tempoFim, -2), 0, $tz); // 12:00
        
        return $data_hora_hoje->between($data_abre, $data_fecha);

    }

    /**
     * Obter o dia da semana
     *
     * @param string $extenso
     * @return string
     */
    public function dayOfWeek($extenso = false){
        $tz = $this->timezone;
        
        if($extenso){
            return Comum::diaDaSemana(Carbon::now($tz)->dayOfWeek + 1);
        }
        // data e hora de hoje/agora
        return Carbon::now($tz)->dayOfWeek;

    }


    public function today(){
        return Carbon::today($this->timezone)->toDateTimeString();
    }
    
}


