<?php

class Default_Service_PChart2 extends App_Service_ServiceAbstract
{
    public $Chart;
    public $myIndicator;
    public $SplitChart;
    public $myPicture;
    public $serieSettings;
    public $dVertical;
    public $TextAlign;
    public $TextValue;
    public $BGRValue;
    public $BGGValue;
    public $BGBValue;
    public $DashRValue;
    public $DashGValue;
    public $DashBValue;
    public $StartRValue;
    public $StartGValue;
    public $StartBValue;
    public $EndRValue;
    public $EndGValue;
    public $EndBValue;
    public $AlphaGValue;
    public $SizeImageX;
    public $SizeImageY;
    public $SizeT;
    public $BGRValueV;
    public $BGGValueV;
    public $BGBValueV;
    public $DashRValueV;
    public $DashGValueV;
    public $DashBValueV;
    public $PosXTitle;
    public $PosYTitle;

    public function __construct()
    {
        define('_PCHART2_PATH', '../library/pChart2.1.4/');

        /* pChart library inclusions */
        require_once('../library/pChart2.1.4/class/pData.class.php');
        require_once('../library/pChart2.1.4/class/pDraw.class.php');
        require_once('../library/pChart2.1.4/class/pImage.class.php');
        require_once("../library/pChart2.1.4/class/pGauge.class.php");
        require_once("../library/pChart2.1.4/class/pIndicator.class.php");

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
        $this->SizeImageX = 380;
        $this->SizeImageY = 196;
        $this->SizeT = 20;
        $this->BGRValueV = 255;
        $this->BGGValueV = 255;
        $this->BGBValueV = 255;
        $this->DashRValueV = 190;
        $this->DashGValueV = 203;
        $this->DashBValueV = 107;
        $this->StartGValue = 255;
        $this->PosXTitle = 120;
        $this->PosYTitle = 5;
    }

    public function preDispatch()
    {

    }

    function addChart()
    {
        $this->Chart = new pData();
    }

    function addGauge()
    {
        $this->SplitChart = new pGauge();
    }

    function addIndicator()
    {
        $this->myIndicator = new pIndicator($this->myPicture);
    }

    function addItens($Values, $SerieName = "SerieName", $SerieDesc = "SerieDesc", $SerieWeight = 1, $SerieTicks = 0)
    {
        $this->Chart->addPoints($Values, $SerieName);
        $this->Chart->setSerieDescription($SerieName, $SerieDesc);
        $this->Chart->setSerieWeight($SerieName, $SerieWeight);
        $this->Chart->setSerieTicks($SerieName, $SerieTicks);
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

    function setRectangle($SizeX = 380, $SizeY, $DataChart = null, $TransparentBG = true, $vAntialias = false)
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

        /* Add a border to the picture - Verificar */
        //$this->myPicture->drawRectangle(1, 1, $SizeX, $SizeY, array("R" => 0, "G" => 0, "B" => 0));

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
        $VFontText = "Forgotte.ttf",
        $VSizeFont = 14,
        $PosX,
        $PosY
    ) {
        /* Write the chart title */
        $this->myPicture->setFontProperties(array(
            "FontName" => "../library/pChart2.1.4/fonts/" . $VFontText,
            "FontSize" => $VSizeFont
        ));
        $this->serieSettings = array("Align" => $vDirect, "R" => 0, "G" => 0, "B" => 0);
        $this->myPicture->drawText($PosX, $PosY, $vText, $this->serieSettings);
    }

    function setFontDefault($vFontName = "pf_arma_five.ttf", $vFontSize = 6, $vR = 0, $vG = 0, $vB = 0)
    {
        /* Set the default font */
        $this->myPicture->setFontProperties(array(
            "FontName" => "../library/pChart2.1.4/fonts/" . $vFontName,
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
        $vFontName = "GeosansLight.ttf",
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
            "FontName" => "../library/pChart2.1.4/fonts/" . $vFontName,
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

    function setDrawIndicator($vX = 25, $vY = 141, $vA = 270, $vB = 5, $IndicatorSettings)
    {
        $this->myIndicator->draw($vX, $vY, $vA, $vB, $IndicatorSettings);
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

    function drawOutput($MyImage = "CharImage")
    {
        $this->myPicture->autoOutput($MyImage);
    }

    function criaImagem()
    {
        /* Render the picture (choose the best way) */
        $this->myPicture->autoOutput();
    }

} ?>