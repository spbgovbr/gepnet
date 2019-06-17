<?php

class Projeto_Service_R3g extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Gerencia
     */
    protected $_mapper;
    protected $_mapperR3g;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_R3g();
    }

    /**
     * @return Projeto_Form_R3g
     */
    public function getForm()
    {
        return $this->_getForm('Projeto_Form_R3g');
    }

    /**
     * @return Default_Form_R3gEditar
     */
    public function getFormEdit()
    {
        $formEditar = $this->_getForm('Projeto_Form_R3gEdit');
        return $formEditar;
    }

    //put your code here
    public function inserir($dados)
    {
        if (@trim($dados['desplanejado']) != "") {
            $dados['desplanejado'] = @mb_substr(@trim($dados['desplanejado']), 0, 4000);
        }
        if (@trim($dados['desrealizado']) != "") {
            $dados['desrealizado'] = @mb_substr(@trim($dados['desrealizado']), 0, 4000);
        }
        if (@trim($dados['descausa']) != "") {
            $dados['descausa'] = @mb_substr(@trim($dados['descausa']), 0, 4000);
        }
        if (@trim($dados['desconsequencia']) != "") {
            $dados['desconsequencia'] = @mb_substr(@trim($dados['desconsequencia']), 0, 4000);
        }
        if (@trim($dados['descontramedida']) != "") {
            $dados['descontramedida'] = @mb_substr(@trim($dados['descontramedida']), 0, 4000);
        }
        if (@trim($dados['desobs']) != "") {
            $dados['desobs'] = @mb_substr(@trim($dados['desobs']), 0, 4000);
        }
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Projeto_Model_R3g($form->getValues());
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $model->idcadastrador = $auth->getIdentity()->idpessoa;
            }

            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        if (@trim($dados['desplanejado']) != "") {
            $dados['desplanejado'] = @mb_substr(@trim($dados['desplanejado']), 0, 4000);
        }
        if (@trim($dados['desrealizado']) != "") {
            $dados['desrealizado'] = @mb_substr(@trim($dados['desrealizado']), 0, 4000);
        }
        if (@trim($dados['descausa']) != "") {
            $dados['descausa'] = @mb_substr(@trim($dados['descausa']), 0, 4000);
        }
        if (@trim($dados['desconsequencia']) != "") {
            $dados['desconsequencia'] = @mb_substr(@trim($dados['desconsequencia']), 0, 4000);
        }
        if (@trim($dados['descontramedida']) != "") {
            $dados['descontramedida'] = @mb_substr(@trim($dados['descontramedida']), 0, 4000);
        }
        if (@trim($dados['desobs']) != "") {
            $dados['desobs'] = @mb_substr(@trim($dados['desobs']), 0, 4000);
        }
        $form = $this->_getForm('Projeto_Form_R3g');
        if ($form->isValidPartial($dados)) {
            $values = array_filter($form->getValues());
            $model = new Projeto_Model_R3g($values);
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
//        $dados = $this->_mapper->pesquisar($params, $paginator);
        $dados = $this->_mapper->getPaginatortById($params, $paginator);
        if ($paginator) {
            $response = array();
            $response['page'] = $dados->getPages()->current;
            $response['total'] = $dados->getPages()->pageCount;
            $response['records'] = $dados->getPages()->totalItemCount;

            foreach ($dados as $d) {
                $array = array();
                $tipo = $efetiva = '';

                $tipo = $this->getTipo((trim($d['domtipo']) != "" ? $d['domtipo'] : 99));
                $efetiva = $this->getEfetiva((trim($d['flacontramedidaefetiva']) != "" ? $d['flacontramedidaefetiva'] : 99));
                $status = $this->getStatusContramedida((trim($d['domstatuscontramedida']) != "" ? $d['domstatuscontramedida'] : 99));
                $prazo = $this->getPrazoProjeto((trim($d['domcorprazoprojeto']) != "" ? $d['domcorprazoprojeto'] : 99));

//                Zend_Debug::dump($d);exit;

                $array['cell'] = array(
                    $d['datdeteccao'],
                    $tipo,
                    $d['desplanejado'],
                    $d['desrealizado'],
                    $prazo,
                    $d['descontramedida'],
                    $d['datcadastro'],
                    $d['desresponsavel'],
                    $efetiva,
                    $status,
                    $d['descausa'],
                    $d['desconsequencia'],
                    $d['datprazocontramedida'],
                    $d['domcorprazoprojeto'],
                    $d['idcadastrador'],
                    $d['desobs'],
                    $d['idprojeto'],
                    $d['idr3g'],
                );

                $response["rows"][] = $array;
            }
            return $response;
//            $service = new App_Service_JqGrid();
//            $service->setPaginator($dados);
//            return $service;
        }
        return $dados;
    }

    public function getTipo($tipo = false)
    {
        if ($tipo) {
            switch ($tipo) {
                case 1:
                    return 'Prazo';
                    break;
                case 2:
                    return 'Custo';
                    break;
                case 3:
                    return 'Qualidade';
                    break;
                case 4:
                    return 'Escopo';
                    break;
                default :
                    return '';
                    break;

            }
        }
        return array(
            '' => 'Selecione',
            '1' => 'Prazo',
            '2' => 'Custo',
            '3' => 'Qualidade',
            '4' => 'Escopo',
        );
    }

    public function getPrazoProjeto($tipo = false)
    {
        if ($tipo) {
            switch ($tipo) {
                case 1:
                    return "<span class='badge badge-important' title=''>P</span>";
                    break;
                case 2:
                    return "<span class='badge badge-warning' title=''>P</span>";
                    break;
                case 3:
                    return "<span class='badge badge-success' title=''>P</span>";
                    break;
                default :
                    return '';
                    break;

            }
        }

        return array(
            '' => 'Selecione',
            '1' => 'Vermelho',
            '2' => 'Amarelo',
            '3' => 'Verde',
        );
    }

    public function getStatusContramedida($status = false)
    {
        if ($status) {
            switch ($status) {
                case 1:
                    return 'Em Andamento';
                    break;
                case 2:
                    return 'Atrasada';
                    break;
                case 3:
                    return 'Concluída';
                    break;
                case 4:
                    return 'Paralisada';
                    break;
                case 5:
                    return 'Não Iniciada';
                    break;
                case 6:
                    return 'Cancelada';
                    break;
                default :
                    return '';
                    break;

            }
        }
        return array(
            '' => 'Selecione',
            '1' => 'Em andamento',
            '2' => 'Atrasada',
            '3' => 'Concluída',
            '4' => 'Paralisada',
            '5' => 'Não Iniciada',
            '6' => 'Cancelada',
        );
    }

    public function getEfetiva($tipo = false)
    {
        if ($tipo) {
            switch ($tipo) {
                case 1:
                    return 'SIM';
                    break;
                case 2:
                    return 'NÃO';
                    break;
                default :
                    return '';
                    break;
            }
        }
        return array(
            '' => 'Selecione',
            '1' => 'SIM',
            '2' => 'NÃO',
        );
    }

    public function retornaContramedida($params)
    {
        return $this->_mapper->retornaContramedida($params);
    }

    public function retornaTodasContramedidas($params)
    {
        return $this->_mapper->retornaTodasContramedidas($params);
    }

    public function getProjeto($idR3g)
    {
        return $this->_mapper->getProjeto($idR3g);
    }
}