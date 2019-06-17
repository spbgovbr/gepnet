<?php
/* CAT:Bar Chart */
/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");
include("../class/pIndicator.class.php");

/* Create and populate the pData object */
$MyData = new pData();
$Settings = array("R" => 188, "G" => 224, "B" => 46);
$MyData->addPoints(array(60, 100), "Porcentagens");
$MyData->setAxisName(0, "Percentuais (%)");
$MyData->addPoints(array(" ", " "), "Valores");
$MyData->setAbscissa("Valores");

/* Create the pChart object */
$myPicture = new pImage(320, 180, $MyData);

/* Write the chart title */
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 15));
$myPicture->drawText(20, 25, "Risco do Projeto", array("FontSize" => 12));

/* Define the default font */
$myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 6));

/* Set the graph area */
$myPicture->setGraphArea(20, 40, 320, 180);
$myPicture->drawGradientArea(70, 60, 320, 180, DIRECTION_HORIZONTAL, array(
    "StartR" => 200,
    "StartG" => 200,
    "StartB" => 200,
    "EndR" => 255,
    "EndG" => 255,
    "EndB" => 255,
    "Alpha" => 30
));

/* Draw the chart scale */
$scaleSettings = array(
    "AxisAlpha" => 10,
    "TickAlpha" => 10,
    "DrawXLines" => false,
    "Mode" => SCALE_MODE_START0,
    "GridR" => 0,
    "GridG" => 0,
    "GridB" => 0,
    "GridAlpha" => 10,
    "Pos" => SCALE_POS_TOPBOTTOM
);
$myPicture->drawScale($scaleSettings);

/* Turn on shadow computing */
$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

/* Create the per bar palette */
$Palette = array(
    "0" => array("R" => 188, "G" => 224, "B" => 46, "Alpha" => 100),
    "1" => array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 255)
);

/* Draw the chart */
$myPicture->drawBarChart(array(
    "DisplayValues" => true,
    "DisplayShadow" => true,
    "DisplayPos" => LABEL_POS_INSIDE,
    "Rounded" => true,
    "Surrounding" => 30,
    "OverrideColors" => $Palette,
    "DisplayR" => 255,
    "DisplayG" => 255,
    "DisplayB" => 255
));

/* Create the pIndicator object */
$Indicator = new pIndicator($myPicture);

/* Define the indicator sections */
$IndicatorSections = "";
$IndicatorSections[] = array("Start" => 0, "End" => 33, "Caption" => "Baixo", "R" => 0, "G" => 142, "B" => 176);
$IndicatorSections[] = array("Start" => 34, "End" => 66, "Caption" => "Moderado", "R" => 108, "G" => 157, "B" => 49);
$IndicatorSections[] = array("Start" => 67, "End" => 100, "Caption" => "Alto", "R" => 226, "G" => 74, "B" => 14);

/* Draw the 2nd indicator */
$IndicatorSettings = array(
    "Values" => 60,
    "Unit" => "%",
    "CaptionPosition" => INDICATOR_CAPTION_BOTTOM,
    "CaptionR" => 0,
    "CaptionG" => 0,
    "CaptionB" => 0,
    "DrawLeftHead" => true,
    "ValueDisplay" => INDICATOR_VALUE_LABEL,
    "ValueFontName" => "../fonts/Forgotte.ttf",
    "ValueFontSize" => 15,
    "IndicatorSections" => $IndicatorSections
);
$Indicator->draw(25, 141, 270, 5, $IndicatorSettings);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.drawBarChart.poll.png");
?>