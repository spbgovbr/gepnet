<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Acordo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordo
     */
    public function insert(Default_Model_Acordo $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "idacordopai" => $model->idacordopai,
            "idtipoacordo" => $model->idtipoacordo,
            "nomacordo" => $model->nomacordo,
            "idresponsavelinterno" => $model->idresponsavelinterno,
            "destelefoneresponsavelinterno" => $model->destelefoneresponsavelinterno,
            "idsetor" => $model->idsetor,
            "idfiscal" => $model->idfiscal,
            "destelefonefiscal" => $model->destelefonefiscal,
            "despalavrachave" => $model->despalavrachave,
            "desobjeto" => $model->desobjeto,
            "desobservacao" => $model->desobservacao,
            "datassinatura" => $model->datassinatura,
            "datiniciovigencia" => $model->datiniciovigencia,
            "datfimvigencia" => $model->datfimvigencia,
            "numprazovigencia" => $model->numprazovigencia,
            "datatualizacao" => $model->datatualizacao,
            "datcadastro" => $model->datcadastro,
            "idcadastrador" => $model->idcadastrador,
            "flarescindido" => $model->flarescindido,
            "flasituacaoatual" => $model->flasituacaoatual,
            "numsiapro" => $model->numsiapro,
            "descontatoexterno" => $model->descontatoexterno,
            "idfiscal2" => $model->idfiscal2,
            "idfiscal3" => $model->idfiscal3,
            "datpublicacao" => $model->datpublicacao,
            "descargofiscal" => $model->descargofiscal,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordo
     */
    public function update(Default_Model_Acordo $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "idacordopai" => $model->idacordopai,
            "idtipoacordo" => $model->idtipoacordo,
            "nomacordo" => $model->nomacordo,
            "idresponsavelinterno" => $model->idresponsavelinterno,
            "destelefoneresponsavelinterno" => $model->destelefoneresponsavelinterno,
            "idsetor" => $model->idsetor,
            "idfiscal" => $model->idfiscal,
            "destelefonefiscal" => $model->destelefonefiscal,
            "despalavrachave" => $model->despalavrachave,
            "desobjeto" => $model->desobjeto,
            "desobservacao" => $model->desobservacao,
            "datassinatura" => $model->datassinatura,
            "datiniciovigencia" => $model->datiniciovigencia,
            "datfimvigencia" => $model->datfimvigencia,
            "numprazovigencia" => $model->numprazovigencia,
            "datatualizacao" => $model->datatualizacao,
            "datcadastro" => $model->datcadastro,
            "idcadastrador" => $model->idcadastrador,
            "flarescindido" => $model->flarescindido,
            "flasituacaoatual" => $model->flasituacaoatual,
            "numsiapro" => $model->numsiapro,
            "descontatoexterno" => $model->descontatoexterno,
            "idfiscal2" => $model->idfiscal2,
            "idfiscal3" => $model->idfiscal3,
            "datpublicacao" => $model->datpublicacao,
            "descargofiscal" => $model->descargofiscal,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Acordo);
    }

}

