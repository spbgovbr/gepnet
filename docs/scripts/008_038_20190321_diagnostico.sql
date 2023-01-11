/**
 * PACOTE#008
 * RDM#28158
 */

-- Sequence: agepnet200.sq_diagnostico"
-- DROP SEQUENCE agepnet200.sq_diagnostico";

CREATE SEQUENCE agepnet200.sq_diagnostico" 
INCREMENT 1
MINVALUE 1
MAXVALUE 999999999
START 1
CACHE 1;
ALTER TABLE agepnet200.sq_diagnostico" 
OWNER TO postgres;

-- Column: numseq
-- ALTER TABLE agepnet200.tb_diagnostico DROP COLUMN sq_diagnostico;

ALTER TABLE agepnet200.tb_diagnostico ADD COLUMN sq_diagnostico serial;
ALTER TABLE agepnet200.tb_diagnostico ALTER COLUMN sq_diagnostico SET NOT NULL;
ALTER TABLE agepnet200.tb_diagnostico ALTER COLUMN sq_diagnostico SET DEFAULT 1;

ALTER TABLE agepnet200.tb_diagnostico ADD COLUMN ano integer;

