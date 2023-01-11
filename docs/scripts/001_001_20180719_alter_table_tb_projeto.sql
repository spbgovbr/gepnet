/**
 * PACOTE#001
 * RDM#20558
 */
ALTER TABLE agepnet200.tb_projeto
  ADD COLUMN numpercentualconcluido numeric(5,2) DEFAULT (0),
  ADD COLUMN numpercentualprevisto numeric(5,2) DEFAULT (0),
  ADD COLUMN numprocessosei character varying(20);
  
  --ALTER TABLE agepnet200.tb_projeto ALTER COLUMN numprocessosei TYPE character varying(20); 