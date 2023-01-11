/**
 * PACOTE#008
 * RDM#28082
 */
UPDATE agepnet200.tb_atividadecronograma SET idresponsavel=NULL WHERE idresponsavel NOT IN(SELECT idparteinteressada FROM agepnet200.tb_parteinteressada);
UPDATE agepnet200.tb_atividadecronograma SET idparteinteressada=NULL WHERE idparteinteressada NOT IN(SELECT idparteinteressada FROM agepnet200.tb_parteinteressada);

ALTER TABLE agepnet200.tb_atividadecronograma
  ADD CONSTRAINT fk_responsavelaceitacao_atividadecronograma FOREIGN KEY (idresponsavel)
	REFERENCES agepnet200.tb_parteinteressada (idparteinteressada) ON UPDATE NO ACTION ON DELETE SET NULL;

ALTER TABLE agepnet200.tb_atividadecronograma
  ADD CONSTRAINT fk_responsavelentrega_atividadecronograma FOREIGN KEY (idparteinteressada)
	REFERENCES agepnet200.tb_parteinteressada (idparteinteressada) ON UPDATE NO ACTION ON DELETE SET NULL;

