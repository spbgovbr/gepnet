<?php
/* CAT:Pie charts */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pPie.class.php");
include("../class/pImage.class.php");

$PontoRisco = 35;
$PontoTotal = 100 - $PontoRisco;
/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints(array($PontoTotal, $PontoRisco), "ScoreA");
$MyData->setSerieDescription("ScoreA", "Application A");

/* Define the absissa serie */
$MyData->addPoints(array("", "Risco"), "Labels");

$MyData->setAbscissa("Labels");

/* Associate the two series to create a scatter serie */
$MyData->setScatterSerie("ScoreA", "Labels");

/* Mark the scatter serie as non-drawable */
$MyData->setScatterSerieDrawable(1, false);

/* Create the pChart object */
$myPicture = new pImage(300, 260, $MyData);

/* Draw a solid background */
$Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
$myPicture->drawFilledRectangle(0, 0, 300, 300, $Settings);

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
$myPicture->drawGradientArea(0, 0, 300, 260, DIRECTION_VERTICAL, $Settings);
$myPicture->drawGradientArea(0, 0, 300, 20, DIRECTION_VERTICAL,
    array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 100));

/* Add a border to the picture */
$myPicture->drawRectangle(0, 0, 299, 259, array("R" => 0, "G" => 0, "B" => 0));

/* Write the picture title */
$myPicture->setFontProperties(array("FontName" => "../fonts/Silkscreen.ttf", "FontSize" => 6));
$myPicture->drawText(10, 13, "pPie - Draw 2D ring charts", array("R" => 255, "G" => 255, "B" => 255));

/* Set the default font properties */
$myPicture->setFontProperties(array(
    "FontName" => "../fonts/Forgotte.ttf",
    "FontSize" => 10,
    "R" => 80,
    "G" => 80,
    "B" => 80
));

/* Enable shadow computing */
$myPicture->setShadow(true, array("X" => 2, "Y" => 2, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 50));

/* Create the pPie object */
$PieChart = new pPie($myPicture, $MyData);

/* Draw an AA pie chart */
$PieChart->draw2DRing(160, 140,
    array("WriteValues" => true, "ValueR" => 255, "ValueG" => 255, "ValueB" => 255, "Border" => true));

/* Write the legend box */
$myPicture->setShadow(true);
$PieChart->drawPieLegend(15, 40, array("Alpha" => 20));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.draw2DRingValue.png");
?>