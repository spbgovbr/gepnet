/**
 * PACOTE#007
 */

CREATE OR REPLACE VIEW vw_comum_unidade AS
SELECT t1.id_unidade, t1.sigla, t1.nome, t1.unidade_responsavel, t1.tipo,
    t1.ativo, t1.telefones, t1.hierarquia_organizacional, t1.id_tipo_organizacional
   FROM dblink('hostaddr=10.10.10.10 dbname=1010 user=1010 password=1010'::text, 'select id_unidade, sigla, nome, unidade_responsavel, tipo, ativo, telefones, hierarquia_organizacional,id_tipo_organizacional from comum.unidade'::text) t1(id_unidade integer, sigla character varying, nome character varying, unidade_responsavel integer, tipo smallint, ativo boolean, telefones character varying, hierarquia_organizacional character varying, id_tipo_organizacional integer);
ALTER TABLE vw_comum_unidade
  OWNER TO postgres;
GRANT ALL ON TABLE vw_comum_unidade TO postgres;