/**
 * PACOTE#001
 */
-- Alterando a tabela para criar a coluna de verificação do PGP assinado para exibir as opções conforme Rdm 16816 a especificação:
ALTER TABLE agepnet200.tb_etapa
ADD COLUMN pgpassinado character varying(1);
