<?php
/* CAT:Labels */
/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");

/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints(array(-15, 20, 90), "Risco");
$MyData->setAxisName(0, "Risco");
$MyData->addPoints(array("", "Risco", ""), "Risco");
$MyData->setSerieDescription("", "Risco", "");
$MyData->setAbscissa("Browsers");

/* Create the pChart object */
$myPicture = new pImage(500, 500, $MyData);
$myPicture->drawGradientArea(0, 0, 500, 500, DIRECTION_VERTICAL, array(
    "StartR" => 240,
    "StartG" => 240,
    "StartB" => 240,
    "EndR" => 180,
    "EndG" => 180,
    "EndB" => 180,
    "Alpha" => 100
));
$myPicture->drawGradientArea(0, 0, 500, 500, DIRECTION_HORIZONTAL, array(
    "StartR" => 240,
    "StartG" => 240,
    "StartB" => 240,
    "EndR" => 180,
    "EndG" => 180,
    "EndB" => 180,
    "Alpha" => 20
));
$myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 8));

/* Draw the chart scale */
$myPicture->setGraphArea(20, 30, 480, 480);
$myPicture->drawScale(array(
    "CycleBackground" => true,
    "DrawSubTicks" => true,
    "GridR" => 0,
    "GridG" => 0,
    "GridB" => 0,
    "GridAlpha" => 10,
    "Pos" => SCALE_POS_TOPBOTTOM
));

/* Turn on shadow computing */
$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

/* Create the per bar palette */
$Palette = array(
    "03" => array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 0),
    "07" => array("R" => 92, "G" => 224, "B" => 46, "Alpha" => 100),
    "07" => array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 0)
);

/* Create the per bar palette * /
$Palette = array("0"=>array("R"=>188,"G"=>224,"B"=>46,"Alpha"=>100),
    "01"=>array("R"=>224,"G"=>100,"B"=>46,"Alpha"=>100),
    "02"=>array("R"=>224,"G"=>214,"B"=>46,"Alpha"=>100),
    "03"=>array("R"=>46,"G"=>151,"B"=>224,"Alpha"=>100),
    "04"=>array("R"=>176,"G"=>46,"B"=>224,"Alpha"=>100),
    "05"=>array("R"=>224,"G"=>46,"B"=>117,"Alpha"=>100),
    "06"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
    "07"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
    "08"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
    "09"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
    "10"=>array("R"=>224,"G"=>176,"B"=>46,"Alpha"=>100)
);/**/

/* Draw the chart */
$myPicture->drawBarChart(array(
    "DisplayPos" => LABEL_POS_INSIDE,
    "DisplayValues" => true,
    "Rounded" => true,
    "Surrounding" => 30,
    "OverrideColors" => $Palette
));

/* Write the legend */
$myPicture->drawLegend(570, 215, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

/* Write a label over the chart */
$myPicture->writeLabel("Risco", array(0, 1));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.drawBarChart.vertical.png");
?>