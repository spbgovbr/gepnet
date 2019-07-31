<?php

/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class App_View_Helper_Avisos extends Zend_View_Helper_Abstract
{

    private $textos = array(
        "<b>Art.96</b> - Cabe aos concliadores promover a conciliação entre as partes e a instrução das causas, \n\
         matérias específicas, realizando atos instrutórios previamente definidos, tais como redução a termo\n\
         de depoimentos e acordos a serem homologados, sob a supervisão do juiz federal, sem prejuízo da \n\
         renovação do ato pelo juiz que apreciar o processo.",

        "<b>Art.97</b> - Os conciliadores serão designados pelos coordenadores dos Juizados Especiais Federais em \n\
        em cada Seção Judiciária ou Subseção Judiciária.",

        "<b>Art.98</b> - Os interessados na atuação como conciliadores deverão ser bacharéis em direito ou estudantes\n\
        universitários e assinarão termo de adesão e compromisso perante o Juizado em que forem atuar.",

        "<b>Art.99</b> - A Divulgação da seleção ficará a cargo de cada coordenador de JEF na Seccional ou na Subseção\n\
        Judiciária e será feita por internet e publicação de edital no foro.",

        "<b>Art.100</b> - Os interessados deverão encaminhar currículo e preencher formulário adequado, através do site\n\
         de cada Seccional.\n\
         </br><b>§1º</b> Incumbe à Secretária de Técnologia da Informação a disponibilização do formulálio eletrônico de\n\
         inscrição para as atividades de conciliação, definido por esta resolução.\n\
         </br><b>§2º</b> Cabe à secretaria do coordendor do JEF da Seccional ordenar e arquivar os currículos e remetê-los\n\
         aos respectivos Juizados.",

        "<b>Art.101</b> - Na seleção dos candidatos, a entrevista caberá ao juiz federal do Juizado Especial Federal selecionado\n\
         pelo conciliador apra exercício das atividades, dispensando-se qualquer ato formal de designação.",

        "<b>Art.102</b> - O resultado da seleção será apenas <i>apto</i> ou <i>não apto</i>.\n\
         </br><b>§1º</b> Da desição indeferitória do juiz federal o interessado pode interpor recurso e solicitar nova entrevista \n\
         ao coordenador do Juizado local.\n\
         </br><b>§2º</b> Da decisão do coordenador quanto à nova entrevista não cabem novos recursos.",

        "<b>Art.103</b> - Os conciliadores atuam sempre em qualquer caso sob a orientação e supervisão do juiz federal do JEF local,\n\
         nos limites previstos em lei.\n\
         </br><b>Parágrafo único.</b> Os conciliadores ficarão vinculados à coordenação do Juizado Especial Local.",

        "<b>Art.104</b> - Os conciliadores atuarão conforme a necessidade do Juizado, podendo atuar perante um ou mais juízos, conforme\n\
         a necessidade de serviço.\n\
         </br><b>§1º</b> O número de conciliadores não deve ultrapassar em cada vara o número de 20 apra cada juiz.\n\
         </br><b>§1º</b> Cabe ao juiz federal, mediante reuniões periódicas, orientar os conciliadores que exercem as atividades\n\
         em sua vara JEF.",

        "<b>Art.105</b> - Aplicam-se aos conciliadores os motivos de impedimento e suspeição previstos nos Códigos de Processo Civil\n\
         e de Processo Penal.",

        "<b>Art.106</b> - Os conciliadores ficam impedidos de exercer advocacia perante os Juizados Especiais na Seção Judiciária\n\
         em que atuem.",

        "<b>Art.107</b> - Servidores do Poder Judiciário não podem atuar como conciliadores.",

        "<b>Art.108</b> - A atividade de conciliadore será exercida gratuitamente, sem nenhum vínculo funcional, empregatício, \n\
         contratual ou afim, vedada qualquer espécie de remuneração, contudo assegurados os direitos, prerrogativas e deveres previstos em lei.\n\
         </br><b>Parágrafo único.</b> O conciliador terá cobertura de seguro de acidentes pessoais custeadas pelo Tribunal ou pela Seção\n\
         Judiciária a que for vinculado.",

        "<b>Art.109</b> - Nos termos do <b>§1º</b> do </br><b>art.1º da Resolução 32 do CJF</b>, à função de conciliador, se houver\n\
         previsão no edital, será atribuído 0,5 ponto por ano de atividade na prova de títulos nos concursos do Tribunal Regional\n\
         Federal da Primeira Região.",

    );

    public function avisos()
    {
        return $this->textos[rand(0, (count($this->textos) - 1))];
    }
}