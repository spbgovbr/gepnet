<?php

class Default_Service_Upload extends App_Service_ServiceAbstract
{

    public function init()
    {   
        $system = PHP_OS;
        if($system == 'WINNT'){
            $caminho = APPLICATION_PATH;
            $explode = explode('\\', $caminho);
            $caminhos = $this->path = '/' . $explode[1] . '/' . $explode[2] . '/' . $explode[3] . '/' . $explode[4] . '/' . $explode[5] . '/upload/';
        }if($system == 'Linux'){
            
            $explode = explode('/',$_SERVER['DOCUMENT_ROOT']);
            $this->path = '/' . $explode[1].'/'.$explode[2].'/'. $explode[3].'/upload/';
            
        }
    }

    /**
     *
     * @param array $params
     * @return \stdClass
     */
    public function getUploadConfig($form,$dados,$caminho = false,$pasta = false)
    {
        if(is_dir($this->path)){
            try{
                $dir = $this->path . $dados['idprojeto'].'/'.$pasta;
//                if($pasta){
//                    $dir .= '/'. $pasta;
//                }
                //$this->criarPasta($dados);

                if($caminho){
                    foreach($caminho as $c){
                        $descaminho =  $form->getElement($c);
                        $descaminho->setDestination($dir);
                    }
                } else {
                    $descaminho = $form->getElement('descaminho');
                    $descaminho->setDestination($dir);
                }

                return true;
            } catch (Exception $err){
                $this->errors = $err;
            }
        }
        return false;
    }

//    public function criarPasta($dados){
//        try{
//            $dir = $this->path . $dados['idprojeto'].'/'.$dados['pasta'];
//            if(!is_dir($dir)){
//                mkdir($dir);
//            }
//            return true;
//        } catch(Exception $err){
//            $this->errors = $err;
//        }
//        return false;
//    }

//    public function delete($dados){
//        try{
//            foreach($dados['arquivos'] as $d){
//                $path = realpath($this->path . '/' . $d);
//                if(is_dir($path)){
////                    $files_in_directory = scandir($path);
////                    if(count($files_in_directory) == 0){
//                        rmdir($path);
////                    }
//                } elseif(is_readable($path)){
//                    unlink($path);
//                }
//            }
//            return true;
//        } catch(Exception $err){
//             return $this->errors = $err;
//        }
//        return false;

//        try{
//
//            foreach($dados['arquivos'] as $d){
//                $path = realpath($this->path . '/' . $d);
//                if(is_dir($path)){
//                        rmdir($path);
//                } elseif(is_readable($path)){
//                    !unlink($path);
//                }
//            }
//            return true;
//        } catch(Exception $err){
//            return $this->errors = $err;
//        }
//        return false;
//    }
}

?>
