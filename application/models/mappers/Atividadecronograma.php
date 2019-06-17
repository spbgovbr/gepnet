<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Atividadecronograma extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Atividadecronograma
     */
    public function insert(Default_Model_Atividadecronograma $model)
    {
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "idgrupo" => $model->idgrupo,
            "idpredecessora" => $model->idpredecessora,
            "numseq" => $model->numseq,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "datiniciobaseline" => $model->datiniciobaseline,
            "datfimbaseline" => $model->datfimbaseline,
            "datinicio" => $model->datinicio,
            "datfim" => $model->datfim,
            "idresponsavel" => $model->idresponsavel,
            "domtipoatividade" => $model->domtipoatividade,
            "flacancelada" => $model->flacancelada,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idmarcoanterior" => $model->idmarcoanterior,
            "numdias" => $model->numdias,
            "vlratividadebaseline" => $model->vlratividadebaseline,
            "vlratividade" => $model->vlratividade,
            "nomresponsavel" => $model->nomresponsavel,
            "numfolga" => $model->numfolga,
            "descriterioaceitacao" => $model->descriterioaceitacao,
            "flaaquisicao" => $model->flaaquisicao,
            "flainformatica" => $model->flainformatica,
            "idelementodespesa" => $model->idelementodespesa,
            "idpredecessora2" => $model->idpredecessora2,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Atividadecronograma
     */
    public function update(Default_Model_Atividadecronograma $model)
    {
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "idgrupo" => $model->idgrupo,
            "idpredecessora" => $model->idpredecessora,
            "numseq" => $model->numseq,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "nomatividadecronograma" => $model->nomatividadecronograma,
            "datiniciobaseline" => $model->datiniciobaseline,
            "datfimbaseline" => $model->datfimbaseline,
            "datinicio" => $model->datinicio,
            "datfim" => $model->datfim,
            "idresponsavel" => $model->idresponsavel,
            "domtipoatividade" => $model->domtipoatividade,
            "flacancelada" => $model->flacancelada,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idmarcoanterior" => $model->idmarcoanterior,
            "numdias" => $model->numdias,
            "vlratividadebaseline" => $model->vlratividadebaseline,
            "vlratividade" => $model->vlratividade,
            "nomresponsavel" => $model->nomresponsavel,
            "numfolga" => $model->numfolga,
            "descriterioaceitacao" => $model->descriterioaceitacao,
            "flaaquisicao" => $model->flaaquisicao,
            "flainformatica" => $model->flainformatica,
            "idelementodespesa" => $model->idelementodespesa,
            "idpredecessora2" => $model->idpredecessora2,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Atividadecronograma);
    }

}

