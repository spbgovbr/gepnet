/**
 * PACOTE#003
 */
-- Column: tipo

-- ALTER TABLE agepnet200.tb_permissao DROP COLUMN tipo;

ALTER TABLE agepnet200.tb_permissao ADD COLUMN tipo character(1);
COMMENT ON COLUMN agepnet200.tb_permissao.tipo IS 'Define o tipo de permissão:
G - Geral - Este domínio determina que a permissão possa ser atribuida tanto para quem possa visualizar e/ou editar o projeto;
E - Especifica - Este domínio determina que a permissão possa ser atribuida somente para quem possa editar o projeto.';
