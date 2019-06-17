<?php

class Default_Service_PChart2
{

    public $Chart;
    protected $serieSettings;
    protected $myPicture;
    protected $dVertical;
    protected $TextAlign;
    protected $TextValue;
    /* Draw the background */
    protected $BGRValue;
    protected $BGGValue;
    protected $BGBValue;
    protected $DashRValue;
    protected $DashGValue;
    protected $DashBValue;
    protected $StartRValue;
    protected $StartGValue;
    protected $StartBValue;
    protected $EndRValue;
    protected $EndGValue;
    protected $EndBValue;
    protected $AlphaGValue;

    public function Default_Service_PChart2()
    {

        define('_PCHART2_PATH', '../../../library/pChart2.1.4/');

        /* pChart library inclusions */
        include('../../../library/pChart2.1.4/class/pData.class.php');
        include('../../../library/pChart2.1.4/class/pDraw.class.php');
        include('../../../library/pChart2.1.4/class/pImage.class.php');

        $this->Chart = new pData();
        $this->dVertical = DIRECTION_VERTICAL;
        $this->BGRValue = 255;
        $this->BGGValue = 255;
        $this->BGBValue = 255;
        $this->DashRValue = 190;
        $this->DashGValue = 203;
        $this->DashBValue = 107;
        $this->StartRValue = 255;
        $this->StartGValue = 255;
        $this->StartBValue = 255;
        $this->EndRValue = 255;
        $this->EndGValue = 255;
        $this->EndBValue = 255;
        $this->AlphaGValue = 50;

    }

    public function preDispatch()
    {
//        $this->header = $this->view->render('print/header.phtml');
//        $this->footer = $this->view->render('print/footer.phtml');
    }

    /*
     * Default_Service_Impressao
     * @attrib $Values = Array
     * @attrib $SerieName String
     * @return pdf
     */
    function addItens($Values, $SerieName = "SerieName", $SerieDesc = "SerieDesc", $SerieWeight = 1, $SerieTicks = 0)
    {
        /*if (is_array($Values)) {
            foreach ($Values as $Key => $Value) {
                $this->Chart->addPoints($Values, $SerieName);
                $this->Chart->setSerieDescription($SerieName, $SerieDesc);
                $this->Chart->setSerieWeight($SerieName, $SerieWeight);
                $this->Chart->setSerieTicks($SerieName, $SerieTicks);
            }
        } else {*/
        $this->Chart->addPoints($Values, $SerieName);
        $this->Chart->setSerieDescription($SerieName, $SerieDesc);
        $this->Chart->setSerieWeight($SerieName, $SerieWeight);
        $this->Chart->setSerieTicks($SerieName, $SerieTicks);
        //}
    }

    function setPaletteSerie($SerieName = "SerieName", $valueR = 0, $valueG = 0, $valueB = 0, $valueAlpha = 100)
    {
        $this->serieSettings = array("R" => $valueR, "G" => $valueG, "B" => $valueB, "Alpha" => $valueAlpha);
        $this->Chart->setPalette($SerieName, $this->serieSettings);
    }

    function setAbsissa($Values = array("0", "1", "2", "3", "4"), $AbsissaName = "Absissa", $AbsissaDesc = "")
    {
        $this->Chart->setAxisName(0, $AbsissaName);
        $this->Chart->addPoints($Values, $AbsissaName);
        if ($AbsissaDesc != "") {
            $this->Chart->setSerieDescription($AbsissaName, $AbsissaDesc);
        }
        $this->Chart->setAbscissa($AbsissaName);
    }

    function setRectangle($SizeX, $SizeY, $DataChart = null, $TransparentBG = true, $vAntialias = false)
    {
        /* Create the pChart object */
        $this->myPicture = new pImage($SizeX, $SizeY, $DataChart, $TransparentBG);

        /* Turn of Antialiasing */
        $this->setAntialias($vAntialias);
    }

    function setBackGroundRectangle($SizeX, $SizeY)
    {
        /* Draw the background */
        $this->serieSettings = array(
            "R" => $this->BGRValue,
            "G" => $this->BGGValue,
            "B" => $this->BGBValue,
            "Dash" => 1,
            "DashR" => $this->DashRValue,
            "DashG" => $this->DashGValue,
            "DashB" => $this->DashBValue
        );
        $this->myPicture->drawFilledRectangle(0, 0, $SizeX, $SizeY, $this->serieSettings);
    }

    function setBackGradientRectangle($SizeX, $SizeY, $SizeT)
    {
        /* Overlay with a gradient */
        $this->serieSettings = array(
            "StartR" => $this->StartRValue,
            "StartG" => $this->StartGValue,
            "StartB" => $this->StartBValue,
            "EndR" => $this->EndRValue,
            "EndG" => $this->EndGValue,
            "EndB" => $this->EndBValue,
            "Alpha" => $this->AlphaGValue
        );
        $this->myPicture->drawGradientArea(0, 0, $SizeX, $SizeY, $this->dVertical, $this->serieSettings);
        $this->setBGTitle($SizeX, $SizeT, $this->dVertical);

        /* Add a border to the picture */
        $this->myPicture->drawRectangle(1, 1, 468, 194, array("R" => 0, "G" => 0, "B" => 0));

        $this->setShadow(false);
    }

    function setBorderPicture($SizeX, $SizeY)
    {
        /* Add a border to the picture */
        $this->myPicture->drawRectangle(1, 1, $SizeX - 2, $SizeY - 2, array("R" => 0, "G" => 0, "B" => 0));
    }

    function setChartTitle(
        $vText = "",
        $vDirect = DIRECTION_VERTICAL,
        $VFontText = "../fonts/Forgotte.ttf",
        $VSizeFont = 14,
        $PosX,
        $PosY
    ) {
        /* Write the chart title */
        $this->myPicture->setFontProperties(array("FontName" => $VFontText, "FontSize" => $VSizeFont));
        $this->serieSettings = array("Align" => $vDirect, "R" => 0, "G" => 0, "B" => 0);
        $this->myPicture->drawText($PosX, $PosY, $vText, $this->serieSettings);
    }

    function setFontDefault($vFontName = "../fonts/pf_arma_five.ttf", $vFontSize = 6, $vR = 0, $vG = 0, $vB = 0)
    {
        /* Set the default font */
        $this->myPicture->setFontProperties(array(
            "FontName" => $vFontName,
            "FontSize" => $vFontSize,
            "R" => $vR,
            "G" => $vG,
            "B" => $vB
        ));
    }

    function setChartArea($topX = 20, $topY = 20, $bottomX = 400, $bottomy = 180)
    {
        /* Define the chart area */
        $this->myPicture->setGraphArea($topX, $topY, $bottomX, $bottomy);
    }

    function setDrawScale(
        $xMargin = 10,
        $yMargin = 10,
        $vFloat = true,
        $vGrR = 0,
        $vGrG = 0,
        $vGrB = 0,
        $dSubTicks = true,
        $cBGround = true
    ) {
        /* Draw the scale - LINHAS DE GRADE */
        $this->serieSettings = array(
            "XMargin" => $xMargin,
            "YMargin" => $yMargin,
            "Floating" => $vFloat,
            "GridR" => $vGrR,
            "GridG" => $vGrG,
            "GridB" => $vGrB,
            "DrawSubTicks" => $dSubTicks,
            "CycleBackground" => $cBGround
        );
        $this->myPicture->drawScale($this->serieSettings);
    }

    function setDrawLineChar($dValue = true, $vPlotBorder = true, $vSizeB = 2, $vSurrounding = -60, $vBorderAlpha = 80)
    {
        /* Draw the line chart */
        $Config = "";
        $this->myPicture->drawLineChart($Config);
        $this->myPicture->drawPlotChart(array(
            "DisplayValues" => $dValue,
            "PlotBorder" => $vPlotBorder,
            "BorderSize" => $vSizeB,
            "Surrounding" => $vSurrounding,
            "BorderAlpha" => $vBorderAlpha
        ));
    }

    function setWriteCharLegend(
        $PosX = 400,
        $PosY = 35,
        $vFontR = 0,
        $vFontG = 0,
        $vFontB = 0,
        $vFontName = "../fonts/GeosansLight.ttf",
        $vFontSize = 10,
        $vMargin = 166,
        $vAlpha = 130,
        $vBoxSize = 0,
        $vStyle = LEGEND_NOBORDER,
        $vMode = LEGEND_VERTICAL
    ) {
        /* Write the chart legend */
        $Config = array(
            "FontR" => $vFontR,
            "FontG" => $vFontG,
            "FontB" => $vFontB,
            "FontName" => $vFontName,
            "FontSize" => $vFontSize,
            "Margin" => $vMargin,
            "Alpha" => $vAlpha,
            "BoxSize" => $vBoxSize,
            "Style" => $vStyle,
            "Mode" => $vMode
        );

        $this->myPicture->drawLegend($PosX, $PosY, $Config);
    }

    function setShadow($bValue = false)
    {
        /* Enable shadow computing */

        if ($bValue) {
            $this->myPicture->setShadow(true,
                array("X" => 1, "Y" => 1, "R" => 50, "G" => 50, "B" => 50, "Alpha" => 20));
        } else {
            $this->myPicture->setShadow(false);
        }
    }

    function setBGTitle($sX, $sY, $vDirection = DIRECTION_VERTICAL)
    {
        /* Draw the background Of Title  */
        if ($sX > 0 && $sY > 0) {
            $this->serieSettings = array(
                "StartR" => 0,
                "StartG" => 0,
                "StartB" => 0,
                "EndR" => 50,
                "EndG" => 50,
                "EndB" => 50,
                "Alpha" => 12
            );
            $this->myPicture->drawGradientArea(0, 0, $sX, $sY, $vDirection, $this->serieSettings);
        }

    }

    function setVDirection($vDirect = DIRECTION_VERTICAL)
    {
        $this->dVertical = $vDirect;
    }

    function setAntialias($vAntAlias = false)
    {
        /* Change of Antialiasing */
        $this->myPicture->Antialias = $vAntAlias;

    }

    function setRGBSeries($RValue = 255, $GValue = 255, $BValue = 255, $hRValue = 190, $hGValue = 203, $hBValue = 107)
    {
        $this->BGRValue = $RValue;
        $this->BGGValue = $GValue;
        $this->BGBValue = $BValue;
        $this->DashRValue = $hRValue;
        $this->DashGValue = $hGValue;
        $this->DashBValue = $hBValue;
    }

    function setRGBGradiente(
        $RValue = 255,
        $GValue = 255,
        $BValue = 255,
        $hRValue = 190,
        $hGValue = 203,
        $hBValue = 107,
        $hAValue = 50
    ) {
        $this->StartRValue = $RValue;
        $this->StartGValue = $GValue;
        $this->StartBValue = $BValue;
        $this->EndRValue = $hRValue;
        $this->EndGValue = $hGValue;
        $this->EndBValue = $hBValue;
        $this->AlphaGValue = $hAValue;
    }

    function drawOutput()
    {
        $this->myPicture->autoOutput();
        //$this->myPicture->autoOutput("pictures/example.drawLineChart.plots.png");
    }

}

$SizeImageX = 470;
$SizeImageY = 196;
$SizeT = 20;
$BGRValueV = 255;
$BGGValueV = 255;
$BGBValueV = 255;
$DashRValueV = 190;
$DashGValueV = 203;
$DashBValueV = 107;
$StartRValue = 255;
$StartGValue = 255;
$StartBValue = 255;
$EndRValue = 255;
$EndGValue = 255;
$EndBValue = 255;
$AlphaGValue = 50;
$TxtTitle = "% Concluído ( Planejado x Realizado)";
$PosXTitle = 120;
$PosYTitle = 5;


/* Create and populate the pData object */
$servicePChart2 = new Default_Service_PChart2();
$Pontos = array(4, 2.5, 5, 7, 1);
$servicePChart2->addItens($Pontos, $SerieName = "Planejado", $SerieDesc = "Planejado", $SerieWeight = 1,
    $SerieTicks = 0);
$Pontos = array(1, 4, 6, 3, 0);
$servicePChart2->addItens($Pontos, $SerieName = "Realizado", $SerieDesc = "Realizado", $SerieWeight = 1,
    $SerieTicks = 4);
/* Draw serie 1 in red with a 80% opacity */
$servicePChart2->setPaletteSerie($SerieName = "Planejado", $valueR = 128, $valueG = 128, $valueB = 0, $valueAlpha = 80);
/* Affect the same palette on different series */
$servicePChart2->setPaletteSerie($SerieName = "Realizado", $valueR = 0, $valueG = 0, $valueB = 0, $valueAlpha = 100);
/* Set Absissa serie */
$Pontos = array("0", "1", "2", "3", "4");
$servicePChart2->setAbsissa($Pontos, "");
/* Create the pChart object */
$servicePChart2->setRectangle($SizeImageX, $SizeImageY, $servicePChart2->Chart, true, false);
/* Draw the background */
$servicePChart2->setRGBSeries($BGRValueV, $BGGValueV, $BGBValueV, $DashRValueV, $DashGValueV, $DashBValueV);
/* Draw the background */
$servicePChart2->setBackGroundRectangle($SizeImageX, $SizeImageY);
/* Overlay with a gradient */
$servicePChart2->setRGBGradiente($StartRValue, $StartGValue, $StartGValue, $EndRValue, $EndGValue, $EndBValue,
    $AlphaGValue);
$servicePChart2->setBackGradientRectangle($SizeImageX, $SizeImageY, $SizeT);
$servicePChart2->setBorderPicture($SizeImageX, $SizeImageY);
$servicePChart2->setChartTitle($TxtTitle, TEXT_ALIGN_TOPMIDDLE, "../fonts/Forgotte.ttf", 14, $PosXTitle, $PosYTitle);
$servicePChart2->setShadow(false);
/* Set the default font */
$servicePChart2->setFontDefault("../fonts/pf_arma_five.ttf", 6, 0, 0, 0);
/* Define the chart area */
$servicePChart2->setChartArea(20, 20, 400, 180);
/* Draw the scale - LINHAS DE GRADE */
$servicePChart2->setDrawScale(10, 10, true, 0, 0, 0, true, true);
/* Turn on Antialiasing */
$servicePChart2->setAntialias(true);
/* Enable shadow computing */
$servicePChart2->setShadow(true);
/* Draw the line chart */
$servicePChart2->setDrawLineChar(true, true, 2, -60, 80);
/* Write the chart legend */
$servicePChart2->setWriteCharLegend($PosX = 400, $PosY = 35, $vFontR = 0, $vFontG = 0, $vFontB = 0,
    $vFontName = "../fonts/GeosansLight.ttf", $vFontSize = 10, $vMargin = 166, $vAlpha = 130,
    $vBoxSize = 0, $vStyle = LEGEND_NOBORDER, $vMode = LEGEND_VERTICAL);
/* Render the picture (choose the best way) */
$servicePChart2->drawOutput();

?>