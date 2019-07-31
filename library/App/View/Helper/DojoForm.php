<?php

class App_View_Helper_DojoForm extends Zend_View_Helper_Abstract
{
    public $view;
    protected $_id;
    protected $_url;
    protected $_send = false;
    protected $_validate = true;

    /*
    protected $_jsOnlyValidate;
    protected $_jsOnlySend;
    protected $_jsValidateAndSend;
    */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function DojoForm($id, $url = false, $send = false, $validate = true)
    {
        $this->_id = $id;
        $this->_url = $url;
        $this->_send = $send;
        $this->_validate = $validate;
        if (!$this->_send && !$this->_validate) {
            return;
        } elseif ($this->_send && $this->_validate) {
            $js = $this->jsValidateAndSend($id, $url);
        } elseif ($this->_send && !$this->_validate) {
            $js = $this->jsSend($id, $url);
        } else {
            $js = $this->jsValidate($id);
        }
        $this->view->inlineScript()->captureStart();
        echo $js;
        $this->view->inlineScript()->captureEnd();

    }

    protected function jsValidate($id)
    {
        $this->dialog($id);
        return
            'function validateForm() {
	            var form = dijit.byId("' . $id . '");
	            if (!form.validate()) {
	            	dijit.byId("' . $id . '-dialog").show();
	                return false;
	            }
	            return true;
	        }
            dojo.addOnLoad(function () {
	            dojo.connect(dijit.byId("' . $id . '"), "onSubmit", "validateForm");
	        });
            ';
    }

    protected function jsSend($id, $url)
    {
        $config = 'config_' . $id;
        return
            'var ' . $config . ' = {
		        //url: "' . $url . '",
		        handleAs: "json",
		        load: function(data,args){
		        	console.log(data, typeof data);
		        	console.log(data.errors, typeof data.errors);
		        	
					for (elem in data.errors)
                	{
                		//json = dojo.fromJson(data);
                		console.log(elem, typeof elem);
                		//console.log(elem.isEmpty);
                		
	                    //msg = "<ul>";
                    	for (err in data.errors[elem])
                    	{
                    		var el = dijit.byId(elem);
                    		el.state = "Error";
                    		el._setStateClass();
                    		dijit.setWaiState(el,"invalid",true);
                    		el._maskValidSubsetError = true;
							el.displayMessage(data.errors[elem][err]);
	                        //msg += "<li>" + data.errors[elem][err] + "</li>";
                    	}
                    	//msg += "</ul>";
                    	//dojo.byId(elem + "_error").innerHTML = msg;
                    	
                	}
		        },
		        error: function(data){
		                console.debug("An error occurred: ", data); /*Em caso de erro joga no console*/
		        },
		        //timeout: 2000, //timeout da operacao
		        form: "' . $id . '"
			};
			dojo.addOnLoad(function () {
				var form = dijit.byId("' . $id . '");
	            dojo.connect(form,"onSubmit",function (e) {
					dojo.xhrPost(' . $config . '); /* Executa a requisicao  */
					/*
					var el = dijit.byId("resource_name");
					el.displayMessage("Wilton");
					//console.log(el);
					el.status = "Error";
					*/
					e.preventDefault();
					return false;
	    		});
	        });
			';
    }

    protected function jsValidateAndSend($id, $url)
    {
        $this->dialog($id);
        $config = 'config_' . $id;
        return
            'var ' . $config . ' = {
		        url: "' . $url . '",
		        load: function(data){
				 /* retorno da informacao. Voce pode chamar uma outra funcao aqui para processar os dados de retorno da requisicao, que estao contido na variavel "data" */
		        },
		        error: function(data){
		                console.debug("An error occurred: ", data); /*Em caso de erro joga no console*/
		        },
		        timeout: 2000, //timeout da operacao
		        form: "' . $id . '"
			};
			
			dojo.addOnLoad(function () {
				var form = dijit.byId("' . $id . '");
	            dojo.connect(form,"onSubmit",function (e) {
	            	//form.validate();
		            if (!form.validate()) {
		            	//alert(form.validate());
		            	dijit.byId("' . $id . '-dialog").show();
		            } else {
						dojo.xhrPost(' . $config . '); 
		    		}
		    		e.preventDefault();
					return false;
	    		});
	        });
            ';
    }

    protected function dialog($id)
    {
        $this->view->dojo()->requireModule("dijit.Dialog");

        echo '<div id="' . $id . '-dialog" dojoType="dijit.Dialog" title="Erro no formul&aacute;rio">
	        <p>
	            O formul&aacute;rio cont&eacute;m dados inv&aacute;lidos, por favor corrija.
	        </p>
	    </div>';
    }

    /**
     * Validate dojo enabled form onSubmit.
     *
     * @param string $formId
     * @return void
     */
    /*
    public function SendDojoForm($formId,$formUrl)
    {
        $this->view->inlineScript()->captureStart();
        $config = 'config_' . $formId;
        echo '
		var ' . $config . ' = {
	        url: "' . $formUrl . '",
	        load: function(data){
	        },
	        error: function(data){
	                console.debug("An error occurred: ", data);
	        },
	        timeout: 2000, //timeout da operacao
	        form: "' . $formId . '"
		};
        dojo.addOnLoad(function () {
            dojo.connect(dojo.byId("' . $formId . '"),"onSubmit",function (e) {
            	e.preventDefault();
				dojo.xhrPost(' . $config . '); 
				return false;
    		});
        });
        ';
        $this->view->inlineScript()->captureEnd();
    }
    
	public function ValidateDojoForm($formId)
    {
    	$this->view->dojo()->requireModule("dijit.Dialog");
    	
		echo '<div id="' . $formId . '-dialog" dojoType="dijit.Dialog" title="Erro no formul&aacute;rio">
	        <p>
	            O formul&aacute;rio cont&eacute;m dados inv&aacute;lidos, por favor corrija.
	        </p>
	    </div>';
    	
        $this->view->headScript()->captureStart();
        echo '
        
        function validateForm() {
            var form = dijit.byId("' . $formId . '");
            if (!form.validate()) {
            	dijit.byId("' . $formId . '-dialog").show();
                return false;
            }
            return true;
        }
        dojo.addOnLoad(function () {
            dojo.connect(dijit.byId("' . $formId . '"), "onSubmit", "validateForm");
        });
        ';
        $this->view->headScript()->captureEnd();
    }
    */
}