<?php
namespace App\Utils;

/**
 * Classe para setar os metodos comuns dentro do sistema
 */
class Comum 
{

    public function checkRemoteFile($url) {
        $fp = @fopen($webfile, "r");
        if ($fp !== false) {
            fclose($fp);
        }
        return($fp);
    }

    public function formatarDocumento($numero) {
        if (strlen($numero) > 11) {
            $formatado = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $numero);
        } else {
            $formatado = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $numero);
        }
        return $formatado;
    }

    public function formatarPlaca($placa) {
        return strtoupper(preg_replace("/(\w{3})(\-?)(\d{3,4})/", "$1-$3", str_replace(' ', '', $placa)));
    }

    public function periodoMensal($ano_mes) {
        if (!preg_match('/^\d{4}\-\d{1,2}$/', $ano_mes)) return false;

        $periodo = array();
        $periodo['inicio'] = $ano_mes.'-01 00:00:00';
        $periodo['fim'] = $ano_mes.'-'.date('t', strtotime("{$ano_mes}-01")).' 23:59:59';

        return $periodo;
    }

    public function dataPorExtenso($data = null) {
        if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $data)) return false;
        $meses = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        $mes = substr($data, 3, 2);
        foreach($meses as $chave => $valor) {
            if ($chave == $mes) {
                return substr($data, 0, 2) . ' de ' . $valor . ' de ' . substr($data, 6, 4);
            }
        }
        return false;
    }

    public function gerarPdf($html, $nome_arquivo) {
        require_once APP . 'vendors' . DS . 'dompdf' . DS . 'dompdf_config.inc.php';

        ini_set('memory_limit', '2G');

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream($nome_arquivo);
    }

    public function gerarTCPdf($html, $nome_arquivo) {
        require_once APP . 'vendors' . DS . 'tcpdf' . DS . 'tcpdf.php';

        ini_set('memory_limit', '2G');
        set_time_limit(500);

        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 7);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nome_arquivo, 'I');
    }

    public function anoMes($data = null, $so_meses = null) {
        $meses = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        if ($so_meses) return $meses;

        $mes = substr($data, 5, 2);
        foreach ($meses as $chave => $valor) {
            if ($chave == $mes)
                return $valor;
        }
        return false;
    }

    public function listMeses($shortName = false) {
        if ($shortName) {
            return array(
                1 => 'Jan',
                2 => 'Fev',
                3 => 'Mar',
                4 => 'Abr',
                5 => 'Mai',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Ago',
                9 => 'Set',
                10 => 'Out',
                11 => 'Nov',
                12 => 'Dez'
            );
        } else {
            return array(
                1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro'
            );
        }
    }

    public function listAnos($inicio = null, $inversa = null) {
        $anos = array();
        if (!$inicio) $inicio = 2000;

        if ($inversa) {
            for ($ano = Date('Y'); $ano >= $inicio; $ano--)
                $anos[$ano] = $ano;
        } else {
            for ($ano = $inicio; $ano <= Date('Y'); $ano++)
                $anos[$ano] = $ano;
        }

        return $anos;
    }

    public function diaDaSemana($dia) {
        $dias = array(
            1 => 'Domingo',
            2 => 'Segunda-feira',
            3 => 'Terça-feira',
            4 => 'Quarta-feira',
            5 => 'Quinta-feira',
            6 => 'Sexta-feira',
            7 => 'Sabado',
        );
        return $dias[$dia];
    }

    public function diaDaSemanaExtenso($dia_abreviado) {
        $dias = array(
            "dom" => 'Domingo',
            "seg" => 'Segunda-feira',
            "ter" => 'Terça-feira',
            "qua" => 'Quarta-feira',
            "qui" => 'Quinta-feira',
            "sex" => 'Sexta-feira',
            "sab" => 'Sabado',
        );
        return $dias[$dia_abreviado];
    }

    public static function soNumero($documento) {
        return preg_replace('/\D/', '', $documento);
    }

    public static function dateToTimestamp($data) {
        $timestamp_data_alteracao = preg_replace("/(\d{2})\/(\d{2})\/(\d{2,4})(\w*)/", "$3-$2-$1$4", $data);
        return strtotime($timestamp_data_alteracao);
    }

    public function periodo($anoMes,$hora_completa = 1) {
        $dt_inicio = strtotime(substr($anoMes, 4, 2) . '/01/' . substr($anoMes, 0, 4));
        if($hora_completa == 1){
           $dt_fim = date("Ymd 23:59:59", strtotime("-1 second", strtotime("+1 month", $dt_inicio)));
        } else {
           $dt_fim = date("Ymd 23:59", strtotime("-1 second", strtotime("+1 month", $dt_inicio)));
        }

        return array(Date('Ymd 00:00:00', $dt_inicio), $dt_fim);
    }

    public function StrZero($num, $size) {
        return str_pad($num, $size, "0", STR_PAD_LEFT);
    }

    public static function ajustaFormatacao($valor) {
        if (strpos($valor, '.') > 0 && strpos($valor, ',') > 0) {
            $valor = str_replace('.', '', $valor);
        }
        return str_replace(',', '.', $valor);
    }
    
    public function dateToDb($data) {
        $dataArray = explode("/", $data, 3);
        if (count($dataArray) == 3) {
            return date("Y-m-d", mktime(0,0,0,$dataArray[1],$dataArray[0],$dataArray[2]));
        } else {
            return false;
        }
                        
    }

    function executarESalvarORelatorio($nomeRelatorio, array $parametros = null, $formatoSaida = 'PDF', $lingua = 'pt_BR') {

        require_once APP . 'vendors' . DS . 'buonny' . DS . 'RelatorioWebService.php';
        $relatorio = new RelatorioWebService;
        try {
            $r = $relatorio->executarRelatorio($nomeRelatorio, $parametros, $formatoSaida, $lingua);
            if (!empty($r)) {
                $nome_arquivo = md5(time() . rand(1, rand(1,999))) . '.' . strtolower($formatoSaida);
                $diretorioSaida = concatenarPath(dirname(__FILE__), '..', 'tmp' ,'relatorios');
                if (!is_dir($diretorioSaida)) {
                    mkdir($diretorioSaida);
                }
                $caminhoArquivo = concatenarPath($diretorioSaida, $nome_arquivo);
                $f = fopen($caminhoArquivo, 'wb');
                fwrite($f, $r);
                fclose($f);
                chmod($caminhoArquivo, 0777);
                return Router::url(
                    array(
                        'controller' => 'relatorios',
                        'action' => 'obter'
                    ), true
                ) . '?arquivo=' . urlencode($nome_arquivo);
            } else {
                return new Exception('Não foi possíel gerar o relatório.');
            }
        } catch (Exception $e) {
            return $e;
        }

    }

    public function geraParametroLinkDemonstrativoDeServico($tipo_link, $codigo_cliente, $ano_mes=null) {
        require_once(ROOT . DS . 'app' . DS . 'vendors' . DS . 'buonny' . DS . 'encriptacao.php');
        $Encriptador = new Buonny_Encriptacao();
        $retorno = $Encriptador->encriptar($tipo_link . '|' . $codigo_cliente . '|' . $ano_mes);
        return $retorno;
    }


    public function geraParametroLinkDemonstrativoExameComplementar($tipo_link, $codigo_cliente, $data_inicial, $data_final) {
        require_once(ROOT . DS . 'app' . DS . 'vendors' . DS . 'buonny' . DS . 'encriptacao.php');
        $Encriptador = new Buonny_Encriptacao();
        $retorno = $Encriptador->encriptar($tipo_link . '|' . $codigo_cliente . '|' . $data_inicial . '|' . $data_final);
        return $retorno;
    }

    public function geraParametroLinkDemonstrativoPercapita($tipo_link, $codigo_cliente, $mes, $ano) {
        require_once(ROOT . DS . 'app' . DS . 'vendors' . DS . 'buonny' . DS . 'encriptacao.php');
        $Encriptador = new Buonny_Encriptacao();
        $retorno = $Encriptador->encriptar($tipo_link . '|' . $codigo_cliente . '|' . $mes . '|' . $ano);
        return $retorno;
    }

    public function encriptarLink($link) {
        require_once(ROOT . DS . 'app' . DS . 'vendors' . DS . 'buonny' . DS . 'encriptacao.php');
        $Encriptador = new Buonny_Encriptacao();
        $retorno = $Encriptador->encriptar($link);
        return $retorno;
    }

    public function descriptografarLink($link) {
        require_once(ROOT . DS . 'app' . DS . 'vendors' . DS . 'buonny' . DS . 'encriptacao.php');
        $Encriptador = new Buonny_Encriptacao();
        $retorno = $Encriptador->desencriptar($link);
        return $retorno;
    }

    function execInBackground($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            if (DS == '\\') $cmd = str_replace('\\', '\\\\', $cmd);
            pclose(popen("start /b ". $cmd . " > null", "r"));
        }
        else {
            exec($cmd . " > /dev/null &");
        }
    }

    function getShellExec($cmd) {
      if (substr(php_uname(), 0, 7) == "Windows"){
            if (DS == '\\') $cmd = str_replace('\\', '\\\\', $cmd);
        }

      $tmp = APP . 'tmp' . DS . 'shell_result' . rand() . '.txt';

        exec($cmd . " > " . $tmp);

        $hand   = fopen($tmp, 'r');
        $string = fread($hand, filesize($tmp));

        fclose($hand);
        unlink($tmp);

        return $string;
    }

    /**
    * Calcula a quantidade de dias ÃƒÂºteis entre duas datas (sem contar feriados)
    * @param String $datainicial
    * @param [String $datafinal=null]
    * @return int Quantidade de dias ÃƒÂºteis
    */
    function diferencaDiasUteisSemContarFeriado($datainicial,$datafinal=null){
       if (!isset($datainicial))
          return false;

       $segundos_datainicial = strtotime(str_replace("/","-",$datainicial));

       if (!isset($datafinal))
          $segundos_datafinal=time();
       else
          $segundos_datafinal = strtotime(str_replace("/","-",$datafinal));

       $dias  = abs(floor(floor(($segundos_datafinal-$segundos_datainicial)/3600)/24));
       $uteis = 0;

       for($i = 1; $i <= $dias; $i++)
       {
         $diai = $segundos_datainicial+($i*3600*24);
         $w = date('w',$diai);

         if ($w>0 && $w<6){
            $uteis++;
         }
       }

       return $uteis;
    }

    function validaEmail($email_entrada,$validaTodos = FALSE) {
        $email_entrada = str_replace(' ', '', $email_entrada);

        if (strpos($email_entrada, ';') > 0) {
            $emails = explode(';', $email_entrada);

            if($validaTodos){
                foreach ($emails as $key => $email){
                    if(!Validation::email($email))
                        return FALSE;
                }
                return implode(';', $emails);

            } else {
                foreach ($emails as $key => $email){
                    if(!Validation::email($email))
                        unset($emails[$key]);
                }
                return implode(';', $emails);
            }
        } else {
            return Validation::email($email_entrada)?$email_entrada:NULL;
        }
    }

    function convertToHoursMins($time, $format = '%d:%d') {
        if ($time) {
            settype($time, 'integer');
            if ($time < 1) {
                return;
            }
            $hours = floor($time/60);
            $minutes = $time%60;
            if ($format == '%d:%d') {
                return str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0', STR_PAD_LEFT);
            } else {
                return sprintf($format, $hours, $minutes);
            }
        } else {
            return "";
        }
    }

    function convertToHoursMinsSecs($time, $format = '%d:%d:%d') {
        $time = $time / 60;
        if ($time < 0.00027) {
            return;
        }
        $hours = floor($time);
        $minutesFloat = ($time - $hours)*60;
        $minutes = floor($minutesFloat);
        $seconds = floor(($minutesFloat - $minutes)*60);
        if ($format == '%d:%d:%d') {
            return str_pad($hours, 2, '0', STR_PAD_LEFT).':'.str_pad($minutes, 2, '0', STR_PAD_LEFT).':'.str_pad($seconds, 2, '0', STR_PAD_LEFT);
        } else {
            return sprintf($format, $hours, $minutes, $seconds);
        }
    }

    function comparaArray($esperado,$retorno){
        echo '<table>';
        echo '<tr>';
        echo    '<td>ESPERADO</td>';
        echo    '<td>RETORNO</td>';
        echo '<tr>';

        echo '<tr>';
        echo    '<td><pre>';
        print_r($esperado);
        echo    '<pre></td>';
        echo    '<td><pre>';
        print_r($retorno);
        echo    '<pre></td>';
        echo '<tr>';
        echo '</table>';
    }

    function unsetField($campo,&$objeto,$teste = NULL){
        foreach ($objeto as $field => &$valor) {

            if($campo === $field){

                unset($objeto[$field]);

            } elseif(is_array($valor)) {

                $valor = self::unsetField($campo,$valor,$teste);

            }
        }

        return $objeto;
    }

    public function trata_nome($palavra){
        $a = array('á','à','ã','â');
        $palavra = str_replace($a, 'a', $palavra);
        $e = array('é','ê');
        $palavra = str_replace($e, 'e', $palavra);
        $i = array('í');
        $palavra = str_replace($i, 'i', $palavra);
        $o = array('ó','ô','õ');
        $palavra = str_replace($o, 'o', $palavra);
        $u = array('ú','ü');
        $palavra = str_replace($u, 'u', $palavra);
        $c = array('ç');
        $palavra = str_replace($c, 'c', $palavra);

        $A = array('Á','À','Ã','Â');
        $palavra = str_replace($A, 'A', $palavra);
        $E = array('É','Ê');
        $palavra = str_replace($E, 'E', $palavra);
        $I = array('Í');
        $palavra = str_replace($I, 'I', $palavra);
        $O = array('Ó','Ô','Õ');
        $palavra = str_replace($O, 'O', $palavra);
        $U = array('Ú','Ü');
        $palavra = str_replace($U, 'U', $palavra);
        $C = array('Ç');
        $palavra = str_replace($C, 'C', $palavra);

        $especiais = array('',"'","\\",'"','º','ª');
        $palavra = str_replace($especiais, '', $palavra);

        return $palavra;
   }


   /* Start é uma data no formato StrtoTime */
   function diffDate($start,$end = false) {

        if(!$end) { $end = time(); }
        if(!is_numeric($start) || !is_numeric($end)) { return false; }

        $start  = date('Y-m-d H:i:s',$start);
        $end    = date('Y-m-d H:i:s',$end);
        $d_start    = new DateTime($start);
        $d_end      = new DateTime($end);
        $diff       = $d_start->diff($d_end);
        $retorno['ano'] = $diff->format('%y');
        $retorno['mes'] = $diff->format('%m');
        $retorno['dia'] = $diff->format('%d');
        $retorno['hora']= $diff->format('%h');
        $retorno['min'] = $diff->format('%i');
        $retorno['seg'] = $diff->format('%s');
        $retorno['invert'] = $diff->invert;
        return $retorno;
    }

    function diffDuracao($start,$end = false, $tipo = 'dias') {

        $all_tipos = array(
            'dias'      => '%a',
            'horas'     => '%H',
            'minutos'   => '%I',
            'segundos'  => '%S'
        );

        if(!$end) { $end = time(); }
        if(!is_numeric($start) || !is_numeric($end)) { return false; }

        $start  = date('Y-m-d H:i:s',$start);
        $end    = date('Y-m-d H:i:s',$end);
        $d_start    = new DateTime($start);
        $d_end      = new DateTime($end);
        $diff       = $d_start->diff($d_end);

        $retorno    = array();
        if(is_array($all_tipos)){
            foreach ($tipo as $t) {
                if(!isset($all_tipos[$t]))  { return false; }

                if(isset($all_tipos[$t])){
                    $retorno[$t] = $diff->format($all_tipos[$t]);
                }
            }
        } else {
            if(!isset($all_tipos[$tipo]))  { return false; }
            $tipo = $all_tipos[$tipo];
        }

        return $retorno;
    }

    function isDate($data) {
        if (preg_match('/^(\d{1,2})(\/|\-)(\d{1,2})(\/|\-)(\d{4}).*$/', $data,$marcas)) {
            $dia = $marcas[1];
            $mes = $marcas[3];
            $ano = $marcas[5];

        } else if (preg_match('/^(\d{1,4})(\/|\-)(\d{1,2})(\/|\-)(\d{2}).*$/', $data,$marcas)) {
            $dia = $marcas[5];
            $mes = $marcas[3];
            $ano = $marcas[1];

        } else {
            return FALSE;
        }

        return checkdate($mes, $dia, $ano);
    }

    function implodeRecursivo($glue,$array){
        $ret = NULL;

        if(!is_array($array))
            return $array;

        foreach ($array as $item) {
            if (is_array($item)) {
                $ret .= self::implodeRecursivo($glue,$item) . $glue;
            } else {
                $ret .= $item . $glue;
            }
        }


        return substr($ret, 0, 0-strlen($glue));
    }

    function isVeiculo($placa){
        return preg_match("#^([a-zA-Z]{3})(\-?)(\d{3,4})$#", $placa);
        
    }

    public function isIP($ip, $permite_nulo = true) {
        if ($permite_nulo && (empty($ip))) return true;
        if (preg_match('/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $ip)) {
            return true;
        }
        return false;
    }

    function timesAgo($ptime) {
        $etime = $ptime;

        if ($etime < 1) {
            return '0 seconds';
        }

        $a = array(
            12 * 30 * 24 * 60 * 60  =>  'ano',
            30 * 24 * 60 * 60       =>  'mês',
            24 * 60 * 60            =>  'dia',
            60 * 60                 =>  'hora',
            60                      =>  'minuto',
            1                       =>  'segundo'
        );
        $string = '';

        foreach ($a as $secs => $str) {
            $d = round($etime) / $secs;

            if ($d >= 1) {
                $r = round($d);
                $string .= $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ';
                $etime = ($etime - ($r*$secs));
            }
        }
        return $string;
    }

    function converteData($data) {
        if (!empty($data)) {
            $posicao= strpos($data,'-');
            if ($posicao>0) { if($posicao==4) {
                    echo (substr($data,8,2).'/'.substr($data,5,2).'/'.substr($data,0,4));
                    if (strlen($data)>10) { echo ' '.substr($data,11,8); }
                }
            } else {
                echo $data;
            }
        } else {
            echo '';
        }
    }

    function microtime_diff($startTime, $endTime) {
        list($a_dec, $a_sec) = explode(" ", $startTime);
        list($b_dec, $b_sec) = explode(" ", $endTime);
        return $b_sec - $a_sec + $b_dec - $a_dec;
    }


    public static function validarCPF($cpf = null) {

        if(empty($cpf)) {
            return false;
        }

        $cpf = preg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        if (strlen($cpf) != 11) {
            return false;
        }
        else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
            return false;
         } else {
            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    function trataEndereco($endereco){
        $endereco = strtoupper($endereco);
        preg_match("/([A-Z\s])([A--ZÀú_\s]+)[, N.]+(\d+)/", $endereco, $match);
        $tipo = str_replace(array('R','AV','EST'),array('RUA','AVENIDA','ESTRADA'), $match[1]);
        $endereco = array(
            'endereco' => $tipo.$match[2],
            'numero' => $match[3]
        );

        return compact('endereco');
    }

    /**
     * Metodo para retirar o tipo logradouro, alameda, rua, avenida, R, av, etc
     * 
     * Param:
     * $endereco = Endereço completo com rua_nome
     * 
     * Return:
     * return: endereço sem o tipo logradouro somente o nome
     */
    function retiraTipoLogradouro($endereco) 
    {   
        //transforma em array o endereço
        $array_endereco = explode(' ', $endereco);

        //tipos logradouro
        $tipo_logradouro = array('AVENIDA', 'ATALHO', 'ACESSO', 'ADRO', 'AEROPORTO','ALAMEDA', 'ESTRADA','ÁREA','ACAMPAMENTO',
                                'ALTO','BAIXA','BALÃO','BECO','BLOCO','BOSQUE','CAIS','CAMINHO','CAMPO','CAMPUS','CANAL',
                                'CHÁCARA','CICLOVIA','CIRCULAR','COMUNIDADE','CONJUNTO','CONTORNO','ESTRADA','JARDIM', 'RUA');
        
        //verifica se tem o valor no array
        if(in_array(strtoupper($array_endereco[0]), $tipo_logradouro)) {
            unset($array_endereco[0]);
        }//fim verificacao

        //retorna o endereco compactado
        return implode(" ",$array_endereco);

    } //fim retiraTipoLogradouro

    function validarCNPJ($cnpj){
        $cnpj = str_pad(str_replace(array('.','-','/'),'',$cnpj),14,'0',STR_PAD_LEFT);
        if (strlen($cnpj) != 14){
            return false;
        }else{
            for($t = 12; $t < 14; $t++){
                for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++){
                    $d += $cnpj{$c} * $p;
                    $p  = ($p < 3) ? 9 : --$p;
                }
                $d = ((10 * $d) % 11) % 10;
                if($cnpj{$c} != $d){
                    return false;
                }
            }
            return true;
        }
    }

    function mod($dividendo,$divisor){
        return round($dividendo - (floor($dividendo/$divisor)*$divisor));
    }

    function gerarCpf($compontos = FALSE){
        $n1 = rand(0,9);
        $n2 = rand(0,9);
        $n3 = rand(0,9);
        $n4 = rand(0,9);
        $n5 = rand(0,9);
        $n6 = rand(0,9);
        $n7 = rand(0,9);
        $n8 = rand(0,9);
        $n9 = rand(0,9);
        $d1 = $n9*2+$n8*3+$n7*4+$n6*5+$n5*6+$n4*7+$n3*8+$n2*9+$n1*10;
        $d1 = 11 - ( Comum::mod($d1,11) );
        if ( $d1 >= 10 ){
            $d1 = 0 ;
        }
        $d2 = $d1*2+$n9*3+$n8*4+$n7*5+$n6*6+$n5*7+$n4*8+$n3*9+$n2*10+$n1*11;
        $d2 = 11 - ( Comum::mod($d2,11) );
        if ($d2>=10){
            $d2 = 0 ;
        }
        $retorno = '';
        if ($compontos==1){
            $retorno = ''.$n1.$n2.$n3.".".$n4.$n5.$n6.".".$n7.$n8.$n9."-".$d1.$d2;
        }
        else{
            $retorno = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$d1.$d2;
        }
        return $retorno;
    }

    function gerarCnpj($compontos = FALSE){
        $n1 = rand(0,9);
        $n2 = rand(0,9);
        $n3 = rand(0,9);
        $n4 = rand(0,9);
        $n5 = rand(0,9);
        $n6 = rand(0,9);
        $n7 = rand(0,9);
        $n8 = rand(0,9);
        $n9 = 0;
        $n10= 0;
        $n11= 0;
        $n12= 1;
        $d1 = $n12*2+$n11*3+$n10*4+$n9*5+$n8*6+$n7*7+$n6*8+$n5*9+$n4*2+$n3*3+$n2*4+$n1*5;
        $d1 = 11 - ( Comum::mod($d1,11) );
        if ( $d1 >= 10 ){
            $d1 = 0 ;
        }
        $d2 = $d1*2+$n12*3+$n11*4+$n10*5+$n9*6+$n8*7+$n7*8+$n6*9+$n5*2+$n4*3+$n3*4+$n2*5+$n1*6;
        $d2 = 11 - ( Comum::mod($d2,11) );
        if ($d2>=10){
            $d2 = 0 ;
        }
        $retorno = '';
        if ($compontos==1){
            $retorno = ''.$n1.$n2.".".$n3.$n4.$n5.".".$n6.$n7.$n8."/".$n9.$n10.$n11.$n12."-".$d1.$d2;
        }
        else{
            $retorno = ''.$n1.$n2.$n3.$n4.$n5.$n6.$n7.$n8.$n9.$n10.$n11.$n12.$d1.$d2;
        }
        return $retorno;
    }

    function distancia_entre_dois_pontos($lat1, $lon1, $lat2, $lon2){
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }

    function time_to_decimal($time, $tipo = 'minutos') {
        $timeArr = explode(':', $time);
        if (!isset($timeArr[2])) $timeArr[2] = 0;
        switch($tipo) {
            case 'horas':
                $decTime = ($timeArr[0]) + ($timeArr[1]/60) + ($timeArr[2]/3600);   
                break;
            case 'minutos':
                $decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
                break;
            case 'segundos':
                $decTime = ($timeArr[0]*3600) + ($timeArr[1]*60) + ($timeArr[2]);
                break;
            default:
                $decTime = ($timeArr[0]*60) + ($timeArr[1]) + ($timeArr[2]/60);
        }
     
        return $decTime;
    }

    function decimal_to_time($decimal, $tipo = 'minutos', $exibe_segundos = true) {
        $horas = 0; $minutos = 0; $segundos = 0;
        switch($tipo) {
            case 'dias':
                $horas = $decimal*24;
                break;
            case 'horas':
                $horas = $decimal;
                break;
            case 'minutos':
                $horas = floor($decimal / 60);
                $minutos = $decimal % 60;
                break;
            case 'segundos':
                $horas = floor($decimal / 3600);
                $decimal = $decimal % 3600;
                $minutos = floor($decimal / 60);
                $segundos = $decimal % 60;
                break;
            default:
                $horas = $decimal;
        }
        $hora_formatada = ($horas<10?sprintf("%02s",$horas):$horas).":".sprintf("%02s",$minutos).($exibe_segundos?":".sprintf("%02s",$segundos):"" );
        return $hora_formatada;
    }

    private function convertToXML($arr, &$xml) {

        foreach($arr as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml->addChild("$key");
                    Comum::convertToXML($value, $subnode);
                }
                else{
                    $subnode = $xml->addChild("item$key");
                    Comum::convertToXML($value, $subnode);
                }
            }
            else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }
        
    }

    function objectToArray($d) {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(array('Comum','objectToArray'), $d);
        }
        else {
            return $d;
        }
    }

    function objectToXML($object, $tag_pai) {
        $array = Comum::objectToArray($object);

        $xml = new SimpleXMLElement("<".$tag_pai."></".$tag_pai.">");
        Comum::convertToXML($array,$xml);

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML($dom->documentElement);
    }   

    function arrayToObject($array) {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = Comum::arrayToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }   

    function somaTotalizador(&$array_totalizador, $array_append) {
        foreach ($array_totalizador as $key => $valor) {
            if (empty($array_totalizador[$key])) $array_totalizador[$key] = 0;
            $array_totalizador[$key] += (isset($array_append[$key]) && is_numeric($array_append[$key]) ? $array_append[$key] : 0);
        }
    }

    function documentoValido() {
        $model_documento = & ClassRegistry::init('Documento');
        $codigo_documento = $this->data[$this->name]['CNPJCPF'];
        if($model_documento->isCPF($codigo_documento) == false && $model_documento->isCNPJ($codigo_documento) == false)
            return false;
        else
            return true;
    }

    public static function formataData($data_in, $formato_in = "timestamp", $formato_out = "dmyhms")  {
      if (strlen($data_in) > 0){
        $dia = $mes = $ano = $hora = $minuto = $segundo = "";
        $separador_DH = stristr($data_in,"T") ? "T" : " ";
        switch ($formato_in){
          case "timestamp":
            $data_in  = explode($separador_DH,$data_in);
            $data     = explode("-",$data_in[0]);
            $tempo    = count($data_in) > 1 ? explode(":",$data_in[1]) : "";
            if(is_array($data)){
              $dia      = isset($data[2]) ? $data[2] : "";
              $mes      = isset($data[1]) ? $data[1] : "";
              $ano      = isset($data[0]) ? $data[0] : "";
            }
            if(is_array($tempo)){
              $hora     = isset($tempo[0]) ? $tempo[0] : "00";
              $minuto   = isset($tempo[1]) ? $tempo[1] : "00";
              $segundo  = isset($tempo[2]) ? $tempo[2] : "00";
            }
          break;
          case "dmyhms":
            $data_in                        = explode($separador_DH,$data_in);
            list($dia,$mes,$ano)            = explode("/",$data_in[0]);
            list($hora,$minuto,$segundo)    = explode(":",$data_in[1]);
          break;
          case "ymd":
            list($ano,$mes,$dia)  = explode("-",$data_in);
          break;
          case "dmy":
            list($dia,$mes,$ano)  = explode("/",$data_in);
          break;
          case "hms":
            list($hora,$minuto,$segundo)  = explode(":",$data_in);
          break;
          case "ymd_stand":
            $ano = substr($data_in,0,4);
            $mes = substr($data_in,4,2);
            $dia = substr($data_in,6,2);
          break;
          case "dmy_stand":
            $dia = substr($data_in,0,2);
            $mes = substr($data_in,2,2);
            $ano = substr($data_in,4,4);
          break;
          case "mssql":
            $dtTimeStamp    = strtotime($data_in);
            $dataConvertida = date('d/m/Y H:i:s', $dtTimeStamp);
            $dia = substr($dataConvertida,0,2);
            $mes = substr($dataConvertida,3,2);
            $ano = substr($dataConvertida,6,4);
            list($hora, $minuto, $segundo) = explode(":",substr($dataConvertida,11));
          break;
        }
        $dia = sprintf("%02d", $dia);
        $mes = sprintf("%02d", $mes);
        $ano = sprintf("%04d", $ano);   
        switch ($formato_out){
          case "timestamp":
            $formato  = $ano."-".$mes."-".$dia." ".$hora.":".$minuto.(!empty($segundo) ? ":".$segundo : "");
          break;
          case "iso":
            $formato  = $ano."-".$mes."-".$dia."T".$hora.":".$minuto.(!empty($segundo) ? ":".$segundo : "");
          break;        
          case "iso_stand":
             $formato  = $ano.$mes.$dia." ".$hora.":".$minuto.":".(!empty($segundo) ? ":".$segundo : "");
          break;
          case "dmyhms":
            $formato  = $dia."/".$mes."/".$ano." ".$hora.":".$minuto.(!empty($segundo) ? ":".$segundo : "");
          break;
          case "ymd":
            $formato  = $ano."-".$mes."-".$dia;
          break;
          case "ymd_stand":
            $formato  = $ano.$mes.$dia;
          break;
          case "dmy":
            $formato  = $dia."/".$mes."/".$ano;
          break;
          case "dmy_stand":
            $formato  = $dia.$mes.$ano;
          break;        
          case "hm":
            $formato  = $hora.":".$minuto;
          break;
          case "hms":
            $formato  = $hora.":".$minuto.(!empty($segundo) ? ":".$segundo : "");
          break;
          case "timestamp_mssql":
            $formato  = $ano."".$mes."".$dia." ".$hora.":".$minuto.(!empty($segundo) ? ":".$segundo : "");
          break;

        }
      }else{
        $formato = "";
      }
      return $formato;
    }

    function range($check, $lower = null, $upper = null, $equal = false) {
        
        if (!is_numeric($check)) {
            return false;
        }
        if (isset($lower) && isset($upper)) {
            if ($equal) {
                return ($check >= $lower && $check <= $upper);
            } else {
                return ($check > $lower && $check < $upper);
            }
        }
        return is_finite($check);
    }

    function validaDateTime($datetime = "", $format = 'dmy', $separador = ' ') {
        if (empty($datetime)) return false;
        $arrAux = explode($separador,$datetime);
        if (count($arrAux)!=2) {
          return false;
        }
        $data = trim($arrAux[0]);
        $hora = trim($arrAux[1]);

        $retorno = true;
        $retorno = $retorno && Validation::date($data,$format);
        $retorno = $retorno && Validation::time($hora);

        return $retorno;

    }   

    function formataCEP($cep){
        $cep_formatado = substr($cep, 0,5).' - '.substr($cep, 5);
        return $cep_formatado;
    }

    public function formata_string_para_utf8($campo_string = '') {
        if (mb_detect_encoding($campo_string,'UTF-8','ISO-8859-1')!='UTF-8') {
            return utf8_encode($campo_string);
        }else {
            return $campo_string;
        }
    }

    public function cores_hexadecimal_aleatorio($cerquilha = true) {
        $color = '';
        $letters = '0123456789ABCDEF';
        
        if($cerquilha)
            $color = '#';

        for($i = 0; $i < 6; $i++) {
            $index = rand(0,15);
            $color .= $letters[$index];
        }
        return $color;
    }

    function formatarTelefone($tel='') {
        $numero = preg_replace("/[^0-9]/i",'',$tel);
        if (trim($numero)=='') return '';
        if (strlen($numero) <10) {
            if (strlen($numero) <= 8) {
                $formatado = preg_replace("/(\d{4})(\d{4})/", "$1-$2", $numero);
            } else {
                $formatado = preg_replace("/(\d{5})(\d{4})/", "$1-$2", $numero);
            }
        } else {
            if (strlen($numero) <= 10) {
                $formatado = preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $numero);
            } else {
                $formatado = preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $numero);
            }
        }
        return $formatado;
    }

    function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(ç)/", "/(Ç)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(º)/"),explode(" ","a c C A e E i I o O u U n N"),$string);
    }

    /**
     * Metodo para formartar data do arquivo cnab
     * 
     * @param: $data = 030517
     * 
     * @return: $data = 2017-05-03
     */ 
    public function formatarDataCnab($data)
    {
        if(empty($data)) {
            return false;
        }

        //separa os algorismos
        $dia = substr($data, 0,2);
        $mes = substr($data, 2,2);
        $ano = '20'.substr($data, 4);

        //retorna a data formatada
        return $ano.'-'.$mes.'-'.$dia;

    }//fim formatarDataCnab

    public function __formata_data($data) {
        return substr($data, 6, 2) . "-" . substr($data, 4, 2) . "-" . substr($data, 0, 4);
    }//FINAL FUNCTION __formata_data

    /**
     * Metodo para formartar o valor do arquivo cnab
     * 
     * @param: $valor = 0000000013442
     * 
     * @return: $valor = 134.42
     */ 
    public function formatarValorCnab($valor)
    {
        if(empty($valor)) {
            return false;
        }

        //retira os zeros a esquerda
        $valor = ltrim($valor,"0");
        //decimal e radiando
        $decimal = substr($valor,-2);
        $radiando = substr($valor,0,-2);
        //monta valor

        $valor = $radiando.".".$decimal;

        //retorna o valor formatado
        return $valor;

    }//fim formatarDataCnab


    /**
     * Metodo para formartar data do arquivo cnab240
     * 
     * @param: $data = 03052017
     * 
     * @return: $data = 2017-05-03
     */ 
    public function formatarDataCnab240($data)
    {
        if(empty($data)) {
            return false;
        }

        //separa os algorismos
        $dia = substr($data, 0,2);
        $mes = substr($data, 2,2);
        $ano = substr($data, 4);

        //retorna a data formatada
        return $ano.'-'.$mes.'-'.$dia;

    }//fim formatarDataCnab


    /**
     * [sendFileToServer description]
     * 
     * metodo para envair a imagem para o servidor de arquivos
     * 
     * 
     *   //monta o array para enviar
     *   $data = array(
     *       'file'=> '@'.arquivo, 
     *       'prefix' => 'nina',
     *       'type' => 'base64'
     *   );
     * 
     *   //url de imagem
     *   $url_imagem = Comum::sendFileToServer($dados);
     * 
     *   $caminho_image = array('path' => $url_imagem->{'response'}->{'path'});
     * 
     * 
     * @param  [type] $absFileName [description]
     * @param  string $prefix      [description]
     * @return [type]              [description]
     */
    public static function sendFileToServer($data)
    {
        // $data = array(
        //     'file'=> $absFileName, 
        //     'prefix' => $prefix);
        
        // debug($data);

        $cURL = curl_init();
        curl_setopt( $cURL, CURLOPT_URL, FILE_SERVER."/upload" );
        curl_setopt( $cURL, CURLOPT_POST, true );
        curl_setopt( $cURL, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $cURL, CURLOPT_RETURNTRANSFER, true );

        $result = curl_exec( $cURL );
        
        /*$err = curl_error($cURL);
        curl_close($cURL);
        debug($err);
        debug($result);
        exit;*/


        $result = json_decode($result);
        curl_close ($cURL);

        return $result;
    }//fim sendFileToServer


    /**
     * [formataHora description]
     * 
     * formata hora
     * 
     * @param  [type] $hr [description]
     * @return [type]     [description]
     */
    public function formataHora($hr)
    {

        if(strlen($hr) == 3) {
            $dados = '0'.substr($hr, 0,1).':'.substr($hr,-2);
        }
        else {
            $dados = substr($hr, 0,2).':'.substr($hr,-2);
        }

        return $dados;

    }//fim formataHora

    /**
     * [isJson verifica se é json]
     * @param  [type]  $json [description]
     * @return boolean       [description]
     */
    public function isJson($json) 
    {
        json_decode($json);
        return (json_last_error() == JSON_ERROR_NONE);
    }//fim isJson

    /**
     * [jsonToArray transforma o json em array]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function jsonToArray($data = null)
    {
        if(!is_null($data)) {
            $json = (array)json_decode($data);
            foreach ($json as $key => $value) {
                if(is_object($value)) {
                    $json[$key] = (array)$value;
                } else {
                    $json[$key] = $value;
                }
            }
            $data = $json;
        }
        return $data;
    }


    public function converterEncodingPara( $strText , $strConvertEncoding = 'ISO-8859-1' )
    {

        // encodings possíveis que podemos trabalhar
        // Alguns foram comenrados para não exigir mais processamento
        $arrEncodings = array(
            'CP1251',
            'UCS-2LE',
            'UCS-2BE',
            'UTF-8',
            'UTF-16',
            'UTF-16BE',
            'UTF-16LE',
            'UTF-32',
            'CP866',
            'CP850',
            'ISO-8859-1', // No mb_detect_encoding detecta que nosso banco tem campos neste encoding
            'Windows-1252'
        );
        
        $encoding = mb_detect_encoding($strText, $arrEncodings, true);
        
        // detectou que a string é de encoding iso-8859-1 não é preciso fazer nada
        if($encoding == 'ISO-8859-1')
        {

            $strText = mb_convert_encoding($strText, 'Windows-1252', 'ISO-8859-1');

            // mas se esta forçando converter iso-8859 para UTF-8
            if($strConvertEncoding == "UTF-8")
            {
                $strText = mb_convert_encoding($strText, 'ISO-8859-1', "UTF-8");
            }
        } else {
            
            // se estiver em encoding utf-8 ou outro apenas converta do sql-server para corrigir registros com acentuação irregular
            $strText = mb_convert_encoding($strText, 'Windows-1252', "UTF-8");
        }

        return $strText;
    }

    	/**
	 * Função para tratar sql criadas em RAW, sem conditions do cakephp. O [ codigo_cliente ] era 
	 * esperado um numero e agora pode ser um array com vários codigos, assim de = passa usar IN nas consultas
	 *
	 * @param [mixed] $codigo_cliente
	 * @return string
	 */
	public static function rawsql_codigo_cliente($codigo_cliente){

		if(is_array($codigo_cliente)){
			return ' IN ('.implode(",",$codigo_cliente).')';
		} else {
			return ' = '.$codigo_cliente;
		}

	}

}//fim class comum
