/**
 * PACOTE#001
 */
ALTER TABLE agepnet200.tb_statusreport
  ADD COLUMN pgpassinado character varying(1),
  ADD COLUMN tepassinado character varying(1),
  ADD COLUMN desandamentoprojeto text;

ALTER TABLE agepnet200.tb_statusreport
  ALTER COLUMN pgpassinado SET DEFAULT 'N'::character varying,
  ALTER COLUMN tepassinado SET DEFAULT 'N'::character varying;
COMMENT ON COLUMN agepnet200.tb_statusreport.desandamentoprojeto IS 'Considerações gerais sobre o andamento do projeto.';