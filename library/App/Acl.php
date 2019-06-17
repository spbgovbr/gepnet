<?php

class App_Acl extends Zend_Acl
{

    protected $_perfil = null;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    //public function __construct($user)
    public function __construct()
    {
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        //Zend_Debug::dump($this->_db); exit;
        //$this->_user = $user ? $user : null;
        /*
         * load roles from db
         */
        $this->initRoles();


        /**
         * load resources from db
         */
        $this->initResources();

        /**
         * load end appy permissions from db
         */
        $this->roleResource();
    }

    /*
      public function getRoleById($id)
      {
      switch ($id) {
      case 3:
      $role = 'admin';
      break;
      default:
      $role = 'teste';
      break;
      }
      return $role;
      }
     */

    private function initRoles()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            //Zend_Debug::dump($auth); exit;

            $perfil = Zend_Auth::getInstance()->getIdentity()->perfilAtivo;

            if ($perfil) {
                $this->_perfil = $perfil->idperfil;
                $this->addRole(new Zend_Acl_Role($this->_perfil));
            }
        }

        /*
          $this
          ->addRole(new Zend_Acl_Role('teste')) //Operador
          ->addRole(new Zend_Acl_Role('admin'),'teste'); // Administrador
         */
    }

    private function initResources()
    {
        $sql = "select
                    idrecurso,
                    ds_recurso
               from agepnet200.tb_recurso";
        $rows = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($rows);die;
        $this
            ->add(new Zend_Acl_Resource('default:index'))// Para sair do sistema
            ->add(new Zend_Acl_Resource('default:error')) // Para sair do sistema
            //->add(new Zend_Acl_Resource('cadastro:recurso')) // Para sair do sistema
        ;

        foreach ($rows as $recurso) {
            if (false == $this->has($recurso['ds_recurso'])) {
                $this->add(new Zend_Acl_Resource($recurso['ds_recurso']));
            }
        }
    }

    private function roleResource()
    {
        $this
            ->deny()
            ->allow(null,
                'default:index')// Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            ->allow(null,
                'default:error')// Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            ->allow(null, 'default:autenticarcodigo')
            // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            // ->allow(null, 'cadastro:recurso') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            // ->allow(null, 'cadastro:permissao') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            // ->allow(null, 'cadastro:perfil') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            // ->allow(null, 'projeto:gerencia') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            //->allow(null, 'processo:index') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            // ->allow(null, 'cadastro:documento') // Tem que permitir que cara saia não é mesmo, ah não ser que o perfil seja prisioneiro ai não ehaueshuase =D
            //->allow(null, 'pesquisa:responder') // Libera o acesso externo para responder pesquisas no GEPNET
            //->allow(null, 'cadastro:permissao', 'retorna-por-perfil')

        ;
        if (null == $this->_perfil) {
            return;
        }

        $sql = "select per.idpermissao, rec.idrecurso, per.no_permissao, rec.ds_recurso
                from
                    agepnet200.tb_permissaoperfil ppe,
                    agepnet200.tb_permissao per,
                    agepnet200.tb_recurso rec
                where
                    per.idpermissao = ppe.idpermissao
                    and rec.idrecurso = per.idrecurso
                    and ppe.idperfil = :perfil
                order by rec.ds_recurso asc, per.no_permissao asc";
        $rows = $this->_db->fetchAll($sql, array('perfil' => $this->_perfil));


        foreach ($rows as $permissao) {
            $this->allow($this->_perfil, $permissao['ds_recurso'], array($permissao['no_permissao']));
        }
    }

    public function isUserAllowed($role, $resource, $permission)
    {
        if (false == $this->has($resource)) {
            return false;
        }
        return ($this->isAllowed($role, $resource, $permission));
    }

    /*
      public function isAllowed($role = null, $resource = null, $privilege = null)
      {

      $allow =  parent::isAllowed($role, $resource, $privilege);
      Zend_Debug::dump($role,'role');
      Zend_Debug::dump($resource,'resource');
      Zend_Debug::dump($privilege, 'privilege');
      Zend_Debug::dump($allow,'permitido?');
      return $allow;

      }
     */
}
