<?php

class App_AclProjeto extends Zend_Acl
{
    protected $_acl;

    protected $_projeto;

    protected $_pessoa;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    //public function __construct($user)
    public function __construct($projeto, $pessoa)
    {
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_acl = new Zend_Acl();
        $this->_projeto = $projeto;
        $this->_pessoa = $pessoa;
        $this->_initialize();
    }

    protected function _initialize()
    {
        $this->_initRoles();
        $this->_initResources();
        $this->_privileges();
        $this->_saveAcl();
    }

    private function _initRoles()
    {
        $sql = "select s.nompessoa from
                agepnet200.tb_recurso r, agepnet200.tb_permissao p,
                agepnet200.tb_permissaoprojeto c, agepnet200.tb_parteinteressada i,
                agepnet200.tb_pessoa s
                where
                r.idrecurso 		    = p.idrecurso and
                r.idrecurso 		    = c.idrecurso and
                c.idpermissao        	= p.idpermissao and
                c.idparteinteressada 	= i.idparteinteressada and
                c.idprojeto          	= i.idprojeto and
                c.idpessoa          	= s.idpessoa and
                c.idprojeto          	= :projeto and
                i.idpessoainterna   	= :pessoa and
                c.ativo='S'             and i.idpessoainterna is not null and
                upper(r.ds_recurso) like('PROJETO:%')
                group by s.nompessoa";

        $rows = $this->_db->fetchAll($sql, array('projeto' => $this->_projeto, 'pessoa' => $this->_pessoa));

        foreach ($rows as $role) {
            if (false == $this->has($role['nompessoa'])) {
                $this->_acl->addRole(new Zend_Acl_Role($role['nompessoa']));
            }
        }
    }

    private function _initResources()
    {
        if (null == $this->_projeto || null == $this->_pessoa) {
            return;
        }
        $sql = "select
                    r.ds_recurso
                    from
                    agepnet200.tb_recurso r,
                    agepnet200.tb_permissao p,
                    agepnet200.tb_permissaoprojeto c,
                    agepnet200.tb_parteinteressada i
                    where
                    r.idrecurso 		    = p.idrecurso and
                    r.idrecurso 		    = c.idrecurso and
                    c.idpermissao        	= p.idpermissao and
                    c.idparteinteressada 	= i.idparteinteressada and
                    c.idprojeto          	= i.idprojeto and
                    c.idprojeto          	= :idprojeto and
                    i.idpessoainterna   	= :pessoa and
                    c.ativo                 = 'S' and
                    i.idpessoainterna is not null and
                    upper(r.ds_recurso) like('PROJETO:%')
                    group by r.idrecurso,r.idrecurso ";

        $rows = $this->_db->fetchAll($sql);

        foreach ($rows as $recurso) {
            if (false == $this->has($recurso['ds_recurso'])) {
                $this->_acl->addResource(new Zend_Acl_Resource(explode(":", $recurso['ds_recurso'])[1]));
            }
        }
    }

    private function _privileges()
    {
        if (null == $this->_projeto || null == $this->_pessoa) {
            return;
        }
        $sql = "select s.nompessoa, r.ds_recurso
                from
                agepnet200.tb_recurso r, agepnet200.tb_permissaoprojeto c,
                agepnet200.tb_parteinteressada i, agepnet200.tb_pessoa s
                where
                r.idrecurso 		 = c.idrecurso and
                c.idparteinteressada = i.idparteinteressada and
                c.idprojeto          = i.idprojeto and
                c.idpessoa           = s.idpessoa and
                c.idprojeto          = :projeto and
                i.idpessoainterna    = :pessoa and
                c.ativo			     = 'S' and
                i.idpessoainterna is not null and
                upper(r.ds_recurso) like('PROJETO:%')
                group by s.nompessoa, r.ds_recurso";

        $rows = $this->_db->fetchAll($sql, array('projeto' => $this->_projeto, 'pessoa' => $this->_pessoa));

        foreach ($rows as $r) {
            $arrPermissao = $this->getPermissoes($r['ds_recurso']);
            $this->_acl->allow($r['nompessoa'], explode(":", $r['ds_recurso'])[1], $arrPermissao);
        }
    }


    protected function getPermissoes($recurso)
    {
        if (null == $recurso) {
            return;
        }
        $sql = "select p.no_permissao from
                agepnet200.tb_recurso r, agepnet200.tb_permissao p,
                agepnet200.tb_permissaoprojeto c, agepnet200.tb_parteinteressada i
                where
                r.idrecurso 		    = p.idrecurso and
                r.idrecurso 		    = c.idrecurso and
                c.idpermissao        	= p.idpermissao and
                c.idparteinteressada 	= i.idparteinteressada and
                c.idprojeto          	= i.idprojeto and
                c.idprojeto          	= :idprojeto and
                i.idpessoainterna   	= :pessoa and
                upper(r.ds_recurso)	    = upper(:dsrecurso) and
                c.ativo			        = 'S' and
                i.idpessoainterna is not null and
                upper(r.ds_recurso) like('PROJETO:%')
                group by p.idpermissao, p.no_permissao";

        return $this->_db->fetchAll($sql, array('dsrecurso' => $recurso));
    }

    protected function _saveAcl()
    {
        $registry = Zend_Registry::getInstance();
        $registry->set('aclProjeto', $this->_acl);
    }


    public function isAllowed($role, $recurso, $action)
    {
        if (!$this->has($role, $recurso, $action)) {
            return false;
        }
        return ($this->isAllowed($role, $recurso, $action));
    }

}
