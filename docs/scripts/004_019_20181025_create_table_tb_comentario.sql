/**
 * PACOTE#004
 */

CREATE TABLE agepnet200.tb_comentario
(
  idcomentario integer NOT NULL, -- Coluna identificadora do registro.
  idprojeto integer NOT NULL, -- Coluna idefinficadora do projeto que o comentario pertence
  idatividadecronograma integer NOT NULL, -- Coluna identificadora do grupo ou entrega ou atividade ou marco do cronograma.
  dscomentario character varying(400) NOT NULL, -- Coluna que descreve o comentário.
  dtcomentario timestamp with time zone NOT NULL, -- Data e hora que foi adicionado o comentário pela parte interessada.
  idpessoa integer NOT NULL, -- Coluna que identifica a parte interessada que adicionou o comentário.
  CONSTRAINT pk_comentario PRIMARY KEY (idcomentario),
  CONSTRAINT fk_comentario_atividadecronograma FOREIGN KEY (idatividadecronograma, idprojeto)
  REFERENCES agepnet200.tb_atividadecronograma (idatividadecronograma, idprojeto) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_comentario_pessoa FOREIGN KEY (idpessoa)
  REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_comentario_projeto FOREIGN KEY (idprojeto)
  REFERENCES agepnet200.tb_projeto (idprojeto) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
OIDS=FALSE
);
ALTER TABLE agepnet200.tb_comentario
OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_comentario TO postgres;
COMMENT ON TABLE agepnet200.tb_comentario
IS 'Tabela de comentários dos grupos, entregas, atividades e marcos do cronograma.';
COMMENT ON COLUMN agepnet200.tb_comentario.idcomentario IS 'Coluna identificadora do registro.';
COMMENT ON COLUMN agepnet200.tb_comentario.idprojeto IS 'Coluna idefinficadora do projeto que o comentario pertence';
COMMENT ON COLUMN agepnet200.tb_comentario.idatividadecronograma IS 'Coluna identificadora do grupo ou entrega ou atividade ou marco do cronograma.';
COMMENT ON COLUMN agepnet200.tb_comentario.dscomentario IS 'Coluna que descreve o comentário.';
COMMENT ON COLUMN agepnet200.tb_comentario.dtcomentario IS 'Data e hora que foi adicionado o comentário pela parte interessada.';
COMMENT ON COLUMN agepnet200.tb_comentario.idpessoa IS 'Coluna que identifica a parte interessada que adicionou o comentário.';


-- Index: agepnet200.fki_comentario_pessoa

-- DROP INDEX agepnet200.fki_comentario_pessoa;

CREATE INDEX fki_comentario_pessoa
ON agepnet200.tb_comentario
USING btree
(idpessoa);

