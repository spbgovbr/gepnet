/**
 * PACOTE#002
 */
ALTER TABLE agepnet200.tb_projeto DROP CONSTRAINT fk_projeto_pesdemandante;
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN iddemandante SET DEFAULT NULL;
