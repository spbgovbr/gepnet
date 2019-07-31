<?php

class App_View_Helper_ValidateJQueryForm extends Zend_View_Helper_Abstract
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
    public function ValidateJQueryForm($formId)
    {
        //$this->view->dojo()->requireModule("dijit.Dialog");
        $dialog = $formId . '_dialog';
        $form = 'form#' . $formId;

        echo '<div id="' . $dialog . '" title="Erro no formul&aacute;rio">
	        <p>
	            O formul&aacute;rio cont&eacute;m dados inv&aacute;lidos, por favor corrija.
	        </p>
	    </div>';

        $this->view->inlineScript()->captureStart();
        echo '
        $(function () {
        	$("#' . $dialog . '").dialog({
				resizable: false,
				modal: false,
				autoOpen:false,
				buttons: {
					"Delete all items": function() {
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			
			$("' . $form . '").submit(function () {
				$.ajax({
					type: "POST",			
					url: $(this).attr("action"),
					data: $(this).serialize(),
					dataType: "json",
					success: function (data) {
						if (!data.success) {
							$("#' . $dialog . '").dialog("open");
    					}
						
						//alert("Erro");
					},
					error: function () {
						$("#' . $dialog . '").dialog("open");
    				}
				});
				return false;
			});
    	});
        ';
        $this->view->inlineScript()->captureEnd();
    }
}