/**
 * PACOTE#001
 */
ALTER TABLE agepnet200.tb_pessoa ADD COLUMN versaosistema character varying(10);
COMMENT ON COLUMN agepnet200.tb_pessoa.versaosistema IS 'Define a ultima versão visualizada pelo usuario.';