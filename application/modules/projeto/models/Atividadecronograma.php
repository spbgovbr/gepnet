<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Atividadecronograma extends Projeto_Model_AtividadecronogramaAbstract
{
    public $idatividadecronograma = null;
    public $idprojeto = null;
    public $idgrupo = null;
    public $numseq = null;
    public $numpercentualconcluido = 0;
    public $numpercentualprevisto = 0;
    public $numdiasrealizados = null;
    public $numdiasrealizadosreal = null;
    public $numdiasbaseline = '0';
    public $numdiascompletos = '0';
    public $numdiasreal = null;
    public $numdiasbase = '0';
    public $nomatividadecronograma = null;
    public $datiniciobaseline = null;
    public $datfimbaseline = null;
    public $datinicio = null;
    public $datfim = null;
    public $idparteinteressada = '0'; # Responsável pela aceitação
    public $idresponsavel = '0'; # Responsável pela entrega
    public $domtipoatividade = null;
    public $flacancelada = 'N';
    public $desobs = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idmarcoanterior = null;
    public $numdias = null;
    public $datatividadeconcluida = null;
    public $vlratividadebaseline = '0';
    public $vlratividade = '0';
    public $numfolga = '0';
    public $descriterioaceitacao = null;
    public $flaaquisicao = 'N';
    public $flainformatica = 'N';
    public $flaordenacao = 'S';
    public $flashowhide = 'S';
    public $idelementodespesa = null;
    public $nomparteinteressadaentrega = null;
    public $grupo = null;
    /**
     * atributtos auxiliares;
     */
    public $nomparteinteressada = null;
    public $emailparteinteressada = null;
    public $hoje = null;
    public $predecessoras = array();
    public $entregas = array();
    public $marcos = array();
    public $responsavelAceitacao = array();
    /**
     *
     * @var Projeto_Model_Parteinteressada
     */
    public $parteinteressada = null;
    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;
    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;

    protected $metaEmDias = 0;
    protected $tendenciaEmDias = 0;

    protected $diasCalculados = false;

    protected $diasBaseLine = null;
    protected $diasReal = null;
    protected $numcriteriofarol = null;
    protected $prazo = null;
    protected $descricaoprazo = null;

    /**
     *
     * @var App_TimeInterval
     */
    // public $timeInterval = null;

    public function init()
    {
        //$this->timeInterval = new App_TimeInterval();
        $this->hoje = new DateTime('now');
        $this->diasBaseLine = $this->numdiasbaseline;
        $this->diasReal = $this->numdiasrealizados;
        if ((null === $this->numdiascompletos) || (null === $this->numdiasbaseline)) {
            $this->numpercentualprevisto = 0;
        } else {
            if ($this->numdiasbaseline > 0) {
                $this->numpercentualprevisto = $this->retornaPercentualPrevisto();
            } else {
                $this->numpercentualprevisto = 0.00;
            }
        }
    }

    public function setdatiniciobaseline($data)
    {
        //$date = new DateTime($data);
        //$data = $date->format('d/m/Y H:i:sP');
        $this->datiniciobaseline = @DateTime::createFromFormat('d/m/Y', $data);

        return $this;
    }

    public function setDatfimbaseline($data)
    {
        $this->datfimbaseline = @DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setDatinicio($data)
    {
        $this->datinicio = @DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setNumdiasrealizados($numdiasrealizados)
    {
        $this->numdiasrealizados = $numdiasrealizados;
    }

    public function getNumdiasrealizados()
    {
        return $this->numdiasrealizados;
    }

    public function setDatfim($data)
    {
        $this->datfim = @DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setVlratividadebaseline($valor)
    {
        $valorfinal = str_replace(",", "", str_replace(".", "", $valor));
        $this->vlratividadebaseline = $valorfinal;
        return $this;
    }

    public function getVlratividadebaseline()
    {
        return $this->vlratividadebaseline;
    }

    public function getVlratividadebaselineFormatado()
    {
        $valor = mb_substr($this->vlratividadebaseline, 0, -2) . '.' . mb_substr($this->vlratividadebaseline, -2);
        return number_format($valor, 2, ',', '.');
    }

    public function setVlratividade($valor)
    {
        $valorfinal = str_replace(",", "", str_replace(".", "", $valor));
        $this->vlratividade = $valorfinal;
        return $this;
    }

    public function getVlratividade()
    {
        return $this->vlratividade;
    }

    public function getVlratividadeFormatado()
    {
        $valor = mb_substr($this->vlratividade, 0, -2) . '.' . mb_substr($this->vlratividade, -2);

        return number_format($valor, 2, ',', '.');
    }

    public function adicionarPredecessora(Projeto_Model_Atividadepredecessora $predecessora)
    {
        $this->predecessoras[] = $predecessora;
        return $this;
    }

    public function setParteInteressada(Projeto_Model_Parteinteressada $parteinteressada)
    {
        $this->parteinteressada = $parteinteressada;
        return $this;
    }

    public function setNomparteinteressada($nomparteinteressada)
    {
        $this->nomparteinteressada = $nomparteinteressada;
    }

    public function setResponsavelAceitacao(Projeto_Model_Parteinteressada $responsavelAceitacao)
    {
        $this->responsavelAceitacao = $responsavelAceitacao;
        return $this;
    }

    public function setDatatividadeconcluida($data)
    {
        $this->datatividadeconcluida = null;
        if (null != $data)
            $this->datatividadeconcluida = @DateTime::createFromFormat('d/m/Y', $data);

        return $this;
    }

    /**
     *
     * @return boolean | array
     */
    public function retornaPredecessoras()
    {
        if (count($this->predecessoras) > 0) {
            return $this->predecessoras;
        }
        return false;
    }

    /**
     *
     * @return boolean | array
     */
    public function limparPredecessoras()
    {
        $this->predecessoras = array();
    }

    /**
     *
     * @return \stdClass
     */
    public function retornarDiasEstimadosEReais()
    {
        if (false === $this->diasCalculados) {
            $this->diasCalculados = true;
            /**
             * Calculo para a atividade - checar
             */
            /*  ALTERADO PARA ATENDER AO REDMINE Manutenção Corretiva #19495 */

            if (empty($this->numdiasrealizados)) {

                if (
                    isset($this->datinicio) && (!empty($this->datinicio)) &&
                    isset($this->datfim) && (!empty($this->datfim))

                ) {
                    $dtIni = new Zend_Date($this->datinicio->format('d/m/Y'), 'd/m/Y');
                    $dtFim = new Zend_Date($this->datfim->format('d/m/Y'), 'd/m/Y');

                    if (Zend_Date::isDate($dtIni) && Zend_Date::isDate($dtFim)) {
                        if (($dtIni->equals($dtFim)) == false) {
                            $dados['datainicio'] = $dtIni->toString('d/m/Y');
                            $dados['datafim'] = $dtFim->toString('d/m/Y');
                            $servico = new Projeto_Service_AtividadeCronograma();
                            $diasAtividade = $servico->retornaQtdeDiasUteisEntreDatas($dados);
                        }
                    }
                } else {
                    $diasAtividade = 0;
                }
            } else {
                $diasAtividade = $this->numdiasrealizados;
            }

            if ($this->domtipoatividade == "4") {
                $diasAtividade = 0;
            }

            $diasAtividade = !empty($this->numdiasrealizados) ? $this->numdiasrealizados : 0;
            $diasExecutadosAtividade = (($diasAtividade * $this->numpercentualconcluido) / 100);
            $this->realTotalDias = $diasAtividade;
            $this->realTotalDiasExecutados = $diasExecutadosAtividade;

            /**
             * Calculo para a estimativa
             */
            if (null == $this->numdiasbaseline) {
                if ($this->domtipoatividade == "4") {
                    $diasBaseLine = 0;
                } else {
                    $diasBaseLine = 1;
                }
            } else {
                $diasBaseLine = $this->numdiasbaseline;
            }
            if ($this->domtipoatividade == "4") {
                $diasBaseLine = 0;
            }
            $this->estimativaTotalDias = $diasBaseLine;
            $diasExecutadosBaseLine = $this->numdiascompletos;
            $this->estimativaTotalDiasExecutados = $diasExecutadosBaseLine;
        }
        $this->numpercentualprevisto = $this->retornaPercentualPrevisto();
        $retorno = new stdClass();
        $retorno->estimativaTotalDias = $this->estimativaTotalDias;
        $retorno->estimativaTotalDiasExecutados = $this->estimativaTotalDiasExecutados;
        $retorno->realTotalDias = $this->realTotalDias;
        $retorno->realTotalDiasExecutados = $this->realTotalDiasExecutados;
        $retorno->percentualPrevisto = $this->numpercentualprevisto;

        return $retorno;
    }

    public function retornaDiasBaseLine()
    {
        if (empty($this->numdiasrealizados)) {

            if (
                isset($this->datiniciobaseline) && (!empty($this->datiniciobaseline)) &&
                isset($this->datfimbaseline) && (!empty($this->datfimbaseline))
            ) {
                $dtIni = new Zend_Date($this->datiniciobaseline->format('d/m/Y'), 'd/m/Y');
                $dtFim = new Zend_Date($this->datfimbaseline->format('d/m/Y'), 'd/m/Y');

                if (Zend_Date::isDate($dtIni) && Zend_Date::isDate($dtFim)) {
                    if (($dtIni->equals($dtFim)) == false) {
                        $dados['datainicio'] = $dtIni->toString('d/m/Y');
                        $dados['datafim'] = $dtFim->toString('d/m/Y');
                        $servico = new Projeto_Service_AtividadeCronograma();
                        $this->diasBaseLine = $servico->retornaQtdeDiasUteisEntreDatas($dados);
                    }
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            $this->diasBaseLine = $this->numdiasbaseline;
        }
        return $this->diasBaseLine;
    }

    public function retornaDiasCompletos()
    {
        if (null === $this->numdiascompletos) {
            return null;
        } else {
            $diasCompletos = $this->numdiascompletos;
        }
        return $diasCompletos;
    }

    public function retornaPercentualPrevisto()
    {
        if ((empty($this->numdiascompletos)) || (empty($this->numdiasbaseline))) {
            $this->numpercentualprevisto = 0;
        } else {
            if ($this->numdiasbaseline > 0) {
                $this->numpercentualprevisto = round(($this->numdiascompletos / $this->numdiasbaseline) * 100, 2);
                $this->numpercentualprevisto = number_format($this->numpercentualprevisto, 0);
            } else {
                $this->numpercentualprevisto = 0;
            }

        }
        return $this->numpercentualprevisto;
    }

    public function retornaDiasReal()
    {
        if (!empty($this->numdiasrealizados)) {
            $dtIni = new Zend_Date($this->datinicio->format('d/m/Y'), 'd/m/Y');
            $dtFim = new Zend_Date($this->datfim->format('d/m/Y'), 'd/m/Y');

            if (Zend_Date::isDate($dtIni) && Zend_Date::isDate($dtFim)) {
                if (($dtIni->equals($dtFim)) == false) {
                    $dados['datainicio'] = $dtIni->toString('d/m/Y');
                    $dados['datafim'] = $dtFim->toString('d/m/Y');
                    $servico = new Projeto_Service_AtividadeCronograma();
                    $this->diasReal = $servico->retornaQtdeDiasUteisEntreDatas($dados);
                }
            }
        } else {
            $this->diasReal = $this->numdiasrealizados;
        }
        return $this->diasReal;
    }

    public function toArray()
    {
        $retorno = get_object_vars($this);
        $retorno['datiniciobaseline'] = (!empty($this->datiniciobaseline)) ? $this->datiniciobaseline->format('d/m/Y') : null;
        $retorno['datfimbaseline'] = (!empty($this->datfimbaseline)) ? $this->datfimbaseline->format('d/m/Y') : null;
        $retorno['datinicio'] = (!empty($this->datinicio)) ? $this->datinicio->format('d/m/Y') : null;
        $retorno['datfim'] = (!empty($this->datfim)) ? $this->datfim->format('d/m/Y') : null;
        $retorno['diasbaseline'] = $this->retornaDiasBaseLine();
        $retorno['diasreal'] = $this->retornaDiasReal();
        $retorno['vlratividade'] = $this->getVlratividadeFormatado();
        $retorno['vlratividadebaseline'] = $this->getVlratividadebaselineFormatado();

        //if($this->numfolga >= 0){
        $retorno['datfim'] .= " E({$this->numfolga})";
        //}

        $retorno['realtotaldias'] = $this->realTotalDias;
        $retorno['idatividadecronograma'] = $this->idatividadecronograma;
        $retorno['idprojeto'] = $this->idprojeto;
        $retorno['idgrupo'] = $this->idgrupo;
        $retorno['realtotaldiasexecutados'] = $this->realTotalDiasExecutados;
        $retorno['numdiasbaseline'] = $this->numdiasbaseline;
        $retorno['numdiascompletos'] = $this->numdiascompletos;
        $retorno['numpercentualconcluido'] = (!empty($this->numpercentualconcluido)) ? number_format($this->numpercentualconcluido,
            0) : 0;
        $retorno['numpercentualprevisto'] = $this->retornaPercentualPrevisto();
        $retorno['numdiasrealizados'] = $this->numdiasrealizados;
        $retorno['numdiasrealizadosreal'] = $this->numdiasrealizadosreal;
        $retorno['retornaDescricaoConclusao'] = $this->retornaDescricaoConclusao();

        $retorno['numdiasreal'] = $this->numdiasreal;
        $retorno['numdiasbase'] = $this->numdiasbase;
        $retorno['grupo'] = $this->grupo;
        $retorno['nomparteinteressadaentrega'] = $this->nomparteinteressadaentrega;
        if (isset($this->parteinteressada->nomparteinteressada)) {
            $retorno['nomparteinteressada'] = $this->parteinteressada->nomparteinteressada;
        }
        if (isset($this->parteinteressada->desemail)) {
            $retorno['desemail'] = mb_substr($this->parteinteressada->desemail, 0,
                strpos($this->parteinteressada->desemail, "@"));
            $retorno['email'] = $this->parteinteressada->desemail;
        }
        if (isset($this->flaordenacao)) {
            $retorno['flaordenacao'] = $this->flaordenacao;
        }
        if (isset($this->flashowhide)) {
            $retorno['flashowhide'] = $this->flashowhide;
        }

        if (isset($this->predecessoras)) {
            $retorno['predecessoras'] = array();
            if (count($this->predecessoras) > 0) {
                foreach ($this->predecessoras as $p) {
                    //$retorno['predecessoras'][] = $p->toArray();
                    $retorno['predecessoras'][] = $p;
                }
            }
        }
        return $retorno;
    }
}

