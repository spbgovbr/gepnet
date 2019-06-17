<?php header('Content-type: text/html'); ?>
<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                {{nomcodigo}} - {{nomprojeto}}
                <i id="img" class="icon-plus"></i>
            </a>
        </div>
        <div id="collapseOne" class="accordion-body collapse">
            <div class="accordion-inner">
                <strong>Resumo do projeto</strong>
                <table class="table table-striped table-bordered table-price">
                    <colgroup>
                        <col style="width: 50px;"></col>
                        <col style="width: 240px;"></col>
                        <col style="width: 50px;"></col>
                        <col style="width: 280px;"></col>
                        <col style="width: 50px;"></col>
                        <col style="width: 220px;"></col>
                        <col style="width: 50px;"></col>
                        <col style="width: 50px;"></col>
                        <col style="width: 120px;"></col>
                        <col style="width: 110px;"></col>
                    </colgroup>
                    <tr>
                        <th>Patroci.:</th>
                        <td>{{patrocinador.nompessoa}}</td>
                        <th>Meta:</th>
                        <td>{{datinicio}} a {{datfim}} - {{metaEmDias}} dias</td>
                        <th>% concluído planejado:</th>
                        <td>{{numpercentualprevisto}}%</td>
                        <th>Último Relatório:</th>
                        <td>{{ultimoStatusReport.datacompanhamento}}</td>
                        <th>Atraso:</th>
                        <td><span class="badge badge-{{descricaoPrazo}}">{{prazoEmDias}} dias</span></td>
                    </tr>
                    <tr>
                        <th>Gerente:</th>
                        <td>{{gerenteprojeto.nompessoa}}</td>
                        <th>Tendência:</th>
                        <td>{{datinicio}} a {{ultimoStatusReport.datfimprojetotendencia}} - {{tendenciaEmDias}} dias
                        </td>
                        <th>% concluído realizado:</th>
                        <td>{{numpercentualconcluido}}%</td>
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
                        <td colspan="">{{objetivo.nomobjetivo}} <br/> {{acao.nomacao}}</td>
                        <th>% de Marcos Concluído do Projeto:</th>
                        <td colspan="">{{percentualConcluidoMarco}}%</td>
                        <th>Nº do Processo:</th>
                        <td colspan="">{{numprocessosei}}</td>
                        <th>Natureza:</th>
                        {{#compare flacopa "eq" "S"}}
                        <td colspan="">{{natureza.nomnatureza}}</td>
                        <td><span class="label label-info">Projeto da Copa de 2014</span></td>
                        {{else}}
                        <td colspan="">{{natureza.nomnatureza}}</td>
                        {{/compare}}
                    </tr>
                    <tr>
                        <th>Ativ. não iniciadas:</th>
                        <td>{{numpercentualiniciado.qtdeatividadenaoiniciada}} atividade(s)
                            ({{numpercentualiniciado.numpercentualnaoiniciado}}%)
                        </td>
                        <th>Atividades iniciadas:</th>
                        <td>{{numpercentualiniciado.qtdeatividadeiniciada}} atividade(s)
                            ({{numpercentualiniciado.numpercentualiniciado}}%)
                        </td>
                        <th>Atividades concluídas:</th>
                        <td>{{numpercentualiniciado.qtdeatividadeconcluida}} atividade(s)
                            ({{numpercentualiniciado.numpercentualatividadeconcluido}}%)
                        </td>
                        <th></th>
                        <td></td>
                        <th></th>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>