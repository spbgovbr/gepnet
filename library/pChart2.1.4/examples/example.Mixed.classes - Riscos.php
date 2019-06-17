<?php
/* CAT:Combo */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");
include("../class/pIndicator.class.php");

$PontoItem = 10;
$numcriteriofarol = 80;

$IniI = ($PontoItem > 0 ? -15 : $PontoItem);
$FimI = $PontoItem + $numcriteriofarol;

$WidthGraph = 700;
$LengthGraph = 350;
$pointsAbs = array();

/* Create and populate the pData object */
$MyData = new pData();
for ($i = $IniI; $i <= $FimI; $i++) {
//for ($i = 0; $i <= 80; $i++) {
    $pointsAbs[] = $i;
    if ($PontoItem == $i) {
        $PosItem = $CountItem;
    }
    $CountItem++;
}
$MyData->addPoints($pointsAbs, "Prazo do Projeto");
$MyData->addPoints($pointsAbs, "Prazo");
$MyData->setAbscissa("Prazo");
//$MyData->setAxisName(0, "Probability");
//$MyData->setAxisUnit(0, "%");

/* Create the pChart object */
$myPicture = new pImage(700, 350, $MyData);

/* Turn of Antialiasing */
$myPicture->Antialias = false;

/* Draw the background */
$Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
$myPicture->drawFilledRectangle(0, 0, 700, 350, $Settings);

/* Overlay with a gradient */
$Settings = array(
    "StartR" => 219,
    "StartG" => 231,
    "StartB" => 139,
    "EndR" => 1,
    "EndG" => 138,
    "EndB" => 68,
    "Alpha" => 50
);
$myPicture->drawGradientArea(0, 0, 700, 220, DIRECTION_VERTICAL, $Settings);
$Settings = array(
    "StartR" => 1,
    "StartG" => 138,
    "StartB" => 68,
    "EndR" => 219,
    "EndG" => 231,
    "EndB" => 239,
    "Alpha" => 50
);
$myPicture->drawGradientArea(0, 222, 700, 350, DIRECTION_VERTICAL, $Settings);

/* Add a border to the picture */
$myPicture->drawRectangle(0, 0, 699, 349, array("R" => 0, "G" => 0, "B" => 0));

/* Set the default font */
$myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 6));

/* Define the chart area */
$myPicture->setGraphArea(60, 40, 320, 180);

/* Draw the scale * /
$scaleSettings = array("XMargin" => 10, "YMargin" => 10, "Floating" => TRUE, "LabelSkip" => 4, "GridR" => 120, "GridG" => 120, "GridB" => 120, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
//$scaleSettings = array("AxisAlpha"=>10,"TickAlpha"=>10,"DrawXLines"=>FALSE,"Mode"=>SCALE_MODE_START0,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10,"Pos"=>SCALE_POS_TOPBOTTOM);
$myPicture->drawScale($scaleSettings);
/**/
/* Draw the scale */
$AxisBoundaries = array(0 => array("Min" => -15, "Max" => 380));
$ScaleSettings = array(
    "Mode" => SCALE_MODE_MANUAL,
    "ManualScale" => $AxisBoundaries,
    "DrawSubTicks" => true,
    "DrawArrows" => true,
    "ArrowSize" => 6,
    "LabelSkip" => 15,
    "AutoAxisLabels" => true
);
$myPicture->drawScale($ScaleSettings);

/* Turn on Antialiasing */
$myPicture->Antialias = true;

/* Draw the line of best fit */
//$myPicture->drawBestFit(array("Ticks" => 4, "Alpha" => 50, "R" => 0, "G" => 0, "B" => 0));

/* Draw the line chart */
//$myPicture->drawLineChart();

/* Draw the series derivative graph */
//$myPicture->drawDerivative(array("ShadedSlopeBox" => TRUE, "CaptionLine" => TRUE));

/* Write the chart legend */
$myPicture->drawLegend(570, 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

/* Set the default font & shadow settings */
$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));

/* Write the chart title */
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
$myPicture->drawText(150, 55, "Probability of heart disease",
    array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE, "R" => 255, "G" => 255, "B" => 255));

/* Write a label over the chart */
//$LabelSettings = array("DrawVerticalLine" => TRUE, "TitleMode" => LABEL_TITLE_BACKGROUND, "TitleR" => 255, "TitleG" => 255, "TitleB" => 255);
//$myPicture->writeLabel("Prazo do Projeto", 55, $LabelSettings);

/* Write a label over the chart */
$LabelSettings = array(
    "DrawVerticalLine" => true,
    "TitleR" => 255,
    "TitleG" => 255,
    "TitleB" => 255,
    "DrawSerieColor" => true,
    "TitleMode" => LABEL_TITLE_BACKGROUND,
    "OverrideTitle" => "Prazo",
    "ForceLabels" => $PontoItem,
    "GradientEndR" => 220,
    "GradientEndG" => 255,
    "GradientEndB" => 220,
    "TitleBackgroundG" => 155
);
$myPicture->writeLabel(array("Prazo do Projeto"), $PosItem, $LabelSettings);

/* Create the pIndicator object * /
$Indicator = new pIndicator($myPicture);

/* Define the indicator sections * /
$IndicatorSections = "";
$IndicatorSections[] = array("Start" => 0, "End" => 29, "Caption" => "Low", "R" => 0, "G" => 142, "B" => 176);
$IndicatorSections[] = array("Start" => 30, "End" => 49, "Caption" => "Moderate", "R" => 108, "G" => 157, "B" => 49);
$IndicatorSections[] = array("Start" => 50, "End" => 80, "Caption" => "High", "R" => 226, "G" => 74, "B" => 14);

/* Draw the 2nd indicator * /
$IndicatorSettings = array("Values" => $PontoItem, "Unit" => "%", "CaptionPosition" => INDICATOR_CAPTION_BOTTOM, "CaptionR" => 0, "CaptionG" => 0, "CaptionB" => 0, "DrawLeftHead" => TRUE, "ValueDisplay" => INDICATOR_VALUE_LABEL, "ValueFontName" => "../fonts/Forgotte.ttf", "ValueFontSize" => 15, "IndicatorSections" => $IndicatorSections);
$Indicator->draw(60, 275, 580, 30, $IndicatorSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.mixed.png");
?>