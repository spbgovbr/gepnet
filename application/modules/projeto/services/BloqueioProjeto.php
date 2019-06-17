<?php

class Projeto_Service_BloqueioProjeto extends App_Service_ServiceAbstract
{

    protected $_form;
    protected $auth;

    /**
     *
     * @var Projeto_Model_Mapper_BloqueioProjeto
     */
    protected $_mapper;
    protected $_mapperAtividadeCronograma;
    protected $_dependencies = array(
        'db'
    );

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Bloqueioprojeto();
    }

    /**
     * @return Projeto_Form_Desbloqueio
     */
    public function getFormDesbloqueio()
    {
        return $this->_getForm('Projeto_Form_Desbloqueio');
    }

    public function rotinaBloqueioProjetos()
    {
        set_time_limit(0);
        $rustart = getrusage();
        $horaInicioExecucao = date('d-m-Y H:i:s');
        $hoje = new Zend_Date();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        $projetos = $serviceGerencia->retornaTodosOsProjetosEmAndamento();

        $arrayEmailsBloqueio = array();
        print "<PRE>";
//        Zend_Debug::dump($projetos); exit;
        foreach ($projetos as $p) {
            $datUltimoStatusReport = null;
            $diasPrazo = (3 * $p->numperiodicidadeatualizacao); //
            $data = $p->datcadastro;

            if ($p->ultimoStatusReport->idstatusreport) {
                $data = $p->ultimoStatusReport->datacompanhamento;
            }

            $o = $this->calcDiferencaDias($hoje, $data);
//                $dataPrazo = $this->setPrazo($data,$diasPrazo);

            if ($diasPrazo < $o->diferencaDias) {
                //Altera status do projeto para BLOQUEADO
                $this->bloquearProjeto($p);
                $this->setLog($p, $horaInicioExecucao, $rustart);
                $arrayEmailsBloqueio[] = $this->retornaObjetoEmail($p);
            }
            /*
             *  Status do Projeto
                1 - Proposta;
                2 - Em Andamento
                3 - Concluído;
                4 - Paralisado;
                5 - Cancelado;
                6 - Bloqueado;
            */
            /*if($p->idprojeto == 317){
                print '<BR>';
                print 'aqui';
                Zend_Debug::dump($data->get('d/m/Y'));
                Zend_Debug::dump($p->idprojeto);
                Zend_Debug::dump($o->diferencaDias);
                Zend_Debug::dump($diasPrazo);
                print '<BR>';
                exit;
            }*/
        }

        $envio = 0;
        $falha = 0;
        foreach ($arrayEmailsBloqueio as $a) {
            if ($this->enviaEmailBloqueio($a)) {
                $envio++;
            } else {
                $falha++;
            }
        }

//        print_r($arrayEmailsBloqueio);
        echo 'Emails Enviados: ' . $envio;
        echo '<br>';
        echo 'Falha de Envio: ' . $falha;
        exit;
    }

    private function rutime($ru, $rus, $index)
    {
        return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
            - ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
    }

    private function setLog($projeto, $horaInicioExecucao, $rustart)
    {
        $log = "\n" . "\n" . "\n" . "\n" . "\n";
        $log .= '############################################################################################' . "\n";
        $log .= '################### EXECUCAO ROTINA BLOQUEIO ' . date('d/m/Y H:i:s') . ' ###########################' . "\n";
        $log .= '############################################################################################' . "\n";
        $log .= "\n";
        $log .= 'Bloqueado Projeto: ' . $projeto->idprojeto . ' - ' . $projeto->nomprojeto . ' as ' . date('d-m-Y H:i:s') . ' via Rotina no servidor' . "\r\n";

        $horaFimExecucao = date('d-m-Y H:i:s');
        $ru = getrusage();

        $log .= "\n" . "\n";
        $log .= 'A execucao do processo usou ' . $this->rutime($ru, $rustart,
                'utime') . ' ms nas operacoes computacionais.' . "\n";
        $log .= 'Gastou ' . $this->rutime($ru, $rustart, 'stime') . ' ms em chamadas de sistema' . "\n";
        $log .= 'Processo com inicio as: ' . $horaInicioExecucao . ' e termino as: ' . $horaFimExecucao;

        $config = Zend_Registry::get('config');
        $dir = $config->resources->cachemanager->default->backend->options->logs_dir;
        $filename = 'bloqueio_projeto_' . date('Y-m-d') . '.txt';
        $path = $dir . $filename;
        $handle = fopen($path, "a+");
        if ($handle) {
            fwrite($handle, $log);
        }
    }

    private function calcDiferencaDias($hoje, $data)
    {
        $o = new stdClass();
        $datInicial = new Zend_Date($data->get('Y-m-d'), 'YYYY-MM-dd');
        $hoje = new Zend_Date($hoje->get('Y-m-d'), 'YYYY-MM-dd');
        $datInicial->add('1', Zend_Date::DAY);
//        $o->prazo = $this->setPrazo($datInicial,$dias);
        $o->diferencaDias = $this->getDiferencaDias($hoje, $datInicial);
        return $o;
    }

    private function getDiferencaDias($hoje, $data)
    {
//        $data = new Zend_Date($data,'YYYY-MM-dd');
//        print $data->get('d/m/Y'); print '<br>';
//        print $hoje->get('d/m/Y'); print '<br>';
        $diff = $hoje->sub($data)->toValue();
        return ceil($diff / 60 / 60 / 24) + 1;
//        return floor($diff/86400);
    }

    public function enviaEmailBloqueio($params)
    {
        set_time_limit(0);
        $service = App_Service_ServiceAbstract::getService('Default_Service_Email');
        $textoEmailBloqueio = 'O sistema GEPNet informa que o projeto ';
        $textoEmailBloqueio .= $params->projeto;
        $textoEmailBloqueio .= ' foi bloqueado a partir desta data,';
        $textoEmailBloqueio .= ' em virtude de estar com mais de três atualizações periódicas';
        $textoEmailBloqueio .= ' (Relatórios de Situação) em atraso.';

        try {
            $assunto = 'GEPNet Bloqueio de Projeto';
//            $from       = "FROM: GEPNet - Gestor de Escritórios de Projetos <gepnet@noreply.com>";
            $from = "From: GEPNet - Gestor de Escritórios de Projetos <gepnet@no-reply.com>";
            //@TODO remover email de teste, habilitar emails dos participantes do projeto
            $to = 'rbolina@stefanini.com';
//            $to         = "{$params['emailPatrocinador']},{$params['emailGerenteProjeto']},{$params['emailGerenteAdjunto']},{$params['emailEscritorioProjetos']}";
//            $service->enviaEmail(array('to'=>$to,'subject' => $assunto, 'body' => $textoEmailBloqueio, 'from' => $from));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function enviaEmailDesbloqueio($params)
    {
        set_time_limit(0);
        $service = App_Service_ServiceAbstract::getService('Default_Service_Email');
        $textoEmailBloqueio = 'O sistema GEPNet informa que o projeto ';
        $textoEmailBloqueio .= $params->projeto;
        $textoEmailBloqueio .= ' foi desbloqueado a partir desta data,';
        $textoEmailBloqueio .= ' pelo motivo de:';
        $textoEmailBloqueio .= $params['desjustificativa'];

        try {
            $assunto = 'GEPNet Desbloqueio de Projeto';
//            $from       = "FROM: GEPNet - Gestor de Escritórios de Projetos <gepnet@noreply.com>";
            $from = "From: GEPNet - Gestor de Escritórios de Projetos <gepnet@no-reply.com>";
            //@TODO remover email de teste, habilitar emails dos participantes do projeto
            $to = 'rbolina@stefanini.com';
//            $to         = "{$params['emailPatrocinador']},{$params['emailGerenteProjeto']},{$params['emailGerenteAdjunto']},{$params['emailEscritorioProjetos']}";
            $service->enviaEmail(array(
                'to' => $to,
                'subject' => $assunto,
                'body' => $textoEmailBloqueio,
                'from' => $from
            ));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function bloquearProjeto($params)
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $status = $serviceGerencia->alterarStatusProjeto(array(
            'idprojeto' => $params->idprojeto,
            'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_BLOQUEADO
        ));
        $model = new Projeto_Model_Bloqueioprojeto(array('idprojeto' => $params->idprojeto));
        if ($status) {
            $this->registrarBloqueioProjeto($model);
        }
    }

    public function desbloquearProjeto($params)
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $status = $serviceGerencia->alterarStatusProjeto(array(
            'idprojeto' => $params['idprojeto'],
            'domstatusprojeto' => Projeto_Model_Gerencia::STATUS_ANDAMENTO
        ));
//        $status = false; //para teste
        if ($status) {
            $model = new Projeto_Model_Bloqueioprojeto(array(
                'idprojeto' => $params['idprojeto'],
                'idpessoa' => $this->auth->idpessoa,
                'desjustificativa' => $params['desjustificativa']
            ));
            $this->registrarDesbloqueioprojeto($model);
            $this->enviaEmailDesbloqueio($params);

        }
    }

    public function registrarBloqueioProjeto($params)
    {
        return $this->_mapper->registrarBloqueioProjeto($params);
    }

    public function registrarDesbloqueioProjeto($params)
    {
        return $this->_mapper->registrarDesbloqueioProjeto($params);
    }

    public function retornaObjetoEmail($params)
    {
        $objDadosEmail = new stdClass();
        $objDadosEmail->projeto = $params->nomprojeto . '/' . $params->nomcodigo . '/' . $params->nomescritorio;
        $objDadosEmail->emailPatrocinador = $params->emailpatrocinador;
        $objDadosEmail->emailGerenteProjeto = $params->emailgerenteprojeto;
        $objDadosEmail->emailGerenteAdjunto = $params->emailgerenteadjunto;
        $objDadosEmail->emailEscritorioProjetos = '';

//        new Zend_Validate_EmailAddress()

        return $objDadosEmail;
    }

    /**
     * Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
     *
     * @param array $params - ponteiro para o array de parametros
     * @return void
     */
    public function permissaoPerfil(&$params)
    {
        $params['desbloqueio'] = false;
        //Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
        $perfisPermissao = array(
            Default_Model_Perfil::ESCRITORIO_DE_PROJETOSEGPE_CIGE,
            Default_Model_Perfil::ADMINISTRADOR_SETORIAL,
            Default_Model_Perfil::ADMINISTRADOR_GEPNET,
        );

//        Zend_Debug::dump($this->auth); exit;

        if (in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao)) {
            $params['desbloqueio'] = true;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

?>

