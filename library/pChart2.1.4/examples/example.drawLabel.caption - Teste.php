<?php
/* CAT:Labels */

/* pChart library inclusions */
include("../class/pData.class.php");
include("../class/pDraw.class.php");
include("../class/pImage.class.php");

$pValor = 400;
$LblSkip = ($pValor <= 100 ? 10 : ($pValor <= 250 ? 45 : 85));
$numcriteriofarol = 100;
$CountItem = 0;
$IniI = ($pValor > 0 ? -15 : $pValor - 10);
$FimI = $pValor + $numcriteriofarol;
for ($i = $IniI; $i <= $FimI; $i++) {
    //for ($i = 0; $i <= 80; $i++) {
    $pointsAbs[] = $i;
    $pointsAbsY[] = 1;
    if ($pValor == $i) {
        $PosItem = $CountItem;
    }
    $CountItem++;
}

/* Create and populate the pData object */
$MyData = new pData();
$MyData->addPoints($pointsAbs, "Prazo 1");
$MyData->addPoints($pointsAbs, "Prazo 2");
$MyData->setAxisName(0, "");
$MyData->setAxisUnit(0, "");
$MyData->addPoints($pointsAbs, "Labels");
$MyData->setAbscissa("Labels");
$MyData->setSerieDrawable("Prazo 1", false);
$MyData->setSerieDrawable("Prazo 2", false);

/* Create the pChart object */
$myPicture = new pImage(230, 230, $MyData);

/* Draw the background */
$Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
$myPicture->drawFilledRectangle(0, 0, 700, 230, $Settings);

/* Overlay with a gradient */
$Settings = array(
    "StartR" => 190,
    "StartG" => 203,
    "StartB" => 107,
    "EndR" => 255,
    "EndG" => 255,
    "EndB" => 255,
    "Alpha" => 50
);
$myPicture->drawGradientArea(0, 0, 700, 220, DIRECTION_VERTICAL, $Settings);
$Settings = array(
    "StartR" => 190,
    "StartG" => 203,
    "StartB" => 107,
    "EndR" => 255,
    "EndG" => 255,
    "EndB" => 255,
    "Alpha" => 50
);
$myPicture->drawGradientArea(0, 222, 700, 350, DIRECTION_VERTICAL, $Settings);

/* Overlay with a gradient */
//$Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
//$myPicture->drawGradientArea(0, 0, 700, 230, DIRECTION_VERTICAL, $Settings);
//$myPicture->drawGradientArea(0, 0, 700, 20, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));


/* Add a border to the picture */
$myPicture->drawRectangle(0, 0, 230, 230, array("R" => 0, "G" => 0, "B" => 0));

/* Write the picture title */
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
$myPicture->drawText(10, 13, "Prazo", array("R" => 0, "G" => 0, "B" => 0));

/* Write the chart title * /
$myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
$myPicture->drawText(155, 55, "Average temperature", array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));/**/

/* Draw the scale and the 1st chart */
$myPicture->setGraphArea(-8, 20, 245, 200);
$myPicture->drawFilledRectangle(8, 20, 245, 190,
    array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
$myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 6));

/* Draw the scale and the 1st chart */
$ScaleSettings = array(
    "Mode" => SCALE_MODE_MANUAL,
    "DrawYLines" => array(0),
    "Pos" => SCALE_POS_LEFTRIGHT,
    "XMargin" => 30,
    "YMargin" => 2,
    "MinDivHeight" => 50,
    "Floating" => true,
    "ManualScale" => $AxisBoundaries,
    "DrawSubTicks" => true,
    "DrawArrows" => true,
    "ArrowSize" => 2,
    "LabelSkip" => $LblSkip,
    "AutoAxisLabels" => true
);
$ScaleSettings = array(
    "LabelSkip" => $LblSkip,
    "DrawYLines" => array(0),
    "Pos" => SCALE_POS_LEFTRIGHT,
    "XMargin" => 20,
    "YMargin" => 10,
    "Floating" => true,
    "DrawSubTicks" => true,
    "DrawArrows" => false,
    "ArrowSize" => 2,
    "AutoAxisLabels" => false
);
$myPicture->drawScale($ScaleSettings);
/**/
//$myPicture->drawScale(array("LabelSkip"=>150,"DrawYLines"=>array(0),"Pos"=>SCALE_POS_LEFTRIGHT));

$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 128, "G" => 128, "B" => 128, "Alpha" => 10));
$myPicture->drawSplineChart();
$myPicture->setShadow(true);

/* Write the chart legend */
$myPicture->drawLegend(50, 50, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

$myPicture->setShadow(true, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
$myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 6));

/* Write a label over the chart */
$LabelSettings = array(
    "DrawVerticalLine" => true,
    "TitleR" => 0,
    "TitleG" => 0,
    "TitleB" => 0,
    "DrawSerieColor" => true,
    "TitleMode" => LABEL_TITLE_BACKGROUND,
    "OverrideTitle" => "Prazo",
    "ForceLabels" => array($pValor),
    "GradientEndR" => 220,
    "GradientEndG" => 255,
    "GradientEndB" => 220,
    "TitleBackgroundG" => 155
);
$myPicture->writeLabel(array("Prazo 1"), $PosItem, $LabelSettings);


/* Render the picture (choose the best way) */
$myPicture->autoOutput("pictures/example.drawLabel.caption.png");
?>