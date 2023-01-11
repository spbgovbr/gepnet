/**
 * PACOTE#002
 */
ALTER TABLE agepnet200.tb_projeto ADD COLUMN atraso character varying(20);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN atraso SET DEFAULT 0;
COMMENT ON COLUMN agepnet200.tb_projeto.atraso IS 'Coluna que define a quantidade de dias de atraso do projeto.';

ALTER TABLE agepnet200.tb_projeto ADD COLUMN numpercentualconcluidomarco numeric(5,2);
COMMENT ON COLUMN agepnet200.tb_projeto.numpercentualconcluidomarco IS 'Coluna que define o numero de percentual concluído dos marcos do projeto.';

ALTER TABLE agepnet200.tb_projeto ADD COLUMN domcoratraso character varying(10);
COMMENT ON COLUMN agepnet200.tb_projeto.domcoratraso IS 'Coluna que define a cor dos dias de atraso do projeto. default - atraso 0 inicio do projeto
success - no praso ou adiantada warning - fora do prazo mais dentro da margem de critério farol important - fora do prado e do critério farol.';