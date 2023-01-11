<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/08/19
 * Time: 11:08
 */
class Projeto_Model_Projeto extends App_Model_ModelAbstract
{
    const STATUS_PROPOSTA = 1;
    const STATUS_ANDAMENTO = 2;
    const STATUS_CONCLUIDO = 3;
    const STATUS_PARALISADO = 4;
    const STATUS_CANCELADO = 5;
    const STATUS_BLOQUEADO = 6;
    const STATUS_ALTERACAO = 7;
    const STATUS_EXCLUIDO = 8;
    const TIPO_INICIATIVA_PROJETO = 1;

    /**@var integer $idprojeto */
    public static $idprojeto = null;
    /**@var string $nomcodigo */
    public static $nomcodigo = null;
    /**@var string $nomsigla */
    public static $nomsigla = null;
    /**@var string $nomprojeto */
    public static $nomprojeto = null;
    /**@var integer $idsetor */
    public static $idsetor = null;
    /**@var string $nomsetor */
    public static $nomsetor = null;
    /**@var integer $idgerenteprojeto */
    public static $idgerenteprojeto = null;
    /**@var string $nomgerenteprojeto */
    public static $nomgerenteprojeto = null;
    /**@var integer $idgerenteadjunto */
    public static $idgerenteadjunto = null;
    /**@var string $nomgerenteadjunto */
    public static $nomgerenteadjunto = null;
    /**@var string $desprojeto */
    public static $desprojeto = null;
    /**@var string $desobjetivo */
    public static $desobjetivo = null;
    /**@var string $datinicio */
    public static $datinicio = null;
    /**@var string $datfim */
    public static $datfim = null;
    /**@var integer $numperiodicidadeatualizacao */
    public static $numperiodicidadeatualizacao = null;
    /**@var integer $numcriteriofarol */
    public static $numcriteriofarol = null;
    /**@var integer $idcadastrador */
    public static $idcadastrador = null;
    /**@var string $datcadastro */
    public static $datcadastro = null;
    /**@var string $domtipoprojeto */
    public static $domtipoprojeto = null;
    /**@var string $flapublicado */
    public static $flapublicado = null;
    /**@var string $flaaprovado */
    public static $flaaprovado = null;
    /**@var string $desresultadosobtidos */
    public static $desresultadosobtidos = null;
    /**@var string $despontosfortes */
    public static $despontosfortes = null;
    /**@var string $despontosfracos */
    public static $despontosfracos = null;
    /**@var string $dessugestoes */
    public static $dessugestoes = null;
    /**@var integer $idescritorio */
    public static $idescritorio = null;
    /**@var string $flaaltagestao */
    public static $flaaltagestao = null;
    /**@var integer $idobjetivo */
    public static $idobjetivo = null;
    /**@var integer $idacao */
    public static $idacao = null;
    /**@var string $flacopa */
    public static $flacopa = null;
    /**@var integer $idnatureza */
    public static $idnatureza = null;
    /**@var string $vlrorcamentodisponivel */
    public static $vlrorcamentodisponivel = null;
    /**@var string $desjustificativa */
    public static $desjustificativa = null;
    /**@var integer $iddemandante */
    public static $iddemandante = null;
    /**@var string $nomdemandante */
    public static $nomdemandante = null;
    /**@var integer $idpatrocinador */
    public static $idpatrocinador = null;
    /**@var string $nompatrocinador */
    public static $nompatrocinador = null;
    /**@var string $datinicioplano */
    public static $datinicioplano = null;
    /**@var string $datfimplano */
    public static $datfimplano = null;
    /**@var string $desescopo */
    public static $desescopo = null;
    /**@var integer $desnaoescopo */
    public static $desnaoescopo = null;
    /**@var string $despremissa */
    public static $despremissa = null;
    /**@var string $desrestricao */
    public static $desrestricao = null;
    /**@var string $numseqprojeto */
    public static $numseqprojeto = null;
    /**@var integer $numanoprojeto */
    public static $numanoprojeto = null;
    /**@var string $desconsideracaofinal */
    public static $desconsideracaofinal = null;
    /**@var string $datenviouemailatualizacao */
    public static $datenviouemailatualizacao = null;
    /**@var integer $idprograma */
    public static $idprograma = null;
    /**@var string $nomproponente */
    public static $nomproponente = null;
    /**@var string $domstatusprojeto */
    public static $domstatusprojeto = null;
    /**@var integer $ano */
    public static $ano = null;
    /**@var integer $idportfolio */
    public static $idportfolio = null;
    /**@var integer $idtipoiniciativa */
    public static $idtipoiniciativa = null;
    /**@var string $numpercentualconcluido */
    public static $numpercentualconcluido = null;
    /**@var string $numpercentualprevisto */
    public static $numpercentualprevisto = null;
    /**@var string $numprocessosei */
    public static $numprocessosei = null;
    /**@var string $atraso */
    public static $atraso = null;
    /**@var string $numpercentualconcluidomarco */
    public static $numpercentualconcluidomarco = null;
    /**@var string $domcoratraso */
    public static $domcoratraso = null;
    /**@var string $qtdeatividadeiniciada */
    public static $qtdeatividadeiniciada = null;
    /**@var string $numpercentualiniciado */
    public static $numpercentualiniciado = null;
    /**@var string $qtdeatividadenaoiniciada */
    public static $qtdeatividadenaoiniciada = null;
    /**@var string $numpercentualnaoiniciado */
    public static $numpercentualnaoiniciado = null;
    /**@var string $qtdeatividadeconcluida */
    public static $qtdeatividadeconcluida = null;
    /**@var string $numpercentualatividadeconcluido */
    public static $numpercentualatividadeconcluido = null;

    /**
     *
     * @var Projeto_Model_Statusreport
     */
    public static $ultimoStatusReport = null;

}