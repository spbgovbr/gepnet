/**
 * PACOTE#003
 */
 ALTER TABLE agepnet200.tb_pessoa DROP COLUMN token;
ALTER TABLE agepnet200.tb_pessoa ADD COLUMN token character(64);
COMMENT ON COLUMN agepnet200.tb_pessoa.token IS 'Coluna que define o hash de senha para validação.';
