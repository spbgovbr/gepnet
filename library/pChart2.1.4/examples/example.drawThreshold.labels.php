<?php
/* CAT:Misc */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");

/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints(array(2, 7, 5, 18, VOID, 12, 10, 15, 8, 5, 6, 9), "Help Desk");
$MyData->setAxisName(0, "Incidents");
$MyData->addPoints(array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jui", "Aou", "Sep", "Oct", "Nov", "Dec"), "Labels");
$MyData->setSerieDescription("Labels", "Months");
$MyData->setAbscissa("Labels");

/* Create the pChart object */
$myPicture = new pImage(700, 230, $MyData);
$myPicture->drawGradientArea(0, 0, 700, 230, DIRECTION_VERTICAL,
    array("StartR" => 100, "StartG" => 100, "StartB" => 100, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 100));
$myPicture->drawGradientArea(0, 0, 700, 230, DIRECTION_HORIZONTAL,
    array("StartR" => 100, "StartG" => 100, "StartB" => 100, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 20));
$myPicture->drawGradientArea(0, 0, 60, 230, DIRECTION_HORIZONTAL,
    array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 100));

/* Do some cosmetics */
$myPicture->drawLine(60, 0, 60, 230, array("R" => 70, "G" => 70, "B" => 70));
$myPicture->drawRectangle(0, 0, 699, 229, array("R" => 0, "G" => 0, "B" => 0));
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
$myPicture->drawText(35, 115, "Recorded cases",
    array("R" => 255, "G" => 255, "B" => 255, "FontSize" => 20, "Angle" => 90, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

/* Prepare the chart area */
$myPicture->setGraphArea(100, 30, 680, 190);
$myPicture->drawFilledRectangle(100, 30, 680, 190, array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 20));
$myPicture->setFontProperties(array(
    "R" => 255,
    "G" => 255,
    "B" => 255,
    "FontName" => "../fonts/pf_arma_five.ttf",
    "FontSize" => 6
));
$myPicture->drawScale(array(
    "AxisR" => 255,
    "AxisG" => 255,
    "AxisB" => 255,
    "DrawSubTicks" => true,
    "CycleBackground" => true
));

/* Write two thresholds over the chart */
$myPicture->drawThreshold(10, array("WriteCaption" => true, "Caption" => "Agreed SLA", "NoMargin" => true));
$myPicture->drawThreshold(15, array("WriteCaption" => true, "Caption" => "Best effort", "NoMargin" => true));

/* Draw one static X threshold area */
$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 30));
$myPicture->drawXThresholdArea(3, 5,
    array("AreaName" => "Service closed", "R" => 226, "G" => 194, "B" => 54, "Alpha" => 40));
$myPicture->setShadow(false);

/* Draw the chart */
$myPicture->drawSplineChart();
$myPicture->drawPlotChart(array("PlotSize" => 3, "PlotBorder" => true));

/* Write the data bounds */
$myPicture->writeBounds();

/* Write the chart legend */
$myPicture->drawLegend(630, 215, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.drawThreshold.labels.png");
?>