<?php
header('Content-type: text/html');
$offset = 60 * 15;
@header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
@header("Cache-Control: max-age=$offset, must-revalidate");
?>
<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                {{nomcodigo}} - {{nomprojeto}}
            </a>
        </div>
        <div id="collapseOne" class="accordion-body collapse">
            <div class="accordion-inner">
                <strong>Resumo do projeto</strong>
                <table class="table table-striped table-bordered table-price">
                    <colgroup>
                        <col style="width: 50px;">
                        <col style="width: 300px;">
                        <col style="width: 50px;">
                        <col style="width: 200px;">
                        <col style="width: 50px;">
                        <col style="width: 50px;">
                        <col style="width: 100px;">
                        <col style="width: 50px;">
                        <col style="width: 50px;">
                    </colgroup>
                    <tr>
                        <th>Patroci.:</th>
                        <td>{{patrocinador.nompessoa}}</td>
                        <th>Meta:</th>
                        <td>{{datinicio}} a {{datfim}} - {{metaEmDias}} dias</td>
                        <th>Previsto:</th>
                        <td>{{ultimoStatusReport.numpercentualprevisto}}%</td>
                        <th>&Uacute;lt. Rel.:</th>
                        <td>{{ultimoStatusReport.datacompanhamento}}</td>
                        <th>Atraso:</th>
                        <td><span class="badge badge-{{descricaoPrazo}}">{{prazoEmDias}} dias</span></td>
                    </tr>
                    <tr>
                        <th>Gerente:</th>
                        <td>{{gerenteprojeto.nompessoa}}</td>
                        <th>Tendência:</th>
                        <td>{{datinicio}} a&nbsp;{{ultimoStatusReport.datfimprojetotendencia}} - {{tendenciaEmDias}}
                            dias
                        </td>
                        <th>Concluído:</th>
                        <td>{{ultimoStatusReport.numpercentualconcluido}}%</td>
                        <th>Status:</th>
                        <td>{{ultimoStatusReport.domstatusprojeto}}</td>
                        <th>Risco:</th>
                        <td><span class="badge badge-{{descricaoRisco}}">{{ultimoStatusReport.nomdomcorrisco}}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Adjunto:</th>
                        <td>{{gerenteadjunto.nompessoa}}</td>
                        <th>Alinhamento Estratégico:</th>
                        <td colspan="7">{{objetivo.nomobjetivo}} <br/> {{acao.nomacao}}</td>
                        <!--                            <th>Natureza:</th>
                                                    {{#compare flacopa "eq" "S"}}
                                                        <td colspan="2">{{natureza.nomnatureza}}</td>
                                                        <td><span class="label label-info">Projeto da Copa de 2014</span></td>
                                                    {{else}}
                                                        <td colspan="3">{{natureza.nomnatureza}}</td>
                                                    {{/compare}}-->
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
