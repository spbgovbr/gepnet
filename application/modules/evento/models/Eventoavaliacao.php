<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Evento_Model_Eventoavaliacao extends App_Model_ModelAbstract
{

    public $ideventoavaliacao = null;
    public $idevento = null;
    public $desdestaqueservidor = null;
    public $desobs = null;
    public $idavaliador = null;
    public $idavaliado = null;
    public $datcadastro = null;
    public $numpontualidade = null;
    public $numordens = null;
    public $numrespeitochefia = null;
    public $numrespeitocolega = null;
    public $numurbanidade = null;
    public $numequilibrio = null;
    public $numcomprometimento = null;
    public $numesforco = null;
    public $numtrabalhoequipe = null;
    public $numauxiliouequipe = null;
    public $numaceitousugestao = null;
    public $numconhecimentonorma = null;
    public $numalternativaproblema = null;
    public $numiniciativa = null;
    public $numtarefacomplexa = null;
    public $domtipoavaliacao = null;
    public $numnotaavaliador = null;
    public $nummedia = null;
    public $nummediafinal = null;
    public $numtotalavaliado = null;
    public $idtipoavaliacao = null;

    public $nomavaliador = null;
    public $nomavaliado = null;
    public $noavaliacao = null;
    public $nomevento = null;


    public function calculaMedias()
    {
        $somatorio = 0;
        $total = 0;

        $notas = array(
            'numpontualidade' => $this->numpontualidade,
            'numordens' => $this->numordens,
            'numrespeitochefia' => $this->numrespeitochefia,
            'numrespeitocolega' => $this->numrespeitocolega,
            'numurbanidade' => $this->numurbanidade,
            'numequilibrio' => $this->numequilibrio,
            'numcomprometimento' => $this->numcomprometimento,
            'numesforco' => $this->numesforco,
            'numtrabalhoequipe' => $this->numtrabalhoequipe,
            'numauxiliouequipe' => $this->numauxiliouequipe,
            'numaceitousugestao' => $this->numaceitousugestao,
            'numconhecimentonorma' => $this->numconhecimentonorma,
            'numalternativaproblema' => $this->numalternativaproblema,
            'numiniciativa' => $this->numiniciativa,
            'numtarefacomplexa' => $this->numtarefacomplexa,
        );

        foreach ($notas as $value) {
            if ($value != -1) {
                $somatorio += $value;
                $total++;
            }
        }
        $this->numtotalavaliado = $total;
        $this->nummedia = $somatorio / $total;
        $this->nummediafinal = ($this->nummedia + $this->numnotaavaliador) / 2;
    }

    public function formPopulate()
    {
        return array(
            'ideventoavaliacao' => $this->ideventoavaliacao,
            'idevento' => $this->idevento,
            'desdestaqueservidor' => $this->desdestaqueservidor,
            'desobs' => $this->desobs,
            'idavaliador' => $this->idavaliador,
            'idavaliado' => $this->idavaliado,
            'datcadastro' => $this->datcadastro,
            'numpontualidade' => $this->numpontualidade,
            'numordens' => $this->numordens,
            'numrespeitochefia' => $this->numrespeitochefia,
            'numrespeitocolega' => $this->numrespeitocolega,
            'numurbanidade' => $this->numurbanidade,
            'numequilibrio' => $this->numequilibrio,
            'numcomprometimento' => $this->numcomprometimento,
            'numesforco' => $this->numesforco,
            'numtrabalhoequipe' => $this->numtrabalhoequipe,
            'numauxiliouequipe' => $this->numauxiliouequipe,
            'numaceitousugestao' => $this->numaceitousugestao,
            'numconhecimentonorma' => $this->numconhecimentonorma,
            'numalternativaproblema' => $this->numalternativaproblema,
            'numiniciativa' => $this->numiniciativa,
            'numtarefacomplexa' => $this->numtarefacomplexa,
            'numnotaavaliador' => $this->numnotaavaliador,
            'nummedia' => $this->nummedia,
            'nummediafinal' => $this->nummediafinal,
            'numtotalavaliado' => $this->numtotalavaliado,
            'idtipoavaliacao' => $this->idtipoavaliacao,
            'nomavaliador' => $this->nomavaliador,
            'nomavaliado' => $this->nomavaliado,
        );
    }

}

    

