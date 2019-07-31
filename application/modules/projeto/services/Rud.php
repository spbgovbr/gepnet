<?php

class Projeto_Service_Rud extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_xxxxxxxx
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;
    protected $rootLocal = true;   //= '/home/vagrant/app/upload/'; //Local - desenvolvimento vagrant
    protected $rootDesenv = 'C:\Program Files (x86)\Zend\Apache2\htdocs\\upload/'; //Servidor projetosdesenv - servidor de desenvolvimento
    protected $root = null;
    protected $pastas = array();
    protected $dir = null;
    protected $notify = null;
    public $path = null;

    /**
     * @var array
     */
    public $errors = array();


    public function init()
    {
        $config = Zend_Registry::get('config');
        $uploadDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $this->path = $uploadDir;
        $this->notify = array(
            'text' => '',
            'type' => 'error',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );

        if ($this->rootLocal == true) {
            // Pegando o caminho do arquivo upload
            $explode = explode('/', $_SERVER['PHP_SELF']);
            $this->root = $uploadDir;
//            $this->root = $_SERVER['DOCUMENT_ROOT'] ."/".$explode[1]."/upload/";
            //Zend_Debug::dump($_SERVER['DOCUMENT_ROOT'] ."/".$explode[1]."/upload");die;
        }
//        if(file_exists($this->rootDesenv)){
//            $this->root = $this->rootDesenv;
//        }

    }

    public function getForm($params = null)
    {
        return $this->_getForm('Projeto_Form_Rud');
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormPasta()
    {
        return $this->_getForm('Projeto_Form_RudPasta');
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fileTree($params)
    {
        $this->dir = urldecode($params['dir']);
        $root = $this->root;
        if (file_exists($root . $this->dir . '/')) {
            $files = scandir($root . $this->dir);
            natcasesort($files);
            if (count($files) > 2) { /* The 2 accounts for . and .. */
                echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
                // All dirs
                foreach ($files as $file) {
                    if (file_exists($root . $this->dir . '/' . $file) && $file != '.' && $file != '..' && is_dir($root . $this->dir . '/' . $file)) {
                        $filename = $this->dir . htmlentities($file);
                        echo "<li class=\"directory collapsed\"><input class='chk' style='margin-bottom:2px;' type='checkbox' id='chk_{$filename}' name='{$filename}'/><a style='display:inline-block' href=\"#\" rel=\"" . htmlentities($this->dir . $file) . "/\">" . htmlentities($file) . "</a></li>";
                    }
                }
                // All files
                foreach ($files as $file) {
//          print $root . $_POST['dir'] . '/' . $file;
//          print "<BR>";
                    if (file_exists($root . $this->dir . '/' . $file) && $file != '.' && $file != '..' && !is_dir($root . $this->dir . '/' . $file)) {
                        $ext = @preg_replace('/^.*\./', '', $file);
                        $filename = $this->dir . htmlentities($file);
                        echo "<li class=\"file ext_$ext\"><input class='chk' style='margin-bottom:2px;' type='checkbox' id='chk_{$file}' name='{$filename}'/><a style='display:inline-block' href=\"#\" rel=\"" . htmlentities($this->dir . $file) . "\">" . htmlentities($file) . "</a></li>";
                    }
                }
                echo "</ul>";
            }
        }

    }

    public function getPastas($params)
    {
        $dirRaiz = $this->path . trim($params['idprojeto']);
        if (!is_dir($dirRaiz)) {
            mkdir($dirRaiz);
        }
        if (file_exists($dirRaiz . '/')) {
            $num = 0;
            $dir = $params['idprojeto'];
            $files = scandir($dirRaiz);
            natcasesort($files);
            if (count($files) > 2) { // The 2 accounts for . and ..
                // All dirs
                $this->pastas += array($num => '/');
                $num++;
                foreach ($files as $file) {
                    if (file_exists($this->root . $dir . '/' . $file) && $file != '.' && $file != '..' && is_dir($this->root . $dir . '/' . $file)) {
                        $this->pastas += array($num => htmlentities($file));
                        $num++;
                    }
                }
                return $this->pastas;
            } else {
                $this->pastas += array($num => '/');
                $num++;
                return $this->pastas;
            }
            return false;
        }
    }

    public function upload($dados)
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Upload');
        $form = $this->getForm($dados);
        $this->_db->beginTransaction();

//        print "<PRE>";
//        print_r($_FILES);
//        print_r($dados);
//        exit;
//        $dados = array_filter($dados);
//        if ($form->isValidPartial($dados)) {

        $caminho = array('arquivo1', 'arquivo2', 'arquivo3', 'arquivo4', 'arquivo5');
        //Zend_Debug::dump($caminho);exit;
        $upload = $service->getUploadConfig($form, $dados, $caminho, $dados['nompasta']);
        if ($upload) {
//                $arquivo1 = $form->getElement('arquivo1');
//                $arquivo2 = $form->getElement('arquivo2');
//                $arquivo3 = $form->getElement('arquivo3');
//                $arquivo4 = $form->getElement('arquivo4');
//                $arquivo5 = $form->getElement('arquivo5');
//                $fileName = $this->renomearArquivo($form->getElement('descaminho'),$dados);

//                $model = new Projeto_Model_Statusreport($form->getValues());
            $form->getValues();
//                $model->arquivo1 = $arquivo1;
//                $model->arquivo2 = $arquivo2;
//                $model->arquivo3 = $arquivo3;
//                $id                = $this->_mapper->insert($model);

            $this->_db->commit();

//                return $id;
            $this->setNotify(true, 'Arquivo(s) enviado(s) com sucesso.');
            return true;
        }
        return false;
//        } else {
//            $this->errors = $form->getMessages();
//        }
        return false;
    }

    private function renomearArquivo(Zend_Form_Element_File $file, $dados)
    {
        $extension = pathinfo($file->getFileName('descaminho'), PATHINFO_EXTENSION);
//        $uniqueToken    = md5(uniqid(mt_rand(), true));
        $format = 'pdf_%s_%s.%s';
        //$newFileName    = $id . '.' . $extn;
        $newFileName = sprintf($format, $dados['idprojeto'], $this->proximoId(), $extension);
        $uploadfilepath = $file->getDestination() . DIRECTORY_SEPARATOR . $newFileName;
        //Zend_Debug::dump($uploadfilepath);
        //exit;

        $filterRename = new Zend_Filter_File_Rename(array(
            'target' => $uploadfilepath,
            'overwrite' => true
        ));
        $filterRename->filter($file->getFileName('descaminho'));
        return $newFileName;
    }

    public function criarPasta($dados)
    {
        try {
            //Zend_Debug::dump($dados);exit;
            $pai = $this->path . $dados['idprojeto'];
            $dir = $this->path . $dados['idprojeto'] . '/' . $dados['pasta'];
            if (!is_dir($pai)) {
                mkdir($pai);
            }
            if (!is_dir($dir)) {
                mkdir($dir);
                $this->setNotify(true, 'Diretório criado com sucesso.');
            } else {
                $this->setNotify(false, 'Diretório já existe.');
            }
            return true;
        } catch (Exception $err) {
            $this->errors = $err;
        }
        return false;
    }

    public function delete($dados)
    {
        $diretorio = array();
        try {
            foreach ($dados['arquivos'] as $d) {
                $path = realpath($this->path . $d);
                if (is_dir($path)) {
                    $diretorio[] = $path;
                } elseif (is_readable($path)) {
                    if (!unlink($path)) {
                        $this->setNotify(false, 'Erro ao excluir arquivo.');
                    } else {
                        $this->setNotify(true, 'Arquivo(s) excluído(s) com sucesso.');
                    }
                }
            }
            foreach ($diretorio as $dir) {
                $files_in_directory = scandir($dir);
                if (count($files_in_directory) > 2) {
//                    var_dump(count($files_in_directory)); exit;
                    $this->setNotify(false, 'Diretório não está vazio.');
                } else {
                    rmdir($dir);
                    $this->setNotify(true, 'Diretório(s) excluído(s) com sucesso.');
                }
            }
            return true;
        } catch (Exception $err) {
            $this->errors = $err;
        }
        return false;
    }

    public function setNotify($success, $msg)
    {
        $this->notify['text'] = $msg;
        $this->notify['type'] = ($success) ? 'success' : 'error';
    }


    public function getNotify()
    {
        return $this->notify;
    }


}
