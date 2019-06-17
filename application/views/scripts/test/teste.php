<!DOCTYPE html>
<html>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEPNET 2</title>
    <link href="/gepnet/public/js/library/bootstrap/css/bootstrap.min.css" media="screen, projection" rel="stylesheet"
          type="text/css">
    <link href="/gepnet/public/js/library/bootstrap/css/bootstrap-responsive.min.css" media="screen, projection"
          rel="stylesheet" type="text/css">
    <link href="/gepnet/public/css/form.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/gepnet/public/css/portlet.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/gepnet/public/js/library/jquery.jqGrid-4.4.4/css/ui.jqgrid.css" media="screen" rel="stylesheet"
          type="text/css">
    <link href="/gepnet/public/js/library/select2-3.4.0/select2.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/gepnet/public/js/library/jquery-ui-1.8.24.custom/css/custom-theme/jquery-ui-1.8.24.custom.css"
          media="screen" rel="stylesheet" type="text/css">
    <link href="/gepnet/public/js/library/jquery.layout-default.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/gepnet/public/js/library/pnotify-1.2.0/jquery.pnotify.default.css" media="screen" rel="stylesheet"
          type="text/css">
    <link href="/gepnet/public/js/library/pnotify-1.2.0/icons/jquery.pnotify.default.icons.css" media="screen"
          rel="stylesheet" type="text/css">
    <link href="/gepnet/public/js/library/kendoui.web/styles/kendo.common.min.css" media="screen" rel="stylesheet"
          type="text/css">
    <link href="/gepnet/public/js/library/kendoui.web/styles/kendo.bootstrap.min.css" media="screen" rel="stylesheet"
          type="text/css">
    <link href="/gepnet/public/css/layout.css" media="screen, projection" rel="stylesheet" type="text/css">
    <script type="text/javascript"
            src="/gepnet/public/js/library/jquery-ui-1.8.24.custom/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript"
            src="/gepnet/public/js/library/jquery-ui-1.8.24.custom/js/jquery-ui-1.8.24.custom.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/jquery.layout.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/jquery.limit-1.2.source.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/layout.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/select2-3.4.0/select2.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/select2-3.4.0/select2_locale_pt-BR.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/pnotify-1.2.0/jquery.pnotify.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/kendoui.web/js/kendo.core.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/kendoui.web/js/kendo.popup.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/kendoui.web/js/kendo.menu.min.js"></script>
    <script type="text/javascript">
        //<!--

        var base_url = "/gepnet/public";
        //-->
    </script>
    <script type="text/javascript" src="/gepnet/public/js/app/projeto/statusreport/acompanhamento.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/chartjs/Lib/js/knockout-2.2.1.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/chartjs/Lib/js/globalize.min.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/library/chartjs/Lib/js/dx.chartjs.js"></script>
    <script type="text/javascript" src="/gepnet/public/js/app/projeto/statusreport/acompanhamento-chart.js"></script>
</head>

<body>
<div class="ui-layout-center" style="overflow:visible;">
    <h2></h2>

    <div class="region-center">
        <input type="hidden" id="idprojeto" value="13">
        <input type="hidden" id="idst" value="74">
        <input type="hidden" id="risco" value="1">
        <input type="hidden" id="cf" value="30">
        <input type="hidden" id="dm" value="8">

        <div class="row">
            <div class="portlet portlet-cinza span6">
                <div class="title">% Concluído ( Planejado x Realizado)</div>
                <div class="content">
                    <div id="chartcontainer-planejado-realizado" style="max-width:580px;height: 160px;"></div>
                </div>
            </div>
            <div class="portlet portlet-cinza span6">
                <div class="title">Evolução Atraso</div>
                <div class="content">
                    <div id="chartcontainer-atraso" style="max-width:500px;height: 160px;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="portlet portlet-cinza span4">
                <div class="title">Prazo</div>
                <div class="content">
                    <div id="chartcontainer-criteriofarol-atraso" style="max-width:330px;height: 190px;"></div>
                </div>
            </div>
            <div class="portlet portlet-cinza span4">
                <div class="title">Risco</div>
                <div class="content">
                    <div id="chartcontainer-criteriofarol-risco" style="max-width:330px;height: 190px;"></div>
                </div>
            </div>
            <div class="portlet portlet-cinza span4">
                <div class="title">Marco</div>
                <div class="content">
                    <div id="chartcontainer-criteriofarol-marco" style="max-width:330px;height: 190px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<img src="<?php echo base64_decode('R0lGODlhAQABAIABAP///wAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=='); ?>"
     width="0" height="0" alt=""/>
</body>
</html>