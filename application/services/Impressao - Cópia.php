<?php

class Default_Service_Impressao extends App_Service_ServiceAbstract
{

    //private $header;
    protected $body;
    //private $footer;
    protected $mpdf;

    public function init()
    {
//        ini_set('display_errors', 'off');

//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout->disableLayout();

        define('_MPDF_PATH', '../library/MPDF57/');
        include('../library/MPDF57/mpdf.php');

        $this->mpdf = new mPDF('', 'A4', '', '', 20, 20, 20, 20, 20, 20);
    }

    public function preDispatch()
    {
//        $this->header = $this->view->render('print/header.phtml');
//        $this->footer = $this->view->render('print/footer.phtml');
    }

    /*
     * Default_Service_Impressao
     * @attrib $html
     * @attrib $orientation L = Landscape Default = Portrait
     * @return pdf
     */
    function gerarPdf($html, $orientation = "P")
    {
//        $this->body = $this->view->render($body_path);
        $this->body = $html;

        $stylesheet = file_get_contents('../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents('../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents('../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);

//        $this->mpdf->SetHTMLHeader($this->header);
//        $this->mpdf->SetHTMLFooter($this->footer);
        $project = Zend_Registry::get('config')->project;

        $this->mpdf->SetHTMLFooter('<div align="center" style="font-size: 12px;">' . $project->sigla . ' - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            . 'Página {PAGENO} de {nbpg}</div>');

        if ($orientation == "L") {
            $this->mpdf->AddPage('L', '', '', '', '', 25, 25, 55, 45, 18, 12);
        } else {
            $this->mpdf->AddPage('P', '', '', '', '', '', '', '', 25);
        }

        $this->mpdf->WriteHTML($this->body, 2);

        $this->mpdf->Output('document.pdf', 'I');
        $this->mpdf->charset_in = 'windows-1252';
//        $this->mpdf->Output(APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'arquivos' . DIRECTORY_SEPARATOR . 'document.pdf', 'F');
    }
}

?>
