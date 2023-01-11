/**
 * PACOTE#004
 */
------------TRATAMENTO RISCO---------------

ALTER TABLE agepnet200.tb_risco ALTER COLUMN domtratamento TYPE numeric(2,0);

ALTER TABLE agepnet200.tb_risco DROP CONSTRAINT cc_domtratamento;

ALTER TABLE agepnet200.tb_risco ADD CONSTRAINT cc_domtratamento CHECK
(domtratamento IS NULL OR (domtratamento = ANY (ARRAY[1::numeric, 2::numeric, 3::numeric, 4::numeric, 5::numeric,
      9::numeric, 10::numeric, 11::numeric, 12::numeric, 13::numeric,
      14::numeric, 15::numeric, 17::numeric, 18::numeric])));