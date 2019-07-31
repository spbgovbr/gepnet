<?php

class Default_Service_Impressao extends App_Service_ServiceAbstract
{

    //private $header;
    protected $body;
    //private $footer;
    public $mpdf;

    public $cssHtml;

    public $marginLeft = 5;
    public $marginRight = 5;
    public $marginTop = 5;
    public $marginBottom = 5;
    public $marginHeader = 10;
    public $marginFooter = 10;

    public function init()
    {
//        ini_set('display_errors', 'off');

//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout->disableLayout();
        //$this->mpdf = new mPDF('windows-1252', 'A4-L', '', '', 5, 5, 15, 25, 10, 15, '');
        $this->mpdf = new App_Service_MPDF(
            'windows-1252',    // format - A4, for example, default ''
            'A4-L',     // font size - default 0
            '',    // default font family
            5,    // margin_left
            5,    // margin right
            5,     // margin top
            5,    // margin bottom
            10,     // margin header
            10,     // margin footer
            'L');  // L - landscape, P - portrait

        $this->cssHtml = false;

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

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        if ($this->cssHtml) {
            $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
            $this->mpdf->WriteHTML($cssBootstrap, 1);

            $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
            $this->mpdf->WriteHTML($cssBootstrapResp, 1);
        }
//      $this->mpdf->SetHTMLHeader($this->header);
//      $this->mpdf->SetHTMLFooter($this->footer);
        $project = Zend_Registry::get('config')->project;
        $this->mpdf->SetHTMLFooter('<div align="center" style="font-size: 12px;">' . $project->sigla . ' - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
            . 'Página {PAGENO} de {nbpg}</div>');

        if ($orientation == "L") {
            $this->mpdf->AddPage('L', '', '', '', '', 25, 25, 55, 45, 18, 12);
        } else {
            $this->mpdf->AddPage('P', '', '', '', '',
                $this->marginLeft,    // margin_left
                $this->marginRight,    // margin right
                $this->marginTop,     // margin top
                $this->marginBottom,    // margin bottom
                $this->marginHeader,     // margin header
                $this->marginFooter     // margin footer
            );
        }

        $this->mpdf->WriteHTML($this->body, 2);

        $this->mpdf->Output('document.pdf', 'I');
        $this->mpdf->charset_in = 'windows-1252';
//        $this->mpdf->Output(APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'arquivos' . DIRECTORY_SEPARATOR . 'document.pdf', 'F');
    }

    function gerarPdfHtml($orientation = "P", $modeOpen = "I")
    {
        /* $modeOpen    - I = Inline;            D = Download;               F = Arquivo; S = String; */
        $this->mpdf->Output('documentPdfHtml.pdf', $modeOpen);
        $this->mpdf->charset_in = 'windows-1252';
    }

    function insertFooter($mFooter = "html", $vFooter = "")
    {
        if ($mFooter == "html") {
            $project = Zend_Registry::get('config')->project;
            $this->mpdf->SetHTMLFooter(($vFooter == ""
                ? '<div align="center" style="font-size: 12px;">' . $project->sigla . ' - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                . 'Página {PAGENO} de {nbpg}</div>'
                : $vFooter));
        } else {
            $this->mpdf->setFooter(($vFooter == ""
                ? '{DATE j/m/Y} - PÁG. {PAGENO}/{nb}'
                : $vFooter));
        }
    }

    function insertHeader($mHeader = "html", $vHeader = "")
    {
        if ($mHeader == "html") {
            $project = projectZend_Registry::get('config')->project;
            $this->mpdf->SetHTMLHeader(($vHeader == ""
                ? '<div align="center" style="font-size: 12px;">' . $project->sigla .' - {DATE d/m/Y H:i} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                . 'Página {PAGENO} de {nbpg}</div>'
                : $vHeader));
        } else {
            $this->mpdf->SetHeader(($vHeader == ""
                ? '{DATE j/m/Y} - PÁG. {PAGENO}/{nb}'
                : $vHeader));
        }
    }

    function addHtml($Html = "", $mode = 0, $getConteudo = false)
    {
        /* $mode=> 0 = Todo o Conteúdo;1 = CSS;2 = html */
        $htmlContent = ($getConteudo ? file_get_contents($Html) : $Html);
        $this->mpdf->WriteHTML($htmlContent, $mode);
    }

    function setMargin($mLeft = 5, $mRight = 5, $mTop = 5, $mBottom = 5, $mHeader = 10, $mFooter = 10)
    {
        $this->marginLeft = $mLeft;
        $this->marginRight = $mRight;
        $this->marginTop = $mTop;
        $this->marginBottom = $mBottom;
        $this->marginHeader = $mHeader;
        $this->marginFooter = $mFooter;
    }

    function adicionaPagina($vOrientation = "P")
    {
        $this->mpdf->AddPage($vOrientation /*P ou L*/, '', '', '', '',
            $this->marginLeft,      // margin_left
            $this->marginRight,     // margin right
            $this->marginTop,       // margin top
            $this->marginBottom,    // margin bottom
            $this->marginHeader,    // margin header
            $this->marginFooter     // margin footer
        );
    }

}