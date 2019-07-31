<?php

class Projeto_Service_Gantt extends App_Service_ServiceAbstract
{

    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Atividadecronograma();
    }

    public function getFormGantt()
    {
        $this->_form = new Projeto_Form_Gantt();
        return $this->_form;
    }

    /**
     * Monta os array para exibicao do gantt
     * @param integer $idprojeto - identificador do projeto
     * @return array
     */
    public function montaDadosCronogramaGantt($params)
    {
        $resultAtividades = $this->_mapper->retornaGrupoPorProjeto($params);
        $data = array();
        if (isset($resultAtividades) && !empty($resultAtividades)) {
            $contAtiv = 0;
            $contEnt = 0;
            foreach ($resultAtividades as $g => $grupo) {
                if ((@isset($grupo->datinicio))) {
                    if (count($grupo->entregas) > 0) {
                        //monta atividades nivel 1
                        /* @var $grupo Projeto_Model_Grupocronograma */
                        $data[] = array(
                            'idatividade' => $grupo->idatividadecronograma,
                            'label' => $grupo->nomatividadecronograma,
                            'domatividade' => $grupo->domtipoatividade,
                            //'domatividade' => $grupo->nv1_domtipoatividade,
                            'idgrupo' => $grupo->idgrupo,
                            'start' => $grupo->datinicio->format('Y-m-d'),
                            'end' => $grupo->datfim->format('Y-m-d'),
                            'progress' => $grupo->numpercentualconcluido,
                            'node' => 'nivel1',
                            'class' => 'success'
                        );
                        if (count($grupo->entregas) > 0) {
                            foreach ($grupo->entregas as $e => $entrega) {
                                /* @var $entrega Projeto_Model_Entregacronograma */
                                //monta atividades nivel 2
                                if ((!($entrega->datinicio instanceof DateTime)) ||
                                    (!($entrega->datfim instanceof DateTime))) {
                                    continue;
                                }
//                                if((@trim($entrega->datinicio)=="")||(@trim($entrega->datfim)=="")){
//                                    continue;
//                                }
                                $data[] = array(
                                    'idatividade' => $entrega->idatividadecronograma,
                                    'label' => $entrega->nomatividadecronograma,
                                    'domatividade' => $entrega->domtipoatividade,
                                    'idgrupo' => $entrega->idgrupo,
                                    'start' => $entrega->datinicio->format('Y-m-d'),
                                    'end' => $entrega->datfim->format('Y-m-d'),
                                    'progress' => $entrega->numpercentualconcluido,
                                    'node' => 'nivel2',
                                    'class' => 'important'
                                );
                                foreach ($entrega->atividades as $atividade) {
                                    //monta atividades nivel 3
                                    if ($atividade->domtipoatividade == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                                        if ((@trim($params['idatividadecronograma']) != "")) {
                                            if ($atividade->idatividadecronograma != @trim($params['idatividadecronograma'])) {
                                                continue;
                                            }
                                        }
                                        if ((@trim($params['idatividademarco']) != "")) {
                                            if ($atividade->idatividadecronograma != @trim($params['idatividademarco'])) {
                                                continue;
                                            }
                                        }
                                        $data[] = array(
                                            'idatividade' => $atividade->idatividadecronograma,
                                            'label' => $atividade->nomatividadecronograma,
                                            'domatividade' => $atividade->domtipoatividade,
                                            'idgrupo' => $atividade->idgrupo,
                                            'start' => $atividade->datinicio->format('Y-m-d'),
                                            'end' => $atividade->datfim->format('Y-m-d'),
                                            'progress' => $atividade->numpercentualconcluido,
                                            'class' => 'urgent',
                                            'node' => 'nivel3',
                                            'tipoAtividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO,
                                            'idpredecessora' => $atividade->predecessoras,
                                        );
                                    } else {
                                        if ((@trim($params['idatividadecronograma']) != "")) {
                                            if ($atividade->idatividadecronograma != @trim($params['idatividadecronograma'])) {
                                                continue;
                                            }
                                        }
                                        if ((@trim($params['idatividademarco']) != "")) {
                                            if ($atividade->idatividadecronograma != @trim($params['idatividademarco'])) {
                                                continue;
                                            }
                                        }
                                        $data[] = array(
                                            'idatividade' => $atividade->idatividadecronograma,
                                            'label' => $atividade->nomatividadecronograma,
                                            'domatividade' => $atividade->domtipoatividade,
                                            'idgrupo' => $atividade->idgrupo,
                                            'start' => $atividade->datinicio->format('Y-m-d'),
                                            'end' => $atividade->datfim->format('Y-m-d'),
                                            'progress' => $atividade->numpercentualconcluido,
                                            'node' => 'nivel3',
                                            'idpredecessora' => $atividade->predecessoras,
                                        );
                                    }
                                    $contAtiv++;
                                }
                                $contEnt++;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Monta os array para exibicao do gantt
     * @param integer $idprojeto - identificador do projeto
     * @return array
     */
    public function montaDadosGantt($params)
    {
        $atividadePredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        $resultAtividades = $this->_mapper->retornaAtividadeGantt($params);
        $itemNivel1 = '';
        $itemNivel2 = '';
        $itemNivel3 = '';
        $data = array();
        foreach ($resultAtividades as $atividade) {
            //monta atividades nivel 1
            if ($itemNivel1 != $atividade['nv1_idatividadecronograma']) {
                $data[] = array(
                    'idatividade' => $atividade['nv1_idatividadecronograma'],
                    'label' => $atividade['nv1_nomatividadecronograma'],
                    'domatividade' => $atividade['nv1_domtipoatividade'],
                    'idgrupo' => $atividade['nv1_idgrupo'],
                    'start' => $atividade['nv1_datinicio'],
                    'end' => $atividade['nv1_datfim'],
                    'node' => 'nivel1',
                    'class' => 'success'
                );
                $itemNivel1 = $atividade['nv1_idatividadecronograma'];
            }

            //monta atividades nivel 2
            if ($atividade['nv2_idatividadecronograma'] != "" && $atividade['nv2_idatividadecronograma'] != $itemNivel2) {
                $data[] = array(
                    'idatividade' => $atividade['nv2_idatividadecronograma'],
                    'label' => $atividade['nv2_nomatividadecronograma'],
                    'domatividade' => $atividade['nv2_domtipoatividade'],
                    'idgrupo' => $atividade['nv2_idgrupo'],
                    'start' => $atividade['nv2_datinicio'],
                    'end' => $atividade['nv2_datfim'],
                    'node' => 'nivel2',
                    'class' => 'important'
                );
                $itemNivel2 = $atividade['nv2_idatividadecronograma'];
            }

            //monta atividades nivel 3
            if ($atividade['nv3_idatividadecronograma'] != "" && $atividade['nv3_idatividadecronograma'] != $itemNivel3) {
                if ($atividade['nv3_domtipoatividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                    if ((@trim($params['idatividadecronograma']) != "")) {
                        if ($atividade['nv3_idatividadecronograma'] != @trim($params['idatividadecronograma'])) {
                            continue;
                        }
                    }
                    if ((@trim($params['idatividademarco']) != "")) {
                        if ($atividade['nv3_idatividadecronograma'] != @trim($params['idatividademarco'])) {
                            continue;
                        }
                    }
                    $predecessoraMarco = $atividadePredecessora->retornaPorAtividadeProjeto(array(
                        'idprojeto' => $params['idprojeto'],
                        'idatividade' => $atividade['nv3_idatividadecronograma']
                    ));
                    $data[] = array(
                        'idatividade' => $atividade['nv3_idatividadecronograma'],
                        'label' => $atividade['nv3_nomatividadecronograma'],
                        'domatividade' => $atividade['nv3_domtipoatividade'],
                        'idgrupo' => $atividade['nv3_idgrupo'],
                        'start' => $atividade['nv3_datinicio'],
                        'end' => $atividade['nv3_datfim'],
                        'class' => 'urgent',
                        'node' => 'nivel3',
                        'tipoAtividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO,
                        'idpredecessora' => @$predecessoraMarco,
                    );
                } else {
                    if ((@trim($params['idatividadecronograma']) != "")) {
                        if ($atividade['nv3_idatividadecronograma'] != @trim($params['idatividadecronograma'])) {
                            continue;
                        }
                    }
                    if ((@trim($params['idatividademarco']) != "")) {
                        if ($atividade['nv3_idatividadecronograma'] != @trim($params['idatividademarco'])) {
                            continue;
                        }
                    }
                    $predecessora = $atividadePredecessora->retornaPorAtividadeProjeto(array(
                        'idprojeto' => $params['idprojeto'],
                        'idatividade' => $atividade['nv3_idatividadecronograma']
                    ));
                    $data[] = array(
                        'idatividade' => $atividade['nv3_idatividadecronograma'],
                        'label' => $atividade['nv3_nomatividadecronograma'],
                        'domatividade' => $atividade['nv3_domtipoatividade'],
                        'idgrupo' => $atividade['nv3_idgrupo'],
                        'start' => $atividade['nv3_datinicio'],
                        'end' => $atividade['nv3_datfim'],
                        'progress' => $atividade['nv3_numpercentualconcluido'],
                        'node' => 'nivel3',
                        'idpredecessora' => @$predecessora,
                    );

                }

                $itemNivel3 = $atividade['nv3_idatividadecronograma'];
            }
        }
        return $data;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getObjetoGraficoGantt($params)
    {
        /*******************************************************************************
         * gantt php class example and configuration file
         * this example shows a full example with all resources
         * and dependencies
         * version 0.1
         * Copyright (C) 2005 Alexandre Miguel de Andrade Souza
         *
         * This library is free software; you can redistribute it and/or
         * modify it under the terms of the GNU General Public
         * License as published by the Free Software Foundation; either
         * version 2 of the License.
         * Please see the accompanying file COPYING for licensing details!
         *
         * If you need a commercial license of this class to your project, please contact
         * alexandremasbr@gmail.com
         *******************************************************************************/
        /**/
        @ini_set('memory_limit', '512M');
        $statusreport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        if ($params['chkfiltro'] != "1") {
            $params['idgrupo'] = "";
            $params['identrega'] = "";
            $params['idatividadecronograma'] = "";
            $params['idatividademarco'] = "";
        }
        $projeto = $mapperGerencia->getByIdTapImprimir($params);

        /* Possibilidades - chkfiltro - QUAIS ITENS MOSTRAR
        1 - Filtro Ativo
        2 - Linha de Grupo
        3 - Linha de Grupo / Entrega
        4 - Linha de Grupo / Entrega / Marco
        5 - Linha de Grupo / Entrega / Marco / Atividade
        */
        $entregasView = (in_array($params['chkfiltro'], array("1", "3", "4", "5")) ? true : false);
        $atividadesMarcoView = (in_array($params['chkfiltro'], array("1", "4", "5")) ? true : false);
        $atividadesView = (in_array($params['chkfiltro'], array("1", "5")) ? true : false);

        /* if ($params['chkfiltro'] == "3") {
            $entregasView = true;
            $atividadesView = false;
            $atividadesMarcoView = false;
        }/**/
        /****************************/
        $listaStatusreport = $statusreport->retornaAcompanhamentosPorProjeto(array(
            'idprojeto' => $params['idprojeto'],
            'sidx' => 'datcadastro',
            'sord' => 'desc'
        ), $paginator = false);
        foreach ($listaStatusreport as $itemStatusReport) {
            if ($itemStatusReport['domstatusprojeto'] != 8) {
                $dataStReport = $itemStatusReport['datacompanhamento'];
                break;
            } else {
                $dataStReport = $itemStatusReport['datacompanhamento'];
            }
        }
        $DataTStReport = $dataStReport->toString('d/m/Y');
        $diaStReport = $dataStReport->toString('d');
        $mesStReport = $dataStReport->toString('m');
        $anoStReport = $dataStReport->toString('Y');
        $DataNumStReport = $anoStReport . $mesStReport . $diaStReport;
        //THIS START STANDARD DEFINITIONS TO CLASS, YOU DONT NEED TO CHANGE THIS SETTINGS, ONLY IF YOU WANT

        $definitions = [];
        $definitions['holidays'] = Projeto_Service_AtividadeCronograma::getFeriados();
        $definitions['display_dias'] = true;
        //generic  definitions to graphic, you dont need to change this. Only if you want
        $definitions['title_y'] = 10; // absolute vertical position in pixels -> title string
        $definitions['planned']['y'] = 6;  // relative vertical position in pixels -> planned/baseline
        $definitions['planned']['height'] = 8;  // height in pixels -> planned/baseline
        $definitions['planned_adjusted']['y'] = 25; // relative vertical position in pixels -> adjusted planning
        $definitions['planned_adjusted']['height'] = 8;  // height in pixels -> adjusted planning
        $definitions['real']['y'] = 26; // relative vertical position in pixels -> real/realized time
        $definitions['real']['height'] = 5;  // height in pixels -> real/realized time
        $definitions['progress']['y'] = 11; // relative vertical position in pixels -> progress
        $definitions['progress']['height'] = 2;  // height in pixels -> progress
        $definitions['img_bg_color'] = array(180, 180, 180); //color of background
        $definitions['title_color'] = array(0, 0, 0); //color of title
        $definitions['text']['color'] = array(0, 0, 0); //color of title
        $definitions['title_bg_color'] = array(255, 255, 255); //color of background of title
        $definitions['milestone']['title_bg_color'] = array(180, 180, 180); //color of background of title of milestone
        $definitions['today']['color'] = array(0, 0, 0); //color of today line
        $definitions['status_report']['color'] = array(255, 50, 0); //color of last status report line
        $definitions['real']['hachured_color'] = array(
            204,
            0,
            0
        );// color of hachured of real. to not have hachured, set to same color of real
        $definitions['workday_color'] = array(255, 255, 255); //white -> default color of the grid to workdays
        $definitions['grid_color'] = array(230, 230, 230); //default color of weekend days in the grid
        $definitions['holiday_color'] = $definitions['grid_color']; //default color of holiday in the grid
        $definitions['groups']['color'] = array(77, 170, 77);// set color of groups
        $definitions['groups']['bg_color'] = array(180, 180, 180);// set color of background to groups title
        $definitions['tasks']['color'] = array(77, 0, 0);// set color of tasks
        $definitions['tasks']['bg_color'] = array(180, 180, 180);// set color of background to tasks title
        $definitions['planned']['color'] = array(122, 119, 119);// set color of initial planning/baseline
        $definitions['planned_adjusted']['color'] = array(0, 0, 204); // set color of adjusted planning
        $definitions['real']['color'] = array(255, 255, 255);//set color of work done
        $definitions['progress']['color'] = array(101, 192, 219); // set color of progress/percentage completed
        $definitions['milestones']['color'] = array(219, 94, 89); //set the color to milestone icon
        //$definitions['img_width']                              = 900; //set the img_width
        //$definitions['img_height']                             = 400; //set the img_height

        //if you want a ttf font set this values
        // just donwload a ttf font and set the path
        // find ttf fonts at http://www.webpagepublicity.com/free-fonts.html -> more than 6500 free fonts
        //$definitions['text']['ttfont']['file']                 = '../library/gantt.class/font/arial.ttf'; // set path and filename of ttf font -> coment to use gd fonts
        //$definitions['text']['ttfont']['size']                 = '8'; // used only with ttf
        //define font colors
        $definitions['title']['ttfont']['file'] = '../library/App/Gantt/font/arial.ttf'; // set path and filename of ttf font -> coment to use gd fonts
        $definitions['title']['ttfont']['size'] = '16'; // used only with ttf

        // these are default value if not set a ttf font
        $definitions['text_font'] = 3; //define the font to text -> 1 to 4 (gd fonts)
        $definitions['title_font'] = 3;  //define the font to title -> 1 to 4 (gd fonts)

        //define font colors
        $definitions["group"]['text_color'] = array(0, 0, 0);
        $definitions["tasks"]['text_color'] = array(0, 0, 0);
        $definitions["legend"]['text_color'] = array(0, 0, 0);
        $definitions["milestone"]['text_color'] = array(0, 0, 0);
        $definitions["phase"]['text_color'] = array(0, 0, 0);

        // set to 1 to a continuous line
        $definitions['status_report']['pixels'] = 15; //set the number of pixels to line interval
        $definitions['today']['pixels'] = 10; //set the number of pixels to line interval

        // set colors to dependency lines -> both  dependency planned(baseline) and dependency (adjusted planning)
        @$definitions['dependency_color']['END_TO_START'] = array(0, 0, 0);//black
        @$definitions['dependency_color']['START_TO_START'] = array(0, 0, 0);//black
        @$definitions['dependency_color']['END_TO_END'] = array(0, 0, 0);//black
        @$definitions['dependency_color']['START_TO_END'] = array(0, 0, 0);//black

        //set the alpha (tranparency) to colors of bars/icons/lines
        $definitions['planned']['alpha'] = 40; //transparency -> 0-100
        $definitions['planned_adjusted']['alpha'] = 40; //transparency -> 0-100
        $definitions['real']['alpha'] = 0; //transparency -> 0-100
        $definitions['progress']['alpha'] = 0; //transparency -> 0-100
        $definitions['groups']['alpha'] = 40; //transparency -> 0-100
        //$definitions['tasks']['alpha']            = 40; //transparency -> 0-100
        $definitions['today']['alpha'] = 10; //transparency -> 0-100
        $definitions['status_report']['alpha'] = 10; //transparency -> 0-100
        $definitions['dependency']['alpha'] = 80; //transparency -> 0-100
        $definitions['milestones']['alpha'] = 10; //transparency -> 0-100


        // set the legends strings
        $definitions['planned']['legend'] = 'PLANEJAMENTO INICIAL';
        $definitions['planned_adjusted']['legend'] = 'PLANEJAMENTO AJUSTADO';
        $definitions['real']['legend'] = 'REALIZADO';
        $definitions['progress']['legend'] = 'PROGRESSO';
        $definitions['milestone']['legend'] = 'MARCO';
        $definitions['today']['legend'] = 'HOJE';
        $definitions['status_report']['legend'] = 'ULTIMO STATUS REPORT';

        //set the size of each day in the grid for each scale
        $definitions['limit']['cell']['m'] = '4'; // size of cells (each day)
        $definitions['limit']['cell']['w'] = '8'; // size of cells (each day)
        $definitions['limit']['cell']['d'] = '20';// size of cells (each day)

        //set the initial positions of the grid (x,y)
        $definitions['grid']['x'] = 480; // initial position of the grix (x)
        $definitions['grid']['y'] = 40; // initial position of the grix (y)

        //set the height of each row of phases/phases -> groups and milestone rows will have half of this height
        $definitions['row']['height'] = 40; // height of each row

        $definitions['legend']['y'] = 85; // initial position of legent (height of image - y)
        $definitions['legend']['x'] = 180; // distance between two cols of the legend
        $definitions['legend']['y_'] = 25; //distance between the image bottom and legend botton
        $definitions['legend']['ydiff'] = 25; //diference between lines of legend

        //other settings
        //  if you want set progress bar on planned bar (the x point), if not set, default is on planned_adjusted bar -> you need to adjust $definitions['progress']['y'] to progress y stay over planned bar or whatever you want;
        $definitions['progress']['bar_type'] = 'planned';
        $definitions["not_show_tasks"] = false; // if set to true not show groups, but still need to set phases to a group
        $definitions["not_show_groups"] = false; // if set to true not show groups, but still need to set phases to a group

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // THIS IS THE BEGINNING OF YOUR CHART SETTINGS
        //global definitions to graphic
        // change to you project data/needs
        $definitions['title_string'] = utf8_decode($projeto->nomprojeto); //project title
        $definitions['locale'] = "pt_BR";//change to language you need -> en = english, pt_BR = Brazilian Portuguese etc
        //define the scale of the chart
        $definitions['limit']['detail'] = 'w'; //w week, m month , d day
        //$resultadoAtividades = $this->montaDadosGantt($params);
        $resultadoAtividades = $this->montaDadosCronogramaGantt($params);
        if ($resultadoAtividades) {
            $i_nv2 = $id_nv2 = $id_nv3 = $id_pred = $i_Mile = $i_nvm = 0;
            $data_menor = date("Y-m-d");
            if ((mktime(0, 0, 0, $mesStReport, $diaStReport, $anoStReport)) < mktime(0, 0, 0, date("m"), date("d"),
                    date("Y"))) {
                $data_menor = date("Y-m-d", mktime(0, 0, 0, $mesStReport, $diaStReport, $anoStReport));
            }
            foreach ($resultadoAtividades as $atividade) {
                $data_inicio = date("Y-m-d", strtotime($atividade['start']));
                $data_fim = date("Y-m-d", strtotime($atividade['end']));
                if (!(isset($data_maior))) {
                    $data_maior = date("Y-m-d", strtotime($atividade['end']));
                }
                $data_menor = ($data_inicio < $data_menor ? $data_inicio : $data_menor);
                $data_menor = ($data_fim < $data_menor ? $data_fim : $data_menor);
                $data_maior = ($data_inicio > $data_maior ? $data_inicio : $data_maior);
                $data_maior = ($data_fim > $data_maior ? $data_fim : $data_maior);
                if ($atividade['domatividade'] == "1") {
                    $dia_ini_nv1 = date('d', strtotime($atividade['start']));
                    $mes_ini_nv1 = date('m', strtotime($atividade['start']));
                    $ano_ini_nv1 = date('Y', strtotime($atividade['start']));
                    $dia_fim_nv1 = date('d', strtotime($atividade['end']));
                    $mes_fim_nv1 = date('m', strtotime($atividade['end']));
                    $ano_fim_nv1 = date('Y', strtotime($atividade['end']));
                    $definitions['groups']['group'][$atividade['idatividade']]['name'] = utf8_decode($atividade['label']);
                    $definitions['groups']['group'][$atividade['idatividade']]['start'] = mktime(0, 0, 0, $mes_ini_nv1,
                        $dia_ini_nv1, $ano_ini_nv1);
                    $definitions['groups']['group'][$atividade['idatividade']]['end'] = mktime(0, 0, 0, $mes_fim_nv1,
                        $dia_fim_nv1, $ano_fim_nv1);
                }
                if ($entregasView == true) {
                    if ($atividade['domatividade'] == "2") {
                        if ($id_nv2 != $atividade['idgrupo']) {
                            $i_nv2 = 0;
                        }
                        $dia_ini_nv2 = date('d', strtotime($atividade['start']));
                        $mes_ini_nv2 = date('m', strtotime($atividade['start']));
                        $ano_ini_nv2 = date('Y', strtotime($atividade['start']));
                        $dia_fim_nv2 = date('d', strtotime($atividade['end']));
                        $mes_fim_nv2 = date('m', strtotime($atividade['end']));
                        $ano_fim_nv2 = date('Y', strtotime($atividade['end']));
                        // you need to set a group to every phase(=phase) to show it rigth
                        // 'group'][0]  -> 0 is the number of the group to associate task
                        // ['phase'][0] = 0; 0 and 0 > the same value -> is the number of the tasks to associate to group
                        $definitions['groups']['group'][$atividade['idgrupo']]['task'][$i_nv2++] = $atividade['idatividade'];
                        // you need to set tasks to graphic be created
                        $definitions['tasks']['task'][$atividade['idatividade']]['name'] = utf8_decode($atividade['label']);
                        $definitions['tasks']['task'][$atividade['idatividade']]['start'] = mktime(0, 0, 0,
                            $mes_ini_nv2, $dia_ini_nv2, $ano_ini_nv2);
                        $definitions['tasks']['task'][$atividade['idatividade']]['end'] = mktime(0, 0, 0, $mes_fim_nv2,
                            $dia_fim_nv2, $ano_fim_nv2);
                        $id_nv2 = $atividade['idgrupo'];
                    }
                }
                if (($atividadesView == true) || ($atividadesMarcoView == true)) {
                    if (($atividade['domatividade'] == "3") || ($atividade['domatividade'] == "4")) {
                        if ($id_nv3 != $atividade['idgrupo']) {
                            $i_nv3 = 0;
                        }
                        $dia_ini_nv3 = date('d', strtotime($atividade['start']));
                        $mes_ini_nv3 = date('m', strtotime($atividade['start']));
                        $ano_ini_nv3 = date('Y', strtotime($atividade['start']));
                        $dia_fim_nv3 = date('d', strtotime($atividade['end']));
                        $mes_fim_nv3 = date('m', strtotime($atividade['end']));
                        $ano_fim_nv3 = date('Y', strtotime($atividade['end']));
                        if ($atividade['domatividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                            if ($atividadesMarcoView == true) {
                                // milestones are products or objectives of project. Set if you want. In this case, you need to set
                                // a data, a title and a task to each milestone
                                $definitions['milestones']['milestone'][$i_nvm]['data'] = mktime(0, 0, 0, $mes_ini_nv3,
                                    $dia_ini_nv3, $ano_ini_nv3);
                                $definitions['milestones']['milestone'][$i_nvm]['title'] = utf8_decode($atividade['label']);
                                //define a group to milestone
                                //$definitions['groups']['group'][0]['milestone'][0]  = 0; //need to set a group to show
                                //define a task to milestone
                                $definitions['tasks']['task'][$atividade['idgrupo']]['milestone'][$i_Mile++] = $i_nvm++; //need to set a task to show
                            }
                        } else {
                            if ($atividadesView == true) {
                                // you need to set a task to every phase(=phase) to show it rigth
                                // 'task'][0] -> 0 is the number of the task to associate phases
                                // ['phase'][0] = 0; 0 and 0 > the same value -> is the number of the phase to associate to task
                                $definitions['tasks']['task'][$atividade['idgrupo']]['phase'][$i_nv3++] = $atividade['idatividade'];
                                //you have to set planned phase name even when show only planned adjusted
                                $definitions['planned']['phase'][$atividade['idatividade']]['name'] = utf8_decode($atividade['label']);
                                //define the start and end of each phase. Set only what you want/need to show. Not defined values will not draws bars
                                $definitions['planned']['phase'][$atividade['idatividade']]['start'] = mktime(0, 0, 0,
                                    $mes_ini_nv3, $dia_ini_nv3, $ano_ini_nv3);
                                $definitions['planned']['phase'][$atividade['idatividade']]['end'] = mktime(0, 0, 0,
                                    $mes_fim_nv3, $dia_fim_nv3, $ano_fim_nv3);
                                //$definitions['planned_adjusted']['phase'][$atividade['idatividade']]['start'] = mktime(0, 0, 0, 12, 2, 2015);
                                //$definitions['planned_adjusted']['phase'][$atividade['idatividade']]['end'] = mktime(0, 0, 0, 1, 18, 2016);
                                //$definitions['real']['phase'][$atividade['idatividade']]['start'] = mktime(0, 0, 0, 12, 28, 2015);
                                //$definitions['real']['phase'][$atividade['idatividade']]['end'] = mktime(0, 0, 0, 1, 14, 2016);
                                //define a percentage/progress to phase. Set only if you want.
                                $definitions['progress']['phase'][$atividade['idatividade']]['progress'] = 70;
                                if (is_array(($atividade['idpredecessora']))) {
                                    foreach ($atividade['idpredecessora'] as $predecessora) {
                                        if ($predecessora['idatividade'] > $predecessora['idatividadepredecessora']) {
                                            $definitions['dependency_planned'][$id_pred]['type'] = END_TO_START;
                                        } else {
                                            $definitions['dependency_planned'][$id_pred]['type'] = START_TO_END;
                                        }
                                        $definitions['dependency_planned'][$id_pred]['phase_from'] = $predecessora['idatividade'];
                                        $definitions['dependency_planned'][$id_pred]['phase_to'] = $predecessora['idatividadepredecessora'];
                                        $id_pred++;
                                    }
                                }
                            }
                        }
                        $id_nv3 = $atividade['idgrupo'];
                    }
                }
            }
        }
        $dia_menor = date('d', strtotime($data_menor));
        $mes_menor = date('m', strtotime($data_menor));
        $ano_menor = date('Y', strtotime($data_menor));
        // Aumenta dois meses na data final
        $time_data_maior = strtotime("+15 days", strtotime($data_maior));
        $data_maior = date("Y-m-d", $time_data_maior);
        $dia_maior = date('d', strtotime($data_maior));
        $mes_maior = date('m', strtotime($data_maior));
        $ano_maior = date('Y', strtotime($data_maior));

        $time_hoje_soma = strtotime("+15 days", strtotime(date("Y-m-d")));
        $data_hoje_soma = date("Y-m-d", $time_hoje_soma);
        $dia_hoje_soma = date('d', strtotime($data_hoje_soma));
        $mes_hoje_soma = date('m', strtotime($data_hoje_soma));
        $ano_hoje_soma = date('Y', strtotime($data_hoje_soma));

        //define data information about the graphic. this limits will be adjusted in month and week scales to fit to
        //start of month of start date and end of month in end date, when the scale is month
        // and to start of week of start date and end of week in the end date, when the scale is week
        $definitions['limit']['start'] = mktime(0, 0, 0, $mes_menor, 01,
            $ano_menor); //these settings will define the size of
        if ($time_data_maior > $time_hoje_soma) {
            $definitions['limit']['end'] = mktime(23, 59, 59, $mes_maior, $dia_maior,
                $ano_maior); //graphic and time limits
        } else {
            $definitions['limit']['end'] = mktime(23, 59, 59, $mes_hoje_soma, $dia_hoje_soma,
                $ano_hoje_soma); //graphic and time limits
        }
        $hoje = date("Y-m-d");
        $dia_hoje = date('d', strtotime($hoje));
        $mes_hoje = date('m', strtotime($hoje));
        $ano_hoje = date('Y', strtotime($hoje));
        // define the data to draw a line as "today"
        $definitions['today']['data'] = mktime(0, 0, 0, $mes_hoje, $dia_hoje,
            $ano_hoje); //time();//draw a line in this date
        // define the data to draw a line as "last status report"
        $definitions['status_report']['data'] = mktime(0, 0, 0, $mesStReport, $diaStReport,
            $anoStReport); //time();//draw a line in this date
        ///////////////////////////////////////////////////
        // THE END -> generate the graphic
        // TO SET THE KIND OF GRAFIC GENERATED
        if ($params['formato'] == "image/gif") {
            $definitions['image']['type'] = 'gif'; // can be png, jpg, gif  -> if not set default is png    $definitions['image']['type']= 'gif';
        } elseif ($params['formato'] == "image/jpeg") {
            $definitions['image']['type'] = 'jpg'; // can be png, jpg, gif  -> if not set default is png
        } else {
            $definitions['image']['type'] = 'png'; // can be png, jpg, gif  -> if not set default is png
        }


        //$definitions['image']['filename'] = "file.ext"; // can be set if you prefer save image as a file
        $definitions['image']['jpg_quality'] = 100; // quality value for jpeg imagens -> i
        //Zend_Debug::dump($definitions);exit;f not set default is 100
        $imageGantt = new App_Gantt_Gantt($definitions);
        $contents = $imageGantt;
        //ob_start();
        //$contents = ob_get_contents();
        //ob_end_clean();
        return $contents;
    }

    /**
     *
     * @param array $params
     * @param Projeto_Model_Mapper_Statusreport
     * @return  array
     */
    public function getObjetoGraficoGantt_bkp($params)
    {
        /*******************************************************************************
         * gantt php class example and configuration file
         * this example shows a full example with all resources
         * and dependencies
         * version 0.1
         * Copyright (C) 2005 Alexandre Miguel de Andrade Souza
         *
         * This library is free software; you can redistribute it and/or
         * modify it under the terms of the GNU General Public
         * License as published by the Free Software Foundation; either
         * version 2 of the License.
         * Please see the accompanying file COPYING for licensing details!
         *
         * If you need a commercial license of this class to your project, please contact
         * alexandremasbr@gmail.com
         *******************************************************************************/
        /**/
        $serviceGerencia = new Projeto_Service_Gerencia();
        $projeto = $serviceGerencia->retornaProjetoPorId($params);
        $atividadePredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        $resultAtividades = $this->_mapper->retornaAtividadeGantt($params);
        $itemNivel1 = '';
        $itemNivel2 = '';
        $itemNivel3 = '';
        $data = array();
        //Zend_Debug::dump($projeto->nomprojeto);exit;
        /**/


        //THIS START STANDARD DEFINITIONS TO CLASS, YOU DONT NEED TO CHANGE THIS SETTINGS, ONLY IF YOU WANT
        //generic  definitions to graphic, you dont need to change this. Only if you want
        $definitions['title_y'] = 10; // absolute vertical position in pixels -> title string
        $definitions['planned']['y'] = 6;  // relative vertical position in pixels -> planned/baseline
        $definitions['planned']['height'] = 8;  // height in pixels -> planned/baseline
        $definitions['planned_adjusted']['y'] = 25; // relative vertical position in pixels -> adjusted planning
        $definitions['planned_adjusted']['height'] = 8;  // height in pixels -> adjusted planning
        $definitions['real']['y'] = 26; // relative vertical position in pixels -> real/realized time
        $definitions['real']['height'] = 5;  // height in pixels -> real/realized time
        $definitions['progress']['y'] = 11; // relative vertical position in pixels -> progress
        $definitions['progress']['height'] = 2;  // height in pixels -> progress
        $definitions['img_bg_color'] = array(180, 180, 180); //color of background
        $definitions['title_color'] = array(0, 0, 0); //color of title
        $definitions['text']['color'] = array(0, 0, 0); //color of title
        $definitions['title_bg_color'] = array(255, 255, 255); //color of background of title
        $definitions['milestone']['title_bg_color'] = array(180, 180, 180); //color of background of title of milestone
        $definitions['today']['color'] = array(0, 0, 0); //color of today line
        $definitions['status_report']['color'] = array(255, 50, 0); //color of last status report line
        $definitions['real']['hachured_color'] = array(
            204,
            0,
            0
        );// color of hachured of real. to not have hachured, set to same color of real
        $definitions['workday_color'] = array(255, 255, 255); //white -> default color of the grid to workdays
        $definitions['grid_color'] = array(204, 204, 204); //default color of weekend days in the grid
        $definitions['groups']['color'] = array(77, 170, 77);// set color of groups
        $definitions['groups']['bg_color'] = array(180, 180, 180);// set color of background to groups title
        $definitions['tasks']['color'] = array(77, 0, 0);// set color of tasks
        $definitions['tasks']['bg_color'] = array(180, 180, 180);// set color of background to tasks title
        $definitions['planned']['color'] = array(122, 119, 119);// set color of initial planning/baseline
        $definitions['planned_adjusted']['color'] = array(0, 0, 204); // set color of adjusted planning
        $definitions['real']['color'] = array(255, 255, 255);//set color of work done
        $definitions['progress']['color'] = array(101, 192, 219); // set color of progress/percentage completed
        $definitions['milestones']['color'] = array(219, 94, 89); //set the color to milestone icon
        //$definitions['img_width']                              = 900; //set the img_width
        //$definitions['img_height']                             = 400; //set the img_height

        //if you want a ttf font set this values
        // just donwload a ttf font and set the path
        // find ttf fonts at http://www.webpagepublicity.com/free-fonts.html -> more than 6500 free fonts
        //$definitions['text']['ttfont']['file']                 = './Arial.ttf'; // set path and filename of ttf font -> coment to use gd fonts
        //$definitions['text']['ttfont']['size']                 = '11'; // used only with ttf
        //define font colors
        //$definitions['title']['ttfont']['file']                = './ActionIs.ttf'; // set path and filename of ttf font -> coment to use gd fonts
        //$definitions['title']['ttfont']['size']                = '11'; // used only with ttf


        // these are default value if not set a ttf font
        $definitions['text_font'] = 3; //define the font to text -> 1 to 4 (gd fonts)
        $definitions['title_font'] = 3;  //define the font to title -> 1 to 4 (gd fonts)

        //define font colors
        $definitions["group"]['text_color'] = array(0, 0, 0);
        $definitions["tasks"]['text_color'] = array(0, 0, 0);
        $definitions["legend"]['text_color'] = array(0, 0, 0);
        $definitions["milestone"]['text_color'] = array(0, 0, 0);
        $definitions["phase"]['text_color'] = array(0, 0, 0);

        // set to 1 to a continuous line
        $definitions['status_report']['pixels'] = 15; //set the number of pixels to line interval
        $definitions['today']['pixels'] = 10; //set the number of pixels to line interval

        // set colors to dependency lines -> both  dependency planned(baseline) and dependency (adjusted planning)
        $definitions['dependency_color'][END_TO_START] = array(0, 0, 0);//black
        $definitions['dependency_color'][START_TO_START] = array(0, 0, 0);//black
        $definitions['dependency_color'][END_TO_END] = array(0, 0, 0);//black
        $definitions['dependency_color'][START_TO_END] = array(0, 0, 0);//black

        //set the alpha (tranparency) to colors of bars/icons/lines
        $definitions['planned']['alpha'] = 40; //transparency -> 0-100
        $definitions['planned_adjusted']['alpha'] = 40; //transparency -> 0-100
        $definitions['real']['alpha'] = 0; //transparency -> 0-100
        $definitions['progress']['alpha'] = 0; //transparency -> 0-100
        $definitions['groups']['alpha'] = 40; //transparency -> 0-100
        $definitions['tasks']['alpha'] = 40; //transparency -> 0-100
        $definitions['today']['alpha'] = 10; //transparency -> 0-100
        $definitions['status_report']['alpha'] = 10; //transparency -> 0-100
        $definitions['dependency']['alpha'] = 80; //transparency -> 0-100
        $definitions['milestones']['alpha'] = 10; //transparency -> 0-100

        // set the legends strings
        $definitions['planned']['legend'] = 'PLANEJAMENTO INICIAL';
        $definitions['planned_adjusted']['legend'] = 'PLANEJAMENTO AJUSTADO';
        $definitions['real']['legend'] = 'REALIZADO';
        $definitions['progress']['legend'] = 'PROGRESSO';
        $definitions['milestone']['legend'] = 'MARCO';
        $definitions['today']['legend'] = 'HOJE';
        $definitions['status_report']['legend'] = 'ULTIMO STATUS REPORT';

        //set the size of each day in the grid for each scale
        $definitions['limit']['cell']['m'] = '4'; // size of cells (each day)
        $definitions['limit']['cell']['w'] = '8'; // size of cells (each day)
        $definitions['limit']['cell']['d'] = '20';// size of cells (each day)

        //set the initial positions of the grid (x,y)
        $definitions['grid']['x'] = 180; // initial position of the grix (x)
        $definitions['grid']['y'] = 40; // initial position of the grix (y)

        //set the height of each row of phases/phases -> groups and milestone rows will have half of this height
        $definitions['row']['height'] = 40; // height of each row

        $definitions['legend']['y'] = 85; // initial position of legent (height of image - y)
        $definitions['legend']['x'] = 180; // distance between two cols of the legend
        $definitions['legend']['y_'] = 25; //distance between the image bottom and legend botton
        $definitions['legend']['ydiff'] = 25; //diference between lines of legend

        //other settings
        //  if you want set progress bar on planned bar (the x point), if not set, default is on planned_adjusted bar -> you need to adjust $definitions['progress']['y'] to progress y stay over planned bar or whatever you want;
        $definitions['progress']['bar_type'] = 'planned';
        $definitions["not_show_tasks"] = false; // if set to true not show groups, but still need to set phases to a group
        $definitions["not_show_groups"] = false; // if set to true not show groups, but still need to set phases to a group
        ///
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////
        // THIS IS THE BEGINNING OF YOUR CHART SETTINGS
        //global definitions to graphic
        // change to you project data/needs
        //$definitions['title_string']                           = "9999"; //project title
        $definitions['title_string'] = utf8_decode($projeto->nomprojeto); //project title
        //$definitions['locale']                                 = "en";//change to language you need -> en = english, pt_BR = Brazilian Portuguese etc
        $definitions['locale'] = "pt_BR";//change to language you need -> en = english, pt_BR = Brazilian Portuguese etc
        //define the scale of the chart
        $definitions['limit']['detail'] = 'w'; //w week, m month , d day

        //define data information about the graphic. this limits will be adjusted in month and week scales to fit to
        //start of month of start date and end of month in end date, when the scale is month
        // and to start of week of start date and end of week in the end date, when the scale is week
        $definitions['limit']['start'] = mktime(0, 0, 0, 10, 1, 2015); //these settings will define the size of
        $definitions['limit']['end'] = mktime(23, 59, 59, 4, 30, 2016); //graphic and time limits

        // define the data to draw a line as "today"
        $definitions['today']['data'] = mktime(0, 0, 0, 1, 16, 2016); //time();//draw a line in this date
        //$definitions['today']['data']= mktime(0,0,0,1,19,2005); //time();//draw a line in this date

        // define the data to draw a line as "last status report"
        $definitions['status_report']['data'] = mktime(0, 0, 0, 1, 3, 2016); //time();//draw a line in this date
        //
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        // use loops to define these variables with database data

        // you need to set tasks to graphic be created
        $definitions['groups']['group'][6]['name'] = "Grupo Um";
        $definitions['groups']['group'][6]['start'] = mktime(0, 0, 0, 12, 2, 2015);
        $definitions['groups']['group'][6]['end'] = mktime(0, 0, 0, 2, 27, 2016);

        // increase the number to add another group
        $definitions['groups']['group'][7]['name'] = "Grupo Dois";
        $definitions['groups']['group'][7]['start'] = mktime(0, 0, 0, 10, 28, 2015);
        $definitions['groups']['group'][7]['end'] = mktime(0, 0, 0, 1, 27, 2016);

        // you need to set a group to every phase(=phase) to show it rigth
        // 'group'][0] -> 0 is the number of the group to associate task
        // ['phase'][0] = 0; 0 and 0 > the same value -> is the number of the tasks to associate to group
        $definitions['groups']['group'][6]['task'][0] = 8;
        $definitions['groups']['group'][7]['task'][0] = 9;

        // you need to set tasks to graphic be created
        $definitions['tasks']['task'][8]['name'] = "Entrega Um";
        $definitions['tasks']['task'][8]['start'] = mktime(0, 0, 0, 12, 2, 2015);
        $definitions['tasks']['task'][8]['end'] = mktime(0, 0, 0, 2, 27, 2016);

        // increase the number to add another task
        $definitions['tasks']['task'][9]['name'] = "Entrega Dois";
        $definitions['tasks']['task'][9]['start'] = mktime(0, 0, 0, 10, 28, 2015);
        $definitions['tasks']['task'][9]['end'] = mktime(0, 0, 0, 1, 27, 2016);

        // you need to set a task to every phase(=phase) to show it rigth
        // 'task'][0] -> 0 is the number of the task to associate phases
        // ['phase'][0] = 0; 0 and 0 > the same value -> is the number of the phase to associate to task
        $definitions['tasks']['task'][8]['phase'][0] = 10;
        $definitions['tasks']['task'][8]['phase'][1] = 12;
        $definitions['tasks']['task'][9]['phase'][0] = 11;

        //you have to set planned phase name even when show only planned adjusted
        $definitions['planned']['phase'][10]['name'] = 'tarefa a';
        //define the start and end of each phase. Set only what you want/need to show. Not defined values will not draws bars
        $definitions['planned']['phase'][10]['start'] = mktime(0, 0, 0, 12, 2, 2015);
        $definitions['planned']['phase'][10]['end'] = mktime(0, 0, 0, 1, 14, 2016);
        $definitions['planned_adjusted']['phase'][10]['start'] = mktime(0, 0, 0, 12, 2, 2015);
        $definitions['planned_adjusted']['phase'][10]['end'] = mktime(0, 0, 0, 1, 18, 2016);
        $definitions['real']['phase'][10]['start'] = mktime(0, 0, 0, 12, 28, 2015);
        $definitions['real']['phase'][10]['end'] = mktime(0, 0, 0, 1, 14, 2016);
        //define a percentage/progress to phase. Set only if you want.
        $definitions['progress']['phase'][10]['progress'] = 70;

        //Example of a second phase.
        $definitions['planned']['phase'][11]['name'] = 'tarefa xyz';
        $definitions['planned']['phase'][11]['start'] = mktime(0, 0, 0, 10, 14, 2015);
        $definitions['planned']['phase'][11]['end'] = mktime(0, 0, 0, 2, 23, 2016);
        $definitions['planned_adjusted']['phase'][11]['start'] = mktime(0, 0, 0, 10, 12, 2015);
        $definitions['planned_adjusted']['phase'][11]['end'] = mktime(0, 0, 0, 1, 1, 2016);
        $definitions['real']['phase'][11]['start'] = mktime(0, 0, 0, 10, 14, 2015);
        $definitions['real']['phase'][11]['end'] = mktime(0, 0, 0, 12, 23, 2015);
        $definitions['progress']['phase'][11]['progress'] = 30;

        //Example of a second phase.
        $definitions['planned']['phase'][12]['name'] = 'tarefa aaa';
        $definitions['planned']['phase'][12]['start'] = mktime(0, 0, 0, 1, 14, 2016);
        $definitions['planned']['phase'][12]['end'] = mktime(0, 0, 0, 2, 23, 2016);
        $definitions['planned_adjusted']['phase'][12]['start'] = mktime(0, 0, 0, 10, 12, 2015);
        $definitions['planned_adjusted']['phase'][12]['end'] = mktime(0, 0, 0, 1, 1, 2016);
        //$definitions['real']['phase'][1]['start']             = mktime(0,0,0,1,23,2016);
        //$definitions['real']['phase'][1]['end']               = mktime(0,0,0,2,27,2016);
        $definitions['progress']['phase'][12]['progress'] = 30;


        //////////////////////////////////////////////////////////////////////////
        //dependencies to planned array -> type can be END_TO_START, START_TO_START, END_TO_END and START_TO_END

        $definitions['dependency_planned'][0]['type'] = END_TO_START;
        $definitions['dependency_planned'][0]['phase_from'] = 10;
        $definitions['dependency_planned'][0]['phase_to'] = 11;

        //Examples of another types of dependencies
        /*
        $definitions['dependency_planned'][1]['type']= START_TO_START;
        $definitions['dependency_planned'][1]['phase_from']=0;
        $definitions['dependency_planned'][1]['phase_to']=1;

        $definitions['dependency_planned'][2]['type']= END_TO_END;
        $definitions['dependency_planned'][2]['phase_from']=0;
        $definitions['dependency_planned'][2]['phase_to']=1;

        $definitions['dependency_planned'][3]['type']= START_TO_END;
        $definitions['dependency_planned'][3]['phase_from']=0;
        $definitions['dependency_planned'][3]['phase_to']=1;
        */

        //////////////////////////////////////////////////////////////////////////
        //dependencies to adjusted planned array -> type can be END_TO_START, START_TO_START, END_TO_END and START_TO_END

        /*
        $definitions['dependency'][0]['type']= END_TO_START;
        $definitions['dependency'][0]['phase_from']=0;
        $definitions['dependency'][0]['phase_to']=1;
         // another examples of dependencies
        /**/
        $definitions['dependency'][1]['type'] = START_TO_END;
        $definitions['dependency'][1]['phase_from'] = 10;
        $definitions['dependency'][1]['phase_to'] = 12;
        /**/
        $definitions['dependency'][2]['type'] = START_TO_START;
        $definitions['dependency'][2]['phase_from'] = 12;
        $definitions['dependency'][2]['phase_to'] = 11;
        /*
        $definitions['dependency'][3]['type']           = START_TO_END;
        $definitions['dependency'][3]['phase_from']     = 0;
        $definitions['dependency'][3]['phase_to']       = 1;
        */

        ///////////////////////////////////////////////////////////////////////////
        // milestones are products or objectives of project. Set if you want. In this case, you need to set
        // a data, a title and a task to each milestone
        $definitions['milestones']['milestone'][0]['data'] = mktime(0, 0, 0, 11, 15, 2015);
        $definitions['milestones']['milestone'][0]['title'] = 'MARCO UM';
        //define a group to milestone
        //$definitions['groups']['group'][0]['milestone'][0]  = 0; //need to set a group to show

        //define a task to milestone
        $definitions['tasks']['task'][8]['milestone'][0] = 0; //need to set a task to show

        ////
        /////////////////////////////////////////////////////////////////

        /////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////
        // THE END -> generate the graphic
        // TO SET THE KIND OF GRAFIC GENERATED

        $definitions['image']['type'] = 'png'; // can be png, jpg, gif  -> if not set default is png
        //$definitions['image']['type']= 'jpg'; // can be png, jpg, gif  -> if not set default is png
        //$definitions['image']['type']= 'gif'; // can be png, jpg, gif  -> if not set default is png
        //$definitions['image']['filename'] = "file.ext"'; // can be set if you prefer save image as a file
        $definitions['image']['jpg_quality'] = 100; // quality value for jpeg imagens -> if not set default is 100
        $imageGantt = new App_Gantt_Gantt($definitions);
        $contents = $imageGantt;
        //ob_start();
        //$contents = ob_get_contents();
        //ob_end_clean();
        return $contents;
    }

    /**
     * @return Projeto_Form_GerarGantt
     */
    public function getFormGerarGantt($params)
    {
        $form = $this->_getForm('Projeto_Form_GerarGantt');
        $form->populate($params);
        return $form;
    }
}
