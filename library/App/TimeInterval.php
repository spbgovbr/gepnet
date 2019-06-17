<?php

/**
 * Description of TimeInterval
 *
 * @author Wilton Barbosa da Silva Júnior
 */

/**
 * Include needed Date classes
 */
//require_once 'Zend/Date/DateObject.php';

class App_TimeInterval /*extends Zend_Date_DateObject*/
{

    protected $_data_atual = null;

    const ORIGEM_DB = 'ORIGEM_DB';
    const ORIGEM_SERVER = 'ORIGEM_SERVER';

    public function __construct()
    {
    }

    public function sysdateDb()
    {

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $stmt = $db->query("SELECT now()");
        return $stmt->fetchColumn();
    }

    public function setDataAtual($origem = self::ORIGEM_SERVER)
    {
        if ($this->_data_atual === null) {
            if ($origem === self::ORIGEM_DB) {
                $this->_data_atual = new Zend_Date($this->sysdateDb(), 'dd/MM/yyyyHH:mm:ss');
            } else {
                if (self::ORIGEM_SERVER) {
                    $this->_data_atual = Zend_Date::now();
                }
            }
        }
    }

    /**
     * @param string $data | Zend_Date objeto.
     *
     */
    public function interval($date, $format = 'dd/MM/yyyyHH:mm:ss')
    {
        $this->setDataAtual();
        return $this->tempoTotal($this->_data_atual, $date, $format);
    }

    /**
     * Calcula o tempo total entre duas datas
     * @param string | Zend_Date $data_inicial
     * @param string | Zend_Date $data_final
     * @param string $format
     * @return App_TimeInterval_Result
     */
    public function tempoTotal($data_inicial, $data_final, $format = 'dd/MM/yyyyHH:mm:ss')
    {
        $data_final = $this->retornaUnixTimestamp($data_final, $format);
        $data_inicial = $this->retornaUnixTimestamp($data_inicial, $format);

        $dias = ($data_final - $data_inicial) / 86400;
        $diasSemPontos = explode(".", $dias);
        $n_dias = floor($diasSemPontos[0]);
        $frac_dias = $dias - $n_dias;

        $horas = $frac_dias * 24;

        $n_horas = floor($horas);
        $frac_horas = $horas - $n_horas;


        $minutos = $frac_horas * 60;

        $n_minutos = floor($minutos);
        $frac_minutos = $minutos - $n_minutos;


        $segundos = $frac_minutos * 60;

        $n_segundos = floor($segundos);
        return new App_TimeInterval_Result($n_dias, $n_horas, $n_minutos, $n_segundos);
    }

    /**
     * Calcula o tempo total entre duas datas
     * @param string or Zend_Date $data_inicial
     * @param string or Zend_Date $data_final
     * @param string $format
     * @return integer
     */
    public function tempoTotalSegundos($data_inicial, $data_final, $format = 'dd/MM/yyyyHH:mm:ss')
    {
        $data_final = $this->retornaUnixTimestamp($data_final, $format);
        $data_inicial = $this->retornaUnixTimestamp($data_inicial, $format);
        return $data_final - $data_inicial;
    }

    /**
     * Calcula o tempo total entre duas datas com o dia util de 13 horas
     * @param int $segundos
     * @return App_TimeInterval_Result
     */
    public function FormataSaidaSegundos($segundos)
    {
        $dias = $segundos / 46800;
        $n_dias = floor($dias);
        $frac_dias = $dias - $n_dias;
        $horas = $frac_dias * 13;
        $n_horas = floor($horas);
        $frac_horas = $horas - $n_horas;
        $minutos = $frac_horas * 60;
        $n_minutos = floor($minutos);
        $frac_minutos = $minutos - $n_minutos;
        $segundos = $frac_minutos * 60;
        $n_segundos = floor($segundos);
        //$frac_segundos = $segundos - $n_segundos;
        return new App_TimeInterval_Result($n_dias, $n_horas, $n_minutos, $n_segundos);
    }

    /**
     * Retorna a quantidade de dias entre duas datas
     * @param string | Zend_Date $data_inicial
     * @param string | Zend_Date $data_final
     * @return App_TimeInterval_Result
     */
    public function retornaDiasEntreDatas($data_inicial, $data_final)
    {
        $format = 'dd/MM/yyyyHH:mm:ss';
        $data_final = $this->retornaUnixTimestamp($data_final, $format);
        $data_inicial = $this->retornaUnixTimestamp($data_inicial, $format);
        $dias = ($data_final - $data_inicial) / 86400;
        return new App_TimeInterval_Result($dias);
    }

    protected function retornaUnixTimestamp($data, $format = 'dd/MM/yyyyHH:mm:ss')
    {
        $zdata = $data;
        if (!$zdata instanceof Zend_Date) {
            $zdata = new Zend_Date($data, $format);
        }
        $timestamp = $zdata->getTimestamp();
        unset($zdata);
        return $timestamp;
    }

    /**
     * Calcula a diferença entre dua horas
     * @param string $hIni
     * @param string $hFinal
     * @return string or boolean
     */
    public function retornaDiferencaEntreHoras($hIni, $hFinal)
    {
        // Separa á hora dos minutos
        $hIni = explode(':', $hIni);
        $hFinal = explode(':', $hFinal);

        // Converte a hora e minuto para segundos
        $hIni = (60 * 60 * $hIni[0]) + (60 * $hIni[1]);
        $hFinal = (60 * 60 * $hFinal[0]) + (60 * $hFinal[1]);

        // Verifica se a hora final é maior que a inicial
        if (!($hIni < $hFinal)) {
            return false;
        }

        // Calcula diferença de horas
        $difDeHora = $hFinal - $hIni;

        //Converte os segundos para Hora e Minuto
        $tempo = $difDeHora / (60 * 60);
        $tempo = explode('.',
            $tempo); // Aqui divide o restante da hora, pois se não for inteiro, retornará um decimal, o minuto, será o valor depois do ponto.
        $hora = $tempo[0];
        @$minutos = (float)(0) . '.' . $tempo[1]; // Aqui forçamos a conversão para float, para não ter erro.
        $minutos = $minutos * 60; // Aqui multiplicamos o valor que sobra que é menor que 1, por 60, assim ele retornará o minuto corretamente, entre 0 á 59 minutos.
        $minutos = explode('.',
            $minutos); // Aqui damos explode para retornar somente o valor inteiro do minuto. O que sobra será os segundos
        $minutos = $minutos[0];

        return new App_TimeInterval_Result(null, $hora, $minutos, null);
    }

    /**
     * Calcula a diferença entre dua horas
     */
    public function horaExpediente($hora, $horainicioExpediente, $horafimExpediente)
    {
        $hora_aux = $this->converteHorasParaSegundos($hora);
        $horainicioExpediente_aux = $this->converteHorasParaSegundos($horainicioExpediente);
        $horafimExpediente_aux = $this->converteHorasParaSegundos($horafimExpediente);
        if ($hora_aux < $horainicioExpediente_aux) {
            return $horainicioExpediente;
        } else {
            if ($hora_aux > $horafimExpediente_aux) {
                return $horafimExpediente;
            } else {
                return $hora;
            }
        }
    }

    public function formataDiasDHMS($dias)
    {
        $n_dias = floor($dias);
        $frac_dias = $dias - $n_dias;
        $horas = $frac_dias * 24;
        $n_horas = floor($horas);
        $frac_horas = $horas - $n_horas;
        $minutos = $frac_horas * 60;
        $n_minutos = floor($minutos);
        $frac_minutos = $minutos - $n_minutos;
        $segundos = $frac_minutos * 60;
        $n_segundos = floor($segundos);
        $frac_segundos = $segundos - $n_segundos;
        return new App_TimeInterval_Result($n_dias, $n_horas, $n_minutos, $n_segundos);
    }

    /**
     * Recebe um array contendo mais de uma hora e retorna a soma de todas elas
     * @param array $times
     * @return string horas
     */
    public function somaHoras($times)
    {
        $seconds = 0;
        foreach ($times as $time) {
            list($g, $i, $s) = explode(':', $time);
            $seconds += $g * 3600;
            $seconds += $i * 60;
            $seconds += $s;
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return "{$hours}:{$minutes}:{$seconds}";
    }

    /**
     * Converte para segundos
     */
    public function converteHorasParaSegundos($hora)
    {
        $aux = explode(":", $hora);
        $hora = $aux[0];
        $minutos = $aux[1];
        $segundos = $aux[2];
        return ($hora * 3600) + ($minutos * 60) + $segundos;
    }

    /**
     * Converte de segundos para o formato hh:mm:ss
     */
    public function converteHoraMinSeg($seconds)
    {
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        return "$hours:$minutes:$seconds";
    }
}