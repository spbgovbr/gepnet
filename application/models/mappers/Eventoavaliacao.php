<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Eventoavaliacao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Eventoavaliacao
     */
    public function insert(Default_Model_Eventoavaliacao $model)
    {
        $data = array(
            "ideventoavaliacao" => $model->ideventoavaliacao,
            "idevento" => $model->idevento,
            "desdestaqueservidor" => $model->desdestaqueservidor,
            "desobs" => $model->desobs,
            "idavaliador" => $model->idavaliador,
            "idavaliado" => $model->idavaliado,
            "datcadastro" => $model->datcadastro,
            "numpontualidade" => $model->numpontualidade,
            "numordens" => $model->numordens,
            "numrespeitochefia" => $model->numrespeitochefia,
            "numrespeitocolega" => $model->numrespeitocolega,
            "numurbanidade" => $model->numurbanidade,
            "numequilibrio" => $model->numequilibrio,
            "numcomprometimento" => $model->numcomprometimento,
            "numesforco" => $model->numesforco,
            "numtrabalhoequipe" => $model->numtrabalhoequipe,
            "numauxiliouequipe" => $model->numauxiliouequipe,
            "numaceitousugestao" => $model->numaceitousugestao,
            "numconhecimentonorma" => $model->numconhecimentonorma,
            "numalternativaproblema" => $model->numalternativaproblema,
            "numiniciativa" => $model->numiniciativa,
            "numtarefacomplexa" => $model->numtarefacomplexa,
            "domtipoavaliacao" => $model->domtipoavaliacao,
            "numnotaavaliador" => $model->numnotaavaliador,
            "nummedia" => $model->nummedia,
            "nummediafinal" => $model->nummediafinal,
            "numtotalavaliado" => $model->numtotalavaliado,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Eventoavaliacao
     */
    public function update(Default_Model_Eventoavaliacao $model)
    {
        $data = array(
            "ideventoavaliacao" => $model->ideventoavaliacao,
            "idevento" => $model->idevento,
            "desdestaqueservidor" => $model->desdestaqueservidor,
            "desobs" => $model->desobs,
            "idavaliador" => $model->idavaliador,
            "idavaliado" => $model->idavaliado,
            "datcadastro" => $model->datcadastro,
            "numpontualidade" => $model->numpontualidade,
            "numordens" => $model->numordens,
            "numrespeitochefia" => $model->numrespeitochefia,
            "numrespeitocolega" => $model->numrespeitocolega,
            "numurbanidade" => $model->numurbanidade,
            "numequilibrio" => $model->numequilibrio,
            "numcomprometimento" => $model->numcomprometimento,
            "numesforco" => $model->numesforco,
            "numtrabalhoequipe" => $model->numtrabalhoequipe,
            "numauxiliouequipe" => $model->numauxiliouequipe,
            "numaceitousugestao" => $model->numaceitousugestao,
            "numconhecimentonorma" => $model->numconhecimentonorma,
            "numalternativaproblema" => $model->numalternativaproblema,
            "numiniciativa" => $model->numiniciativa,
            "numtarefacomplexa" => $model->numtarefacomplexa,
            "domtipoavaliacao" => $model->domtipoavaliacao,
            "numnotaavaliador" => $model->numnotaavaliador,
            "nummedia" => $model->nummedia,
            "nummediafinal" => $model->nummediafinal,
            "numtotalavaliado" => $model->numtotalavaliado,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Eventoavaliacao);
    }

}

