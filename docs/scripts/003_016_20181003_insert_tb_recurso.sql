/**
 * PACOTE#003
 */
INSERT INTO
  agepnet200.tb_recurso(idrecurso, ds_recurso)
VALUES ((select max(idrecurso)+1 from agepnet200.tb_recurso), 'default:autenticarcodigo');