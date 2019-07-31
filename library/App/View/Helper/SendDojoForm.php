<?php

class App_View_Helper_SendDojoForm extends Zend_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Validate dojo enabled form onSubmit.
     *
     * @param string $formId
     * @return void
     */
    public function SendDojoForm($formId, $formUrl)
    {
        $this->view->inlineScript()->captureStart();
        $config = 'config_' . $formId;
        echo '
		var ' . $config . ' = {
	        url: "' . $formUrl . '",
	        load: function(data){
			 /* retorno da informação. Você pode chamar uma outra função aqui para processar os dados de retorno da requisição, que estão contido na váriavel "data" */
	        },
	        error: function(data){
	                console.debug("An error occurred: ", data); /*Em caso de erro joga no console*/
	        },
	        timeout: 2000, //timeout da operação
	        form: "' . $formId . '"
		};
        dojo.addOnLoad(function () {
            dojo.connect(dojo.byId("' . $formId . '"),"onSubmit",function (e) {
            	e.preventDefault();
				dojo.xhrPost(' . $config . '); /* Executa a requisição  */
				return false;
    		});
        });
        ';
        $this->view->inlineScript()->captureEnd();
    }
}