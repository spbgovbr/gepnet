<?php

class PrintController extends Zend_Controller_Action
{

    private $header;
    private $body;
    private $footer;
    private $mpdf;

    public function init()
    {
        ini_set('display_errors', 'off');

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4', '', '', 20, 20, 20, 20, 20, 20);
    }

    public function preDispatch()
    {
//        $this->header = $this->view->render('print/header.phtml');
//        $this->footer = $this->view->render('print/footer.phtml');
    }

    function indexAction($body_path)
    {
        $this->body = $this->view->render($body_path);

        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

//        $this->mpdf->SetHTMLHeader($this->header);
//        $this->mpdf->SetHTMLFooter($this->footer);

        $this->mpdf->AddPage();
        $this->mpdf->WriteHTML($this->body, 2);

        $this->mpdf->Output('document.pdf', 'I');
    }

}
