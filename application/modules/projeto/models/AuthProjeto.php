<?php

/**
 * Created by PhpStorm.
 * User: wendell.wlfl
 * Date: 03/05/2016
 * Time: 09:23
 */
class Projeto_Model_AuthProjeto
{

    public static function validaUsuarioProjeto($params)
    {
        $auth = Zend_Auth::getInstance();

        $db = Zend_Db_Table::getDefaultAdapter();

        $authAdapter = new Zend_Auth_Adapter_DbTable($db);

        $authAdapter->setTableName('tb_parteinteressada')
            ->setIdentityColumn('idprojeto')
            ->setCredentialColumn('idpessoainterna');
        $authAdapter->setIdentity($projeto)->setCredential($pessoa);


        $result = $auth->authenticate($authAdapter);

        //Verifica se a validação foi efetuada com sucesso
        if ($result->isValid()) {
            //Recupera a informação da parte interessada
            $info = $authAdapter->getResultRowObject(array(
                    'nomfuncao' => null,
                    'destelefone' => null,
                    'desemail' => null,
                    'domnivelinfluencia' => null,
                    'idcadastrador' => null,
                    'datcadastro' => null,
                    'idpessoainterna' => null,
                    'observacao' => null,
                )
            );
            $configuracao = new Projeto_Model_Configura();
            $configuracao->setFullName($info->idprojeto);
            $configuracao->setUserName($info->idparteinteressada);
            $configuracao->setRoleId($info->nomparteinteressada);

            $storage = $auth->getStorage();
            $storage->write($configuracao);

            $aclProjeto = new App_AclProjeto($params()->getParam('idprojeto'), $params()->getParam('idpessoa'));
            $authProjeto = new App_Controller_Plugin_AuthProjeto();
            $authProjeto->preDispatch($params());

            return true;
        }
        return false;
    }
}