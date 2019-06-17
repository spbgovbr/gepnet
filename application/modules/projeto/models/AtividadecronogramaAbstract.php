<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
abstract class Projeto_Model_AtividadecronogramaAbstract
    extends App_Model_ModelAbstract
    implements Projeto_Model_AtividadecronogramaInterface
{

    const TIPO_ATIVIDADE_GRUPO = 1;
    const TIPO_ATIVIDADE_ENTREGA = 2;
    const TIPO_ATIVIDADE_COMUM = 3;
    const TIPO_ATIVIDADE_MARCO = 4;

    const TIPO_ATIVIDADE_1 = 'Grupo';
    const TIPO_ATIVIDADE_2 = 'Entrega';
    const TIPO_ATIVIDADE_3 = 'Comum';
    const TIPO_ATIVIDADE_4 = 'Marco';

    public $idatividadecronograma = null;
    public $numseq = null;
    public $idprojeto = null;
    public $nomatividadecronograma = null;
    public $domtipoatividade = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idgrupo = null;
    public $flacancelada = null;
    public $flashowhide = null;

    /**
     * Retorna a descricao e o prazo em dias
     * @param integer $numcriteriofarol
     * @return \stdClass
     */
    public function retornaPrazo($numcriteriofarol)
    {
        $dias = 0;
        $sinal = "";

        if (!empty($this->datfimbaseline) && !empty($this->datfim)) {

            if (Zend_Date::isDate($this->datfimbaseline) && Zend_Date::isDate($this->datfim)) {

                if ($this->datfim == $this->datfimbaseline) {
                    $dias = 0;
                } else {
                    $service = new Projeto_Service_AtividadeCronograma();
                    $dados['datainicio'] = $this->datfim->format('d/m/Y');
                    $dados['datafim'] = $this->datfimbaseline->format('d/m/Y');
                    $dias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                    /**********************************************************/
                    /* retira um dia do cálculo para atender a regra definida */
                    $dias = $dias * (-1);
                    $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
                    /**********************************************************/
                    //$dias = $this->datfim->diff($this->datfimbaseline)->days;
                    $sinal = $this->retornaDescricaoFarolAtraso($this->datfimbaseline->format('d/m/Y'),
                        $this->datfim->format('d/m/Y'), $numcriteriofarol);
                }
            }
        }

        $retorno = new stdClass();
        $retorno->descricao = $sinal;
        $retorno->dias = $dias;
        return $retorno;
    }

    public function retornaDescricaoFarolAtraso($dataPlanejada, $dtRealizada, $numCriterioFarol)
    {
        $sinal = "";
        $dataFimPlanejada = new Zend_Date($dataPlanejada, 'd/m/Y');
        $dataFimRealizada = new Zend_Date($dtRealizada, 'd/m/Y');
        if ((Zend_Date::isDate($dataFimPlanejada)) &&
            (Zend_Date::isDate($dataFimRealizada))
        ) {
            $numEmDias = 0;
            $dados['datainicio'] = $dataFimRealizada->toString('d/m/Y');
            $dados['datafim'] = $dataFimPlanejada->toString('d/m/Y');
            $service = new Projeto_Service_AtividadeCronograma();
            /* retira um dia do cálculo para atender a regra definida */
            if (($dataFimRealizada->equals($dataFimPlanejada)) == false) {
                $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                $numEmDias = $numEmDias * (-1);
                $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1);
            }

            if ($numEmDias < 0 || $numEmDias == 0) {
                $sinal = "success";
            } else {
                if ($numEmDias > 0 && $numEmDias <= $numCriterioFarol) {
                    $sinal = "warning";
                } else {
                    if ($numEmDias > $numCriterioFarol) {
                        $sinal = "important";
                    }
                }
            }
        }
        return $sinal;
    }

    private function isFinalSemana($dataRealizada, $dataPlanejada)
    {
        $dtPlanejada = new Zend_Date($dataPlanejada, 'd/m/Y');
        $dtPlanejada = $dtPlanejada->addDay(1);
        $dataFimRealizada = new Zend_Date($dataRealizada, 'd/m/Y');

        if ($dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'sáb'
            || $dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'dom') {
            return true;
        }
        return false;
    }


    public function retornaDescricaoConclusao()
    {
        $hoje = new DateTime('now');
        $classe = 'item-em-dia';

        if ($this->numpercentualconcluido == 100) {
            $classe = 'item-concluido';
        } else {
            if ($this->datfim < $hoje) {
                $classe = 'item-atrasado';
            }
        }

        if ((($this->domtipoatividade == self::TIPO_ATIVIDADE_COMUM)
                || ($this->domtipoatividade == self::TIPO_ATIVIDADE_MARCO)) && $this->flacancelada == 'S') {
            // alterado em 03-11-2017 - Manutenção Corretiva #13718 - Domingos
            $classe = 'item-cancelado';
        }

        return $classe;
    }
}

