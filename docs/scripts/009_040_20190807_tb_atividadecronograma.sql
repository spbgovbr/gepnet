/**
 * PACOTE#009
 * RDM#36056
 */
ALTER TABLE agepnet200.tb_atividadecronograma
    ADD COLUMN datatividadeconcluida date;

COMMENT ON COLUMN agepnet200.tb_atividadecronograma.datatividadeconcluida
    IS 'Data da atividade concluida.';

