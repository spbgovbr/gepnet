/**
 * PACOTE#002
 */
ALTER TABLE agepnet200.tb_projeto DROP CONSTRAINT fk_projeto_pesgeradjunto;
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN idgerenteadjunto SET DEFAULT NULL;
