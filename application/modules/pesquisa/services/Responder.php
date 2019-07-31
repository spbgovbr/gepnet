<?php

class Pesquisa_Service_Responder extends App_Service_ServiceAbstract
{

    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Pesquisa_Model_Mapper_ResultadoPesquisa();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retorna o form da referente a pesquisa
     *
     * @param type $params
     * @return Pesquisa_Form_ResponderPesquisa
     */
    public function getFormPesquisa($params)
    {
        $this->_form = $this->dinamicFormPesquisa($params);
        return $this->_form;
    }

    public function retornaPesquisasResponderGrid($params)
    {
        try {
            $pesquisa = new Pesquisa_Model_Mapper_Pesquisa();
            $dados = $pesquisa->retornaPesquisasResponderGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function salvarPesquisaRespondida($idpesquisa, $params)
    {
        $form = $this->getFormPesquisa(array('idpesquisa' => $idpesquisa));
        if ($form->isValid($params)) {
            $serviceResultado = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
            $result = $serviceResultado->salvarResultadoPesquisa($form->getValues());
            //Zend_Debug::dump($result);exit;
            return $result;
        } else {
            $this->errors = $form->getMessages();
            return false;


        }
    }

    public function salvarPesquisaRespondidaExterna($idpesquisa, $params)
    {
        $form = $this->getFormPesquisa(array('idpesquisa' => $idpesquisa));
        if ($form->isValid($params)) {
            $serviceResultado = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');

            $result = $serviceResultado->salvarResultadoPesquisaExterna($form->getValues());
            return $result;
        } else {
            $this->errors = $form->getMessages();
            return false;


        }
    }

    public function dinamicFormPesquisa($params)
    {
        try {
            $pesquisaMapper = new Pesquisa_Model_Mapper_Pesquisa();
            $pesquisa = $pesquisaMapper->detalharPesquisaById($params);
            $formResponder = new Pesquisa_Form_PesquisaResponder();

            $formResponder->addElement('hidden', 'idquestionariopesquisa', array(
                'required' => true,
                'filters' => array('StringTrim', 'StripTags'),
                'validators' => array('Digits'),
            ));

            $idfrasepesquisa = "";
            $arrOptions = array();
            $element = "";
            foreach ($pesquisa as $questao) {
                //se a frase for diferente da idfrase pesquisa corrente cria um novo elemento
                if ($questao['idfrasepesquisa'] != $idfrasepesquisa) {

                    //seta a informacoes do combo da pergunta anterior antes de iniciar a proxima
                    if ($element != "") {
                        $formResponder->getElement($element)->addMultiOptions($arrOptions);
                    }

                    //reset nos valores do elemento a cada nova pergunta
                    $arrOptions = array();
                    $element = "";

                    //verifica o tipo de elemento a ser criado (select, text, hidden)
                    switch ($questao['domtipofrase']) {
                        case Pesquisa_Model_Frase::UMA_ESCOLHA:
                            $formResponder->addElement('radio', 'idfrasepesquisa_' . $questao['idfrasepesquisa'], array(
                                'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(
                                    array(
                                        'NotEmpty',
                                        false,
                                        array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                    ),
                                    array('StringLength', false, array(0, 3))
                                ),
                                'attribs' => array(
                                    'class' => 'span',
                                    'maxlength' => '3',
                                    'data-rule-maxlength' => 3,
                                    'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'placeholder' => 'Selecione a opção'
                                ),
                            ));
                            //armazena a opcao do combo
                            $arrOptions[$questao['idrespostapesquisa']] = $questao['desresposta'];
                            //armazena o elemento que necessita do combo
                            $element = 'idfrasepesquisa_' . $questao['idfrasepesquisa'];

                            break;
                        case Pesquisa_Model_Frase::MULTIPLA_ESCOLHA:
                            $formResponder->addElement('multiCheckbox',
                                'idfrasepesquisa_' . $questao['idfrasepesquisa'], array(
                                    'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                    'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'filters' => array('StringTrim', 'StripTags'),
                                    'validators' => array(
                                        array(
                                            'NotEmpty',
                                            false,
                                            array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                        ),
                                    ),
                                    'attribs' => array(
                                        'class' => 'span',
                                        'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                        'placeholder' => 'Selecione a opção'
                                    ),
                                ));
                            //armazena a opcao do combo
                            $arrOptions[$questao['idrespostapesquisa']] = $questao['desresposta'];
                            //armazena o elemento que necessita do combo
                            $element = 'idfrasepesquisa_' . $questao['idfrasepesquisa'];

                            break;

                        case Pesquisa_Model_Frase::DESCRITIVO:
                            $formResponder->addElement('text', 'idfrasepesquisa_' . $questao['idfrasepesquisa'], array(
                                'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(
                                    array(
                                        'NotEmpty',
                                        false,
                                        array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                    ),
                                    array('StringLength', false, array(0, 255))
                                ),
                                'attribs' => array(
                                    'class' => 'span',
                                    'maxlength' => '255',
                                    'data-rule-maxlength' => 255,
                                    'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'placeholder' => 'Informe a resposta'
                                ),
                            ));
                            break;
                        case Pesquisa_Model_Frase::TEXTO:
                            $formResponder->addElement('textarea', 'idfrasepesquisa_' . $questao['idfrasepesquisa'],
                                array(
                                    'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                    'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'filters' => array('StringTrim', 'StripTags'),
                                    'validators' => array(
                                        array(
                                            'NotEmpty',
                                            false,
                                            array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                        ),
                                        array('StringLength', false, array(0, 255))
                                    ),
                                    'attribs' => array(
                                        'class' => 'span',
                                        'maxlength' => '255',
                                        'data-rule-maxlength' => 255,
                                        'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                        'placeholder' => 'Informe a resposta',
                                        'rows' => 8
                                    ),
                                ));
                            break;
                        case Pesquisa_Model_Frase::NUMERO:
                            $formResponder->addElement('text', 'idfrasepesquisa_' . $questao['idfrasepesquisa'], array(
                                'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(
                                    array(
                                        'NotEmpty',
                                        false,
                                        array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                    ),
                                    array('StringLength', false, array(0, 255)),
                                    array('Digits'),
                                ),
                                'attribs' => array(
                                    'class' => 'span',
                                    'maxlength' => '255',
                                    'data-rule-maxlength' => 255,
                                    'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'data-rule-number' => true,
                                    'placeholder' => 'Informe a resposta'
                                ),
                            ));
                            break;
                        case Pesquisa_Model_Frase::DATA:
                            $formResponder->addElement('text', 'idfrasepesquisa_' . $questao['idfrasepesquisa'], array(
                                'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(
                                    array(
                                        'NotEmpty',
                                        false,
                                        array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                    ),
                                    array('StringLength', false, array(0, 10)),
                                    array(
                                        'Date',
                                        false,
                                        array('messages' => array('dateInvalidDate' => 'Data inválida.'))
                                    ),
                                ),
                                'attribs' => array(
                                    'class' => 'span mask-date',
                                    'maxlength' => '10',
                                    'data-rule-maxlength' => 10,
                                    'data-rule-dateITA' => true,
                                    'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'placeholder' => 'DD/MM/AAAA'
                                ),
                            ));
                            break;
                        case Pesquisa_Model_Frase::UF:
                            $formResponder->addElement('select', 'idfrasepesquisa_' . $questao['idfrasepesquisa'],
                                array(
                                    'label' => $questao['numordempergunta'] . ' - ' . $questao['desfrase'],
                                    'required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                    'filters' => array('StringTrim', 'StripTags'),
                                    'validators' => array(
                                        array('StringLength', 'options' => array(0, 2)),
                                        array(
                                            'NotEmpty',
                                            false,
                                            array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                                        ),
                                    ),
                                    'attribs' => array(
                                        'class' => 'span',
                                        'maxlength' => '2',
                                        'data-rule-maxlength' => 2,
                                        'data-rule-required' => $questao['obrigatoriedade'] == 'S' ? true : false,
                                        'placeholder' => 'Selecione a opção'
                                    ),
                                ));

                            //armazena a opcao do combo
                            //Zend_Debug::dump($pesquisa);exit;
                            $arrOptions[$questao['idrespostapesquisa']] = $questao['desresposta'];
                            //armazena o elemento que necessita do combo
                            $element = 'idfrasepesquisa_' . $questao['idfrasepesquisa'];
                            break;

                        default:
                            break;
                    }
                    //atribui o idfrasepesquisa corrente para comparar com a proxima pergunta
                    $idfrasepesquisa = $questao['idfrasepesquisa'];
                } else {
                    //armazena a opcao do combo
                    $arrOptions[$questao['idrespostapesquisa']] = $questao['desresposta'];
                }
            }

            //se a ultima pergunta for multioptions seta as opcoes do combo
            if ($element != "") {
                $formResponder->getElement($element)->addMultiOptions($arrOptions);
            }

            //botoes padrao do form
            $formResponder->addElement('button', 'submit', array(
                'ignore' => true,
                'label' => 'Salvar',
                'escape' => false,
                'attribs' => array(
                    'id' => 'salvar',
                    'type' => 'submit',
                    'class' => 'btn-success',
                ),
            ));
            $formResponder->getElement('submit')
                ->removeDecorator('label')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Wrapper');

            $formResponder->addElement('button', 'reset', array(
                'ignore' => true,
                'label' => 'Limpar',
                'escape' => false,
                'attribs' => array(
                    'id' => 'limpar',
                    'type' => 'reset',
                ),
            ));
            $formResponder->getElement('reset')
                ->removeDecorator('label')
                ->removeDecorator('HtmlTag')
                ->removeDecorator('Wrapper');

            //alimenta o form com o id do questionario
            $formResponder->setLegend($pesquisa[0]['nomquestionario']);
            $formResponder->populate(array('idquestionariopesquisa' => $questao['idquestionariopesquisa']));
            return $formResponder;
        } catch (Exception $exc) {
            $this->error = App_Service_ServiceAbstract::ERRO_GENERICO;
        }
    }

    /**
     * Verifica se a pesquisa ja foi respondida pelo usuário
     * @return boolean
     */
    public function cpfRespondeuPesquisa($params)
    {
        try {
            $result = $this->_mapper->existsRespostaPesquisaByCpf($params);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * Resgata os dados do usuario autenticado via LDAP e verifica se ja respondeu a pesquisa informada
     * @param array $params
     * @return type
     */
    public function respondeuPesquisaLdap($params)
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));
        $dataUser = $auth->getIdentity();
        $cpfLdap = $dataUser['data_user']['cpf'][0];

        $params['cpf'] = $cpfLdap;

        return $this->cpfRespondeuPesquisa($params);
    }

    /**
     * Resgata os dados do usuario autenticado via SISEG e verifica se ja respondeu a pesquisa informada
     * @param array $params
     * @return type
     */
    public function respondeuPesquisaInterna($params)
    {
        $params['cpf'] = $this->auth->cpf;
        return $this->cpfRespondeuPesquisa($params);
    }

}
