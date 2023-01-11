/**
 * PACOTE#001
 */
ALTER TABLE agepnet200.tb_statusreport
  ADD COLUMN numpercentualconcluidomarco numeric(5,2),
  ADD COLUMN diaatraso integer,
  ADD COLUMN domcoratraso character varying(10),
  ADD COLUMN numcriteriofarol integer,
  ADD COLUMN datfimprojeto date;

COMMENT ON COLUMN agepnet200.tb_statusreport.numpercentualconcluidomarco IS 'Apresenta o numero de percentual concluído do marco neste acompanhamento.';
COMMENT ON COLUMN agepnet200.tb_statusreport.diaatraso IS 'Apresenta a quantidade de dias do projeto em atraso para o acompanhamento gerado.';
COMMENT ON COLUMN agepnet200.tb_statusreport.domcoratraso IS 'Apresenta a cor do farol referente aos dias de atraso de acordo com cada acompanhamento gerado.';
COMMENT ON COLUMN agepnet200.tb_statusreport.numcriteriofarol IS 'Apresenta o numero do critério de farol de acordo com o acompanhamento gerado.';
COMMENT ON COLUMN agepnet200.tb_statusreport.datfimprojeto IS 'Apresenta a data fim do projeto para o acompanhamento gerado.';