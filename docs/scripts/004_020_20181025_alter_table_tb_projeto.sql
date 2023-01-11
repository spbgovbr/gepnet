/**
 * PACOTE#004
 */

--------------ALTERAÇÃO DA TABELA PROJETOS

ALTER TABLE agepnet200.tb_projeto ADD COLUMN qtdeatividadeiniciada numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN qtdeatividadeiniciada SET DEFAULT 0;

ALTER TABLE agepnet200.tb_projeto ADD COLUMN numpercentualiniciado numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN numpercentualiniciado SET DEFAULT 0;

ALTER TABLE agepnet200.tb_projeto ADD COLUMN qtdeatividadenaoiniciada numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN qtdeatividadenaoiniciada SET DEFAULT 0;

ALTER TABLE agepnet200.tb_projeto ADD COLUMN numpercentualnaoiniciado numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN numpercentualnaoiniciado SET DEFAULT 0;

ALTER TABLE agepnet200.tb_projeto ADD COLUMN qtdeatividadeconcluida numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN qtdeatividadeconcluida SET DEFAULT 0;

ALTER TABLE agepnet200.tb_projeto ADD COLUMN numpercentualatividadeconcluido numeric(5,2);
ALTER TABLE agepnet200.tb_projeto ALTER COLUMN numpercentualatividadeconcluido SET DEFAULT 0;
