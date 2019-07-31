<?php

/**
 * Class DownloadController
 */
class DownloadController extends Zend_Controller_Action
{
    /**
     * Metodo responsavel por realizar o donwload dos arquivos
     * E necessario conter o parametro "arquivo" na URL de requisicao, com o nome do arquivo
     * encodado com base64_encode
     *
     * @return Zend_Controller_Response_Abstract
     */
    public function indexAction()
    {
        $params = $this->_request->getParams();
        if (array_key_exists('arquivo', $params)) {
            $arquivo = $params['arquivo'];
            $arquivo = base64_decode($arquivo);
            if (file_exists($arquivo)) {
                $body = file_get_contents($arquivo);
                $this->view->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
                return $this->getResponse()->setHeader('Content-Type', 'application/octet-stream')
                    ->setHeader(
                        'Content-Disposition',
                        "attachment; filename=" . basename($arquivo)
                    )
                    ->setHeader('Content-Transfer-Encoding', 'Binary')
                    ->setHeader('Content-Description', 'File Transfer')
                    ->setHeader('Pragma', 'public')
                    ->setHeader('Expires', '0')
                    ->setHeader('Cache-Control', 'must-revalidate')
                    ->setBody($body)
                    ->setHeader('Content-Length', strlen($body));
            }
        }
    }
}
