<?php

/**
 * Formulario de resposta de pesquisa montado dinamicamente na service de resultado.
 */
class Pesquisa_Form_PesquisaResponder extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
                'method' => 'post',
                'id' => 'form-responder-pesquisa',
            )
        );
    }

}
