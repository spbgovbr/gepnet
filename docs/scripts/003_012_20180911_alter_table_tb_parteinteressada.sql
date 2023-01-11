/**
 * PACOTE#003
 */
ALTER TABLE agepnet200.tb_parteinteressada ADD COLUMN tppermissao character varying(1);
ALTER TABLE agepnet200.tb_parteinteressada ALTER COLUMN tppermissao SET DEFAULT '1'::character varying;
COMMENT ON COLUMN agepnet200.tb_parteinteressada.tppermissao IS 'Combobox(Permissão) com as opções: 
1 - Editar,
2 - Visualizar';

UPDATE agepnet200.tb_parteinteressada
   SET tppermissao='1'
 WHERE idpessoainterna is not null;