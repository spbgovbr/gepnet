<?php

require('calendar.php');

class Gantti
{

    var $cal = null;
    var $data = array();
    var $first = false;
    var $last = false;
    var $options = array();
    var $cellstyle = false;
    var $blocks = array();
    var $months = array();
    var $days = array();
    var $seconds = 0;
    var $year = null;
    var $totalDaysRange = 0;

    const semana7dias = 7;

    function __construct($data, $params = array())
    {
        $options = $this->setOptionsGannt($params);

        $this->options = $options;
        $this->cal = new Calendar();
        $this->data = $data;
        $this->seconds = 60 * 60 * 24;

        $this->cellstyle = 'style="width: ' . $this->options['cellwidth'] . 'px; height: ' . $this->options['cellheight'] . 'px"';

        // parse data and find first and last date  
        $this->parse();

        //seta a quantidade de dias no range
        $this->setDaysRange();
    }

    /**
     * Seta as confuguracoes de exibicao do gantt
     * @param array $params - Parametros de configuracao
     * @return array
     */
    public function setOptionsGannt($params)
    {
        $config = array(
            'title' => $params['title'] ?: 'Gannt',
            'cellwidth' => $params['cellwidth'] ?: 40,         //espacamento em px da celula dia
            'cellheight' => $params['cellheight'] ?: 35,        //tamanho da altura da linha
            'today' => $params['today'] ?: true,           //exibe anotacao na barra da data de hoje
            'display_meses' => isset($params['display_meses']) ?: true,   //exibir meses/anos
            'display_semanas' => isset($params['display_semanas']) ?: true, //exibir semanas
            'display_dias' => isset($params['display_dias']) ?: true,    //exibir dias
        );

        switch ($params['show_header_type']) {
            case 1:
                //seta configuracoes para exibicao de gannt por meses/anos
                $config['display_meses'] = true;    //exibir meses
                $config['display_semanas'] = false;   //exibir semanas
                $config['display_dias'] = false;   //exibir dias
                $config['cellwidth'] = 10;      //tamanho da largura da celula dia
                break;
            case 2:
                //seta configuracoes para exibicao de gannt por meses/semanas
                $config['display_meses'] = true;    //exibir meses
                $config['display_semanas'] = true;    //exibir semanas
                $config['display_dias'] = false;   //exibir dias
                $config['cellwidth'] = 20;      //tamanho da largura da celula dia
                break;
            case 3:
                //seta configuracoes para exibicao de gannt por meses/semanas
                $config['display_meses'] = true;    //exibir meses
                $config['display_semanas'] = false;   //exibir semanas
                $config['display_dias'] = true;    //exibir dias
                $config['cellwidth'] = 20;      //tamanho da largura da celula dia
                break;
            case 4:
                //seta configuracoes para exibicao de gannt por anos/meses/semanas/dias
                $config['display_meses'] = true;    //exibir meses
                $config['display_semanas'] = true;   //exibir semanas
                $config['display_dias'] = true;    //exibir dias
                $config['cellwidth'] = 20;
                $config['show_header_type'] = $params['show_header_type'];
                break;
            default:
                $config['show_header_type'] = 4;
                break;
        }
        return $config;
    }

    function parse()
    {

        foreach ($this->data as $d) {
            $this->blocks[] = array(
                'idatividade' => $d['idatividade'],
                'label' => $d['label'],
                'start' => $start = strtotime($d['start']),
                'end' => $end = strtotime($d['end']),
                'class' => @$d['class'],
                'node' => @$d['node'],
                'progress' => @$d['progress'],
                'tipoAtividade' => @$d['tipoAtividade'],
                'idpredecessora' => @$d['idpredecessora'],
            );
            if (!$this->first || (!empty($start) && $this->first > $start)) {
                $this->first = $start;
            }
            if (!$this->last || (!empty($end) && $this->last < $end)) {
                $this->last = $end;
            }
        }


        $this->first = $this->cal->date($this->first);
        $this->last = $this->cal->date($this->last);

        $current = $this->first->month();
        $lastDay = $this->last->month()->lastDay()->timestamp;

        // build the months      
        while ($current->lastDay()->timestamp <= $lastDay) {
            $month = $current->month();
            $this->months[] = $month;
            foreach ($month->days() as $day) {
                $this->days[] = $day;
            }
            $current = $current->next();
        }
    }

    /**
     * Calcula a diferenca de datas e retorna quantidade de semanas.
     * @return integer - Numero de semanas do range entre a data inicial e data final.
     */
    public function getCountSemanasRange()
    {
        $semanas = ($this->getDaysRange() / 7);
        /* se a primeira semana do range for menor que 5 dias
         * acrecenta mais uma semana.
         * correcao de estouro de layout
         */
        if ($this->getCountDaysFirstWeekRange() < 5) {
            $semanas++;
        }
        return $semanas;
    }

    /**
     * Recupera a quantidade de dias da ultima semana do range
     * @return integer
     */
    public function getContDaysLastWeekRange()
    {
        $fimRange = $this->last;
        $primeiroDiaUltimaSemana = $fimRange->month()->weeks()->last()->day();
        $daysLastWeek = 0;
        while ($primeiroDiaUltimaSemana->int() > 1) {
            $primeiroDiaUltimaSemana = $primeiroDiaUltimaSemana->next();
            $daysLastWeek++;
        }
        return $daysLastWeek;
    }

    /**
     * Seta a quantidade total de dias do range
     * @return void
     */
    public function setDaysRange()
    {
        $totalDaysRange = 0;
        foreach ($this->months as $month) {
            $totalDaysRange += $month->countDays();
        }
        $this->totalDaysRange = $totalDaysRange;
    }

    /**
     * Retorna o total de dias compreendidos do inicio ao fim do range
     * @return integer
     */
    public function getDaysRange()
    {
        return $this->totalDaysRange;
    }

    /**
     * Retorna a quantidade de dias da primeira semanada semana no mes do range
     * ATENCAO: Se a semana comeca em um mes e termina no outro nao contabiliza os dias do mes anterior.
     * @return integer Numero de dias da semana no mes
     */
    public function getCountDaysFirstWeekRange()
    {
        return $this->months[0]->weeks()->current()->lastDay()->int();
    }

    public function renderMesesCabecalho()
    {
        $html = '';
        if ($this->options['display_meses']) {

            $html = '<ul class="gantt-months totalstyle">';
            foreach ($this->months as $month) {
                $html .= '<li class="gantt-month" style="width: ' . ($this->options['cellwidth'] * $month->countDays()) . 'px">'
                    . '<strong class="cellstyle">' . $month->name() . '/' . $month->year() . '</strong>'
                    . '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function renderSemanasCabecalho()
    {
        $html = '';
        if ($this->options['display_semanas']) {
            $html = '<ul class="gantt-weeks totalstyle">';
            $semanaProjeto = 0;

            $daysFirstWeekRange = $this->getCountDaysFirstWeekRange();
            $daysLastWeekRange = $this->getContDaysLastWeekRange();
            $daysWeek = $daysFirstWeekRange;

            $totalSemanasRange = $this->getCountSemanasRange();
            while ($totalSemanasRange > 0) {
                $semanaProjeto++;
                $html .= '<li class="gantt-week" style="width: ' . ($this->options['cellwidth'] * $daysWeek) . 'px">'
                    . '<strong class="cellstyle">' . $semanaProjeto . 'ª Semana</strong>'
                    . '</li>';

                $daysWeek = self::semana7dias;
                $totalSemanasRange--;

                //se for a ultima semana do range calcula e seta a quantidade de dias
                if ($totalSemanasRange == 1 || ($totalSemanasRange > 0 && $totalSemanasRange < 1)) {
                    $daysWeek = $daysLastWeekRange;
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function renderDiasCabecalho()
    {
        $html = '';
        if ($this->options['display_dias']) {
            $html .= '<ul class="gantt-days totalstyle">';
            foreach ($this->days as $day) {

                $weekend = ($day->isWeekend()) ? ' weekend' : '';
                $today = ($day->isToday()) ? ' today' : '';

                $html .= '<li class="gantt-day wrapstyle' . $weekend . $today . '"><span class="cellstyle">' . $day->padded() . '</span></li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public function renderDiasOfItems()
    {
        $html = '';
        // main items
        $html .= '<ul class="gantt-items totalstyle">';

        foreach ($this->blocks as $i => $block) {

            $html .= '<li class="gantt-item">';

            // days
            $html .= '<ul class="gantt-days">';

            if (isset($this->options['show_header_type']) && $this->options['show_header_type'] == 4) {
                foreach ($this->days as $day) {
                    $weekend = ($day->isWeekend()) ? ' weekend' : '';
                    $today = ($day->isToday()) ? ' today' : '';

                    $html .= '<li class="gantt-day wrapstyle' . $weekend . $today . '"><span class="cellstyle"></span></li>';
                }
            } else {
                //se for a opcao for mostrar tudo no cabecalho renderiza os dias e uma unica <li> para melhorar a performance na renderizacao
                $html .= '<li class="gantt-day totalstyle largura-total"><span></span></li>';
            }
            $html .= '</ul>';

            // the block
            $days = (($block['end'] - $block['start']) / $this->seconds);
            $offset = (($block['start'] - $this->first->month()->timestamp) / $this->seconds);
            $top = round($i * ($this->options['cellheight'] + 1));
            $left = round($offset * $this->options['cellwidth']);
            $width = round(($days + 1) * $this->options['cellwidth'] - 9);
            $height = round($this->options['cellheight'] - 8);
            $progress = $block['progress'] ?: 100; //define o percentual da barra progresso
            //define a cor da barra de progresso
            $class = $this->setClassStyle($block['class']);

            //Monta o marco com tamanho fixo de 1 dia
            if ($block['tipoAtividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                $html .= '<div class="gantt-block progress ' . $class . '" style="left: ' . $left . 'px; width: ' . $this->options['cellwidth'] . 'px; height: ' . $height . 'px" title="Marco - ' . $block['label'] . '" data-placement="top" data-trigger="hover">'
                    . '<span class="bar" style="width: ' . $this->options['cellwidth'] . 'px">'
                    . '<i class="icon-flag"></i>'
                    . '</div>';

                //se existir atividade predecessora monta link de ligacao
                if (isset($block['idpredecessora']) && !empty($block['idpredecessora'])) {
                    foreach ($block['idpredecessora'] as $predecessora) {
                        $html .= $this->montaLinkPredecessora($predecessora['idatividadepredecessora'], $i, $left,
                            $width);
                    }
                }
            } else {
                $percentual = $block['progress'] ? ' - ' . $block['progress'] . '% concluído' : '';

                //se existir atividade predecessora monta link de ligacao
                if (isset($block['idpredecessora']) && !empty($block['idpredecessora'])) {
                    foreach ($block['idpredecessora'] as $predecessora) {
                        $html .= $this->montaLinkPredecessora($predecessora['idatividadepredecessora'], $i, $left,
                            $width);
                    }
                }

                //monta barra de progresso
                $html .= '<div class="gantt-block progress ' . $class . '" style="left: ' . $left . 'px; width: ' . $width . 'px; height: ' . $height . 'px" '
                    . 'data-placement="top" data-trigger="hover" data-content="' . ((int)$days + 1) . ' dia(s)' . $percentual . '" title="' . $block['label'] . '">'
                    . '<strong class="gantt-block-label bar" style="width: ' . $progress . '%;">'
                    . ((int)$days + 1) . ' dia(s)'
                    . $percentual
                    . '</strong>'
                    . '</div>';
            }
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Calcula e monta o link da atividade predecessora
     *
     * @param integer $idpredecessora - id da atividade predecessora
     * @param integer $indiceAtividade - indice da atividade corrente
     * @param integer $left - quantidade de pixel da margem direita da atividade corrente
     * @return string
     */
    public function montaLinkPredecessora($idpredecessora, $indiceAtividade, $left, $width)
    {
        $posicaoPredecessora = 0;
        $linhas = null;
        $leftPre = null;
        $widthPre = null;
        $html = '';

        $i = count($this->blocks) - 1;
        while ($i > 0) {
            if ($this->blocks[$i]['idatividade'] == $idpredecessora) {

                //informacoes da atividade predecessora para calcular o link
                $daysPre = (($this->blocks[$i]['end'] - $this->blocks[$i]['start']) / $this->seconds);
                $offsetPre = (($this->blocks[$i]['start'] - $this->first->month()->timestamp) / $this->seconds);
                $topPre = round($i * ($this->options['cellheight'] + 1));
                $leftPre = round($offsetPre * $this->options['cellwidth']);
                $widthPre = round(($daysPre + 1) * $this->options['cellwidth'] - 9);
                $heightPre = round($this->options['cellheight'] - 8);

                $posicaoPredecessora = $i;
                $linhas = ($indiceAtividade - $posicaoPredecessora); //quantidade de linhas entre a atividade e a atividade predecessora
                break;
            }
            $i--;
        }

        //se a atividade predecessora for a mesma que a ativadade corrente (nao sei se ocorrera, mas esta sedo tratado pois havia esse dado no banco)
        if ($this->blocks[$indiceAtividade]['idatividade'] == $idpredecessora) {
            //fim a fim
            $html = '<div class="arrowLeft" style="position: absolute; top: 4px; left: ' . ($left + $width + 5) . 'px; z-index: 4;"></div>'
                . '<div class="taskDepLine" style="position: absolute; left: ' . ($left + $width + 9) . 'px; width: 10px; top: 26px; z-index: 3;"></div>'
                . '<div class="taskDepLine" style="position: absolute; height: 16px; left: ' . ($left + $width + 19) . 'px;  top: 8px; z-index: 3;"></div>'
                . '<div class="taskDepLine" style="position: absolute; top: 8px; left: ' . ($left + $width + 9) . 'px; width: 10px; z-index: 3;"></div>'
                . '<div class="arrowLeft" style=" position: absolute; left: ' . ($left + $width + 5) . 'px; width: 10px; top: 22px; z-index: 4;"></div>';
        } //se atividade tiver data de inicio igual a data fim da predecessora
        else {
            if ((($left - ($leftPre + $widthPre)) < 10) && ($left - ($leftPre + $widthPre)) > -9) {
                $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: ' . ($left - 5) . 'px; z-index: 4;"></div>'
                    . '<div class="taskDepLine" style="position: absolute;left: ' . ($left - 15) . 'px; width: 10px; top: 16px; z-index: 3;"></div>'
                    . '<div class="taskDepLine" style="position: absolute;height: 16px; left: ' . ($left - 15) . 'px;  top: 0px; z-index: 3;"></div>'
                    . '<div class="taskDepLine" style="position: absolute;left: ' . ($left - 15) . 'px; width: ' . ($leftPre + $widthPre - $left + 23) . 'px; top: 0px; z-index: 3;"></div>'
                    . '<div class="taskDepLine" style="position: absolute; height: ' . ($linhas * ($this->options['cellheight'] + 1) - 16) . 'px; left: ' . ($leftPre + $widthPre + 8) . 'px;  top:-' . ($linhas * ($this->options['cellheight'] + 1) - 16) . 'px; z-index: 3;"></div>'
                    . '<div class="taskDepLine" style="position: absolute; left: ' . ($leftPre + $widthPre + 8) . 'px; width: ' . (($left - 15) - ($leftPre + $widthPre + 8)) . 'px; top: 16px; z-index: 3;"></div>'
                    . '<div class="arrowLeft" style="position: absolute; top: -' . ($linhas * ($this->options['cellheight'] + 1) - 12) . 'px; left: ' . ($leftPre + $widthPre + 5) . 'px; z-index: 4;"></div>';
            } //se atividade tiver data de inicio posterior a data da atividade predecessora
            else {
                if (($leftPre + $widthPre) < $left) {
                    if ($linhas > 0) {
                        //            //composicao da ligacao inicio a inicio
                        //            $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: '.($left - 2).'px; z-index: 3;"></div>'
                        //                    .'<div class="taskDepLine" style="left: '.($left -10).'px; width: 10px; top: 16px; z-index: 3;"></div>'
                        //                    .'<div class="taskDepLine" style="height: '.($linhas * ($this->options['cellheight']+1)).'px; left: '.($left - 12).'px;  top:-'.($linhas * ($this->options['cellheight']+1)-16) .'px; position: absolute; z-index: 3;"></div>'
                        //                    .'<div class="taskDepLine" style="left: '.($left -10).'px; width: '.($leftPre - $left+3).'px; top: -'.($linhas * ($this->options['cellheight']+1)-16) .'px;"></div>'
                        //                    .'<div class="arrowLeft" style="position: absolute; top: -'.($linhas * ($this->options['cellheight']+1)-11) .'px; left: '.($leftPre - 5).'px; z-index: 3; "></div>'
                        //            ;

                        // composicao da ligacao predecessora fim a corrente inicio (predecessora ordenada na tela acima da atividade corrente)
                        $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: ' . ($left - 5) . 'px; z-index: 4;"></div>'
                            . '<div class="taskDepLine" style="position: absolute;left: ' . ($left - 15) . 'px; width: 10px; top: 16px; z-index: 3;"></div>'
                            //.'<div class="taskDepLine" style="position: absolute;height: 16px; left: '.($left - 15).'px;  top: 0px; z-index: 3;"></div>'
                            //.'<div class="taskDepLine" style="position: absolute;left: '.($left -15).'px; width: '.($leftPre+ $widthPre - $left + 23).'px; top: 0px; z-index: 3;"></div>'
                            . '<div class="taskDepLine" style="position: absolute; height: ' . ($linhas * ($this->options['cellheight'] + 1)) . 'px; left: ' . ($leftPre + $widthPre + 8) . 'px;  top:-' . ($linhas * ($this->options['cellheight'] + 1) - 16) . 'px; z-index: 3;"></div>'
                            . '<div class="taskDepLine" style="position: absolute; left: ' . ($leftPre + $widthPre + 8) . 'px; width: ' . (($left - 15) - ($leftPre + $widthPre + 8)) . 'px; top: 16px; z-index: 3;"></div>'
                            . '<div class="arrowLeft" style="position: absolute; top: -' . ($linhas * ($this->options['cellheight'] + 1) - 12) . 'px; left: ' . ($leftPre + $widthPre + 5) . 'px; z-index: 4;"></div>';
                    } else {
                        $linhas = $linhas * (-1);
                        //composicao da ligacao predecessora fim a corrente inicio (predecessora ordenada na tela abaixo da atividade corrente)
                        $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: ' . ($left - 5) . 'px; z-index: 4;"></div>'
                            . '<div class="taskDepLine" style="position: absolute;left: ' . ($left - 15) . 'px; width: 10px; top: 16px; z-index: 3;"></div>'
                            . '<div class="taskDepLine" style="position: absolute; height: ' . ($linhas * ($this->options['cellheight'] + 1)) . 'px; left: ' . ($leftPre + $widthPre + 8) . 'px;  top: 16px; z-index: 3;"></div>'
                            . '<div class="taskDepLine" style="position: absolute; left: ' . ($leftPre + $widthPre + 8) . 'px; width: ' . (($left - 15) - ($leftPre + $widthPre + 8)) . 'px; top: 16px; z-index: 3;"></div>'
                            . '<div class="arrowLeft" style="position: absolute; top: ' . ($linhas * ($this->options['cellheight'] + 1) + 12) . 'px; left: ' . ($leftPre + $widthPre + 5) . 'px; z-index: 4;"></div>';
                    }

                    //se atividade tiver data de anterior a data da atividade predecesora
                } else {
                    if ((($leftPre + $widthPre) > $left)) {
                        if ($linhas > 0) {
                            //            //composicao da ligacao inicio a inicio
                            //            $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: '.($left - 5).'px; "></div>'
                            //                    .'<div class="taskDepLine" style="left: '.($leftPre -5).'px; width: '.($left -$leftPre).'px; top: 16px; z-index: 3;"></div>'
                            //                    .'<div class="taskDepLine" style="height: '.($linhas * ($this->options['cellheight']+1)).'px; left: '.($leftPre - 5).'px;  top:-'.($linhas * ($this->options['cellheight']+1)-16) .'px; position: absolute; z-index: 3;"></div>'
                            //                    //.'<div class="taskDepLine" style="left: '.$leftPre.'px; width: '.($leftPre - $left +5).'px; top: -'.($linhas * ($this->options['cellheight']+1)-16) .'px;"></div>'
                            //                    .'<div class="arrowRigth" style="position: absolute; top: -'.($linhas * ($this->options['cellheight']+1)-12) .'px; left: '.($leftPre - 5).'px; z-index: 3;"></div>'
                            //            ;

                            // composicao da ligacao predecessora fim a corrente inicio (predecessora ordenada na tela acima da atividade corrente)
                            $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: ' . ($left - 5) . 'px; z-index: 4; "></div>'
                                . '<div class="taskDepLine" style="left: ' . ($left - 14) . 'px; width:10px; top:16px; z-index: 3"></div>'
                                . '<div class="taskDepLine" style="height: 16px; left: ' . ($left - 15) . 'px;  top: 0px; position: absolute; z-index: 3;"></div>'
                                . '<div class="taskDepLine" style="left: ' . ($left - 15) . 'px; width: ' . ($leftPre + $widthPre - $left + 23) . 'px; top: 0px; z-index: 3;"></div>'
                                . '<div class="taskDepLine" style="height: ' . ($linhas * ($this->options['cellheight'] + 1) - 16) . 'px; left: ' . ($leftPre + $widthPre + 8) . 'px;  top:-' . ($linhas * ($this->options['cellheight'] + 1) - 16) . 'px; position: absolute; z-index: 3;"></div>'
                                //.'<div class="taskDepLine" style="left: '.($leftPre + $widthPre + 10).'px; width: '.($width - $widthPre).'px; top: -'.($linhas * ($this->options['cellheight']+1)-16) .'px;"></div>'
                                . '<div class="arrowLeft" style="position: absolute; top: -' . ($linhas * ($this->options['cellheight'] + 1) - 12) . 'px; left: ' . ($leftPre + $widthPre + 5) . 'px; z-index: 4;"></div>';
                        } else {
                            $linhas = $linhas * (-1);
                            // composicao da ligacao predecessora fim a corrente inicio (predecessora ordenada na tela abaixo da atividade corrente)
                            $html = '<div class="arrowRigth" style="position: absolute; top: 12px; left: ' . ($left - 5) . 'px; z-index: 4; "></div>'
                                . '<div class="taskDepLine" style="left: ' . ($left - 14) . 'px; width:10px; top:16px; z-index: 3"></div>'
                                . '<div class="taskDepLine" style="height: 16px; left: ' . ($left - 15) . 'px;  top: 0px; position: absolute; z-index: 3;"></div>'
                                . '<div class="taskDepLine" style="left: ' . ($left - 15) . 'px; width: ' . ($leftPre + $widthPre - $left + 23) . 'px; top: 0px; z-index: 3;"></div>'
                                . '<div class="taskDepLine" style="height: ' . ($linhas * ($this->options['cellheight'] + 1) + 16) . 'px; left: ' . ($leftPre + $widthPre + 8) . 'px;  top:0px; position: absolute; z-index: 3;"></div>'
                                . '<div class="arrowLeft" style="position: absolute; top: ' . ($linhas * ($this->options['cellheight'] + 1) + 12) . 'px; left: ' . ($leftPre + $widthPre + 5) . 'px; z-index: 4;"></div>';
                        }
                    }
                }
            }
        }

        return $html;
    }

    /**
     * Resgata a classe de acordo com informada no bloco
     *
     * @param string $param - indice da classe
     * @return string
     */
    public function setClassStyle($param)
    {
        switch ($param) {
            case 'important':
                $class = 'progress-warning';
                break;
            case 'urgent':
                $class = 'progress-danger';
                break;
            case 'success':
                $class = 'progress-success';
                break;
            default:
                $class = 'progress-info';
                break;
        }
        return $class;
    }

    /**
     * Seta barra de marcacao do dia atual
     * @param integer $altura - Quantidades de linhas antes do marcador "Hoje"
     * @return string
     */
    public function renderTodayAnotation($altura = 2)
    {
        $html = '';
        if ($this->options['today']) {
            // today
            $today = $this->cal->today();
            $offset = (($today->timestamp - $this->first->month()->timestamp) / $this->seconds);
            $left = round($offset * $this->options['cellwidth']) + round(($this->options['cellwidth'] / 2) - 1);

            if ($today->timestamp > $this->first->month()->firstDay()->timestamp && $today->timestamp < $this->last->month()->lastDay()->timestamp) {
                $html .= '<time style="top: ' . ($this->options['cellheight'] * $altura) . 'px; left: ' . $left . 'px" datetime="' . $today->format('Y-m-d') . '">Hoje</time>';
            }
        }
        return $html;
    }

    /**
     * Renderiza CSS dinamicamente para ser incorporado ao HTML. Otimização de performance.
     *
     */
    public function renderCss()
    {
        // common styles    
        $cellstyle = 'line-height: ' . $this->options['cellheight'] . 'px; height: ' . $this->options['cellheight'] . 'px;';
        $wrapstyle = 'width: ' . $this->options['cellwidth'] . 'px;';
        $totalstyle = 'width: ' . (count($this->days) * $this->options['cellwidth']) . 'px;';

        $html = '';
        $html .= '<style>
                    .cellstyle {' . $cellstyle . '}
                    .wrapstyle {' . $wrapstyle . '}
                    .totalstyle {' . $totalstyle . '}
                    .largura-total {height: ' . ($this->options['cellheight'] + 1) . 'px;}    
                </style>';
        return $html;
    }

    function render()
    {
        $html = array();

        // common styles    
//        $cellstyle = 'style="line-height: ' . $this->options['cellheight'] . 'px; height: ' . $this->options['cellheight'] . 'px"';
//        $wrapstyle = 'style="width: ' . $this->options['cellwidth'] . 'px"';
//        $totalstyle = 'style="width: ' . (count($this->days) * $this->options['cellwidth']) . 'px"';

        $html[] = $this->renderCss();
        $altura = 0;
        $altura = $this->options['display_dias'] ? ++$altura : $altura;
        $altura = $this->options['display_semanas'] ? ++$altura : $altura;
        $altura = $this->options['display_meses'] ? ++$altura : $altura;

        // start the diagram    
        $html[] = '<figure class="gantt">';

        // set a title if available
        if ($this->options['title']) {
            $html[] = '<figcaption>' . $this->options['title'] . '</figcaption>';
        }

        // sidebar with labels

        $html[] = '<aside>';
        $html[] = '<ul class="gantt-labels" style="margin-top: ' . (($this->options['cellheight'] * $altura) + 2) . 'px">';
        foreach ($this->blocks as $i => $block) {
            switch ($block['node']) {
                case 'nivel2':
                    $html[] = '<li class="gantt-label" title="' . $block['label'] . '">'
                        . '<strong class="nivel2 cellstyle"><i class="icon-tag"></i> ' . $block['label'] . '</strong>'
                        . '</li>';
                    break;
                case 'nivel3':
                    ($block['tipoAtividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) ?
                        $icon = '<i class="icon-flag"></i> ' : $icon = '<i class="icon-list-alt"></i> ';

                    $html[] = '<li class="gantt-label" title="' . $block['label'] . '">'
                        . '<strong class="nivel3 cellstyle">' . $icon . $block['label'] . '</strong>'
                        . '</li>';
                    break;

                default:
                    $html[] = '<li class="gantt-label" title="' . $block['label'] . '">'
                        . '<strong class="cellstyle"><i class="icon-folder-open"></i> ' . $block['label'] . '</strong>'
                        . '</li>';
                    break;
            }
        }
        $html[] = '</ul>';
        $html[] = '</aside>';

        // data section
        $html[] = '<section class="gantt-data">';

        // data header section
        $html[] = '<header>';
        //exibicao dos meses
        $html[] = $this->renderMesesCabecalho();
        //exibicao das semanas cabecalho
        $html[] = $this->renderSemanasCabecalho();
        // exibicao dias do cabecalho
        $html[] = $this->renderDiasCabecalho();
        //exibicao dos dias do items
        $html[] = $this->renderDiasOfItems();
        $html[] = '</header>';
        $html[] = $this->renderTodayAnotation($altura);

        // end data section
        $html[] = '</section>';

        // end diagram
        $html[] = '</figure>';

        return implode('', $html);
    }

    function __toString()
    {
        return $this->render();
    }

}
