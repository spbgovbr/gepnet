/**
 * PACOTE#008
 * RDM#28082
 */

ALTER TABLE agepnet200.tb_questionariodiagnosticomelhoria DROP CONSTRAINT ckc_abrangencia;

ALTER TABLE agepnet200.tb_questionariodiagnosticomelhoria
  ADD CONSTRAINT ckc_abrangencia CHECK (flaabrangencia::text = ANY (ARRAY['1'::character(1), '2'::character(1), '3'::character(1)]::text[]));

