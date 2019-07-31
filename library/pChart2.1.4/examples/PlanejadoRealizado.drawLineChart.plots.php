<?php
/* CAT:Area Chart */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");

/* Create and populate the pData object */
$Chart = new pData();

$Chart->addPoints(array(4, 2.5, 5, 7, 1), "Planejado");
$Chart->setSerieDescription("Planejado", "Planejado");
$Chart->setSerieWeight("Planejado", 1);

$Chart->addPoints(array(1, 4, 6, 3, 0), "Realizado");
$Chart->setSerieDescription("Realizado", "Realizado");
$Chart->setSerieWeight("Realizado", 1);
$Chart->setSerieTicks("Realizado", 4);

/* Draw serie 1 in red with a 80% opacity */
$serieSettings = array("R" => 128, "G" => 128, "B" => 128, "Alpha" => 80);
$Chart->setPalette("Planejado", $serieSettings);

/* Affect the same palette on different series */
$serieSettings = array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 100);
$Chart->setPalette("Realizado", $serieSettings);

$Chart->setAxisName(0, "");
$Chart->addPoints(array("0", "1", "2", "3", "4"), "Absissa");
$Chart->setAbscissa("Absissa");

/* Create the pChart object */
$myPicture = new pImage(470, 196, $Chart, true);

/* Turn of Antialiasing */
$myPicture->Antialias = false;

/* Draw the background */
$Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
$myPicture->drawFilledRectangle(0, 0, 470, 195, $Settings);

/* Overlay with a gradient */
$Settings = array(
    "StartR" => 255,
    "StartG" => 255,
    "StartB" => 255,
    "EndR" => 255,
    "EndG" => 255,
    "EndB" => 255,
    "Alpha" => 50
);
$myPicture->drawGradientArea(0, 0, 470, 195, DIRECTION_VERTICAL, $Settings);

$myPicture->drawGradientArea(0, 0, 470, 20, DIRECTION_VERTICAL,
    array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 12));

/* Add a border to the picture */
$myPicture->drawRectangle(1, 1, 468, 194, array("R" => 0, "G" => 0, "B" => 0));

/* Write the chart title */
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 14));
$TextSettings = array("Align" => TEXT_ALIGN_TOPMIDDLE, "R" => 0, "G" => 0, "B" => 0);
$myPicture->drawText(120, 5, "% Concluído ( Planejado x Realizado)", $TextSettings);

$myPicture->setShadow(false);

/* Set the default font */
$myPicture->setFontProperties(array(
    "FontName" => "../fonts/pf_arma_five.ttf",
    "FontSize" => 6,
    "R" => 0,
    "G" => 0,
    "B" => 0
));

/* Define the chart area */
$myPicture->setGraphArea(20, 20, 400, 180);

/* Draw the scale - LINHAS DE GRADE */
$scaleSettings = array(
    "XMargin" => 10,
    "YMargin" => 10,
    "Floating" => true,
    "GridR" => 0,
    "GridG" => 0,
    "GridB" => 0,
    "DrawSubTicks" => true,
    "CycleBackground" => true
);
$myPicture->drawScale($scaleSettings);

/* Turn on Antialiasing */
$myPicture->Antialias = true;

/* Enable shadow computing */
$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 50, "G" => 50, "B" => 50, "Alpha" => 20));

/* Draw the line chart */
$Config = "";
$myPicture->drawLineChart($Config);
$myPicture->drawPlotChart(array(
    "DisplayValues" => true,
    "PlotBorder" => true,
    "BorderSize" => 2,
    "Surrounding" => -60,
    "BorderAlpha" => 80
));

/* Write the chart legend */
$Config = array(
    "FontR" => 0,
    "FontG" => 0,
    "FontB" => 0,
    "FontName" => "../fonts/GeosansLight.ttf",
    "FontSize" => 10,
    "Margin" => 166,
    "Alpha" => 130,
    "BoxSize" => 0,
    "Style" => LEGEND_NOBORDER,
    "Mode" => LEGEND_VERTICAL
);

$myPicture->drawLegend(400, 35, $Config);

/* Render the picture (choose the best way) */
//$myPicture->autoOutput("pictures/example.drawLineChart.plots.png");
$myPicture->autoOutput("../pictures/example.drawLineChart.plots.png");
?>