/**
 * PACOTE#001
 */
-- Alterando a tabela para adicionar coluna para controlar o acesso do status do tipo da contramedida que pode ser alterada dinamicamente no formulário de acordo com o tipo escolhido:
alter table agepnet200.tb_tipocontramedida add column idstatustipocontramedida integer;
