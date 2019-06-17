<?php
header('Content-type: text/html');
$offset = 60 * 15;
@header("Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT");
@header("Cache-Control: max-age=$offset, must-revalidate");
?>
<div class="container-grupo-cabecalho">
    <div class="cron-radio">&nbsp;</div>
    <div class="nome-grupo"
         title="{{nomcodigo}} - {{nomprojeto}}"
         style="width: 21.3% !important;">
        <i class="icon-folder-close"></i>&nbsp;&nbsp;{{nomeprojeto}}
    </div>
    <div class="cron-perdecessoras"
         style="width: 3.1% !important;">&nbsp;
    </div>
    <div class="cron-datas"
         style="width: 12.1% !important;">
        <span class="tabulador">{{datiniciobaselinet}} a {{datfimbaselinet}}</span>
    </div>
    <div class="cron-dias"
         style="width: 3.1% !important;">
        <span class="tabulador">{{diasbaselinet}}</span>
    </div>
    <div class="cron-custo"
         style="width: 6.1% !important;">
        <span class="tabulador">{{vlratividadet}}</span>
    </div>
    <div class="cron-datas-reais"
         style="width: 13.2% !important;">
        <span class="tabulador">{{datiniciot}} a {{datfimt}}</span>
    </div>
    <div class="cron-numdias-realizados"
         style="width: 4.1% !important;">
        <span class="tabulador">{{diasrealt}}</span>
    </div>
    <div class="cron-percentual">{{numpercentualconcluidot}}%</div>
    <div class="cron-responsavel" style="width: 17.3% !important;">&nbsp;</div>
    <div class="cron-info">
        <span class="tabulador">
            <span class="badge badge-{{descricaoAtrasoFarol}}"
                  title="{{atrasoCabecalhoFarol}} dias">{{atrasoCabecalhoFarol}} dias</span>
            </span>
    </div>
    <div class="cron-comentario">&nbsp;</div>
</div>