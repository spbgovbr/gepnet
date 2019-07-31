<?php

class App_View_Helper_ValidateDojoForm extends Zend_View_Helper_Abstract
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
}