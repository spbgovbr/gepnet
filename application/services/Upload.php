<?php

class Default_Service_Upload extends App_Service_ServiceAbstract
{

    public function init()
    {
        $config = Zend_Registry::get('config');
        $uploadDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $this->path = $uploadDir;
    }

    /**
     *
     * @param array $params
     * @return \stdClass
     */
    public function getUploadConfig($form, $dados, $caminho = false, $pasta = false)
    {
        //Zend_Debug::dump($this->path); exit;

        if (is_dir($this->path)) {
            try {
                if (isset($dados['idprojeto'])) {
                    $dir = $this->path . $dados['idprojeto'] . '/' . $pasta;
                } else {
                    if (isset($dados['idplanodeacao'])) {
                        $dir = $this->path . $dados['idplanodeacao'] . '/' . $pasta;
                    }
                }
//                if($pasta){
//                    $dir .= '/'. $pasta;
//                }
                //$this->criarPasta($dados);

                if ($caminho) {
                    foreach ($caminho as $c) {
                        $descaminho = $form->getElement($c);
                        $descaminho->setDestination($dir);
                    }
                } else {
                    $descaminho = $form->getElement('descaminho');
                    $descaminho->setDestination($dir);
                }

                return true;
            } catch (Exception $err) {
                $this->errors = $err;
            }
        }
        return false;
    }

}

?>
