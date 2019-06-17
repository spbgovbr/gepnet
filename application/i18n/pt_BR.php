<?php

// Tradução para o Português por Nivaldo Arruda - nivaldo@gmail.com
$portugues = array();
$portugues['isEmpty'] = 'Este campo não pode ser vazio';
$portugues['stringEmpty'] = "'%value%' é uma string vazia";
$portugues['noRecordFound'] = "Um registro com o valor '%value%' não foi encontrado";
$portugues['recordFound'] = "Um registro com o valor '%value%' foi encontrado";

// Email
$portugues['emailAddressInvalid'] = 'Não é um email válido no formato nome@servidor';
$portugues['emailAddressInvalidFormat'] = 'Não é um email válido no formato nome@servidor';

//hostname
$portugues['hostnameIpAddressNotAllowed'] = "'%value%' Parece ser um endereço de IP, mas endereços de IP não são permitidos";
$portugues['hostnameUnknownTld'] = "'%value%' parece ser um DNS, mas não foi possivel validar o TLD";
$portugues['hostnameDashCharacter'] = "'%value%' parece ser um DNS, mas contém um 'dash' (-) em uma posição inválida";
$portugues['hostnameInvalidHostnameSchema'] = "'%value%' parece ser um DNS, mas não foi possível comparar com o schema para o TLD '%tld%'";
$portugues['hostnameUndecipherableTld'] = "'%value%' parece ser um DNS mas não foi possível extrair o TLD";
$portugues['hostnameInvalidHostname'] = "'%value% não é compatível com a estrutura DNS";
$portugues['hostnameInvalidLocalName'] = "'%value%' não parece ser uma rede local válida";
$portugues['hostnameLocalNameNotAllowed'] = "'%value%' parece ser o nome de uma rede local, mas nome de rede local não são permitido";

//identical

$portugues['notSame'] = "A comparação não coincide.";
$portugues['missingToken'] = "Não foi fornecido parâmetros para teste";

//greater then
$portugues['notGreaterThan'] = "'%value%' não é maior que '%min%'";

//float
$portugues['notFloat'] = "'%value%' não é do tipo float";

//date
$portugues['dateNotYYYY-MM-DD'] = "'%value%' deve estar no formato aaaa-mm-dd";
$portugues['dateInvalid'] = "'%value%' não parece ser um data válida";
$portugues['dateFalseFormat'] = "'%value%' não combina com o formato informado";

//digits
$portugues['notDigits'] = "'%value%' não contém apenas dígitos";

//between
$portugues['notBetween'] = "'%value%' não está entre '%min%' e '%max%', inclusive";
$portugues['notBetweenStrict'] = "'%value%' não está estritamente entre '%min%' e '%max%'";

//alnum
$portugues['notAlnum'] = "'%value%' não possue apenas letras e dígitos";

//alpha
$portugues['notAlpha'] = "'%value%' não possue apenas letras";

//in array
$portugues['notInArray'] = "'%value%' não foi encontrado na lista";

//int
$portugues['notInt'] = "'%value%' não parece ser um inteiro";

//ip
$portugues['notIpAddress'] = "'%value%' não parece ser um endereço ip válido";

//lessthan
$portugues['notLessThan'] = "'%value%' não é menor que '%max%'";

//notempty
$portugues['isEmpty'] = "Campo vazio, mas um valor diferente de vazio é esperado";

//regex
$portugues['regexNotMatch'] = "'%value%' não foi validado na expressão '%pattern%'";

//stringlength
$portugues['stringLengthTooShort'] = "'%value%' é menor que %min% (tamanho mánimo desse campo)";
$portugues['stringLengthTooLong'] = "'%value%' é maior que  %max% (tamanho maximo desse campo)";

//$portugues[''] = "";
return $portugues;