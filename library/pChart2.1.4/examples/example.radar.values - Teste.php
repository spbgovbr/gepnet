<?php
/* CAT:Polar and Gauges */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pGauge.class.php");
include("../class/pImage.class.php");

$Ponto = 315;
$WidHeigImg = 190;
/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints(array($Ponto), "Ponto");
$MyData->setSerieDescription("Ponto", "Ponto A");

/* Create the pChart object */
$myPicture = new pImage($WidHeigImg, ($WidHeigImg - 7), $MyData);

$myPicture->drawGradientArea(0, 0, $WidHeigImg, $WidHeigImg, DIRECTION_VERTICAL,
    array(
        "StartR" => 255,
        "StartG" => 255,
        "StartB" => 255,
        "EndR" => 255,
        "EndG" => 255,
        "EndB" => 255,
        "Alpha" => 100
    ));
$myPicture->drawLine(0, 20, $WidHeigImg, 20, array("R" => 255, "G" => 255, "B" => 255));
$RectangleSettings = array("R" => 180, "G" => 180, "B" => 180, "Alpha" => 100);

/* Add a border to the picture */
$myPicture->drawGradientArea(0, 0, $WidHeigImg, $WidHeigImg, DIRECTION_VERTICAL, array(
    "StartR" => 240,
    "StartG" => 240,
    "StartB" => 240,
    "EndR" => 180,
    "EndG" => 180,
    "EndB" => 180,
    "Alpha" => 100
));
$myPicture->drawGradientArea(0, 0, $WidHeigImg, $WidHeigImg, DIRECTION_HORIZONTAL, array(
    "StartR" => 240,
    "StartG" => 240,
    "StartB" => 240,
    "EndR" => 180,
    "EndG" => 180,
    "EndB" => 180,
    "Alpha" => 20
));
$myPicture->drawRectangle(0, 0, 699, 229, array("R" => 0, "G" => 0, "B" => 0));


/* Set the default font properties */
$myPicture->setFontProperties(array(
    "FontName" => "../fonts/Forgotte.ttf",
    "FontSize" => 12,
    "R" => 80,
    "G" => 80,
    "B" => 80
));

/* Enable shadow computing */
$myPicture->setShadow(false, array("X" => 2, "Y" => 2, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

/* Create the pGauge object */
$SplitChart = new pGauge();

/* Draw a Gauge chart */
$myPicture->setGraphArea(0, 5, $WidHeigImg, $WidHeigImg);
$Options = array(
    "DrawPoly" => true,
    "WriteValues" => true,
    "ValueFontSize" => 8,
    "Layout" => GAUGE_LAYOUT_CIRCLE,
    "Segments" => 1,
    "FixedMax" => 180,
    "MinAxis" => ($Ponto < 0 ? $Ponto - 15 : ($Ponto < 280 ? 0 : ($Ponto - 80))),
    "BackgroundGradient" => array(
        "StartR" => 255,
        "StartG" => 255,
        "StartB" => 255,
        "StartAlpha" => 400,
        "EndR" => 255,
        "EndG" => 255,
        "EndB" => 255,
        "EndAlpha" => 0
    )
);

$SplitChart->drawGauge($myPicture, $MyData, $Options);

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.gauge.values.png");
?>