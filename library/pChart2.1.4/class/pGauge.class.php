<?php
/*
    pGauge - class to draw Gauge charts

    Version     : 2.1.4
    Made by     : Jean-Damien POGOLOTTI
    Last Update : 19/01/2014

    This file can be distributed under the license you can find at :

                      http://www.pchart.net/license

    You can find the whole class documentation on the pChart web site.
*/


define("SEGMENT_HEIGHT_AUTO", 690001);

define("GAUGE_LAYOUT_STAR", 690011);
define("GAUGE_LAYOUT_CIRCLE", 690012);

define("GAUGE_LABELS_ROTATED", 690021);
define("GAUGE_LABELS_HORIZONTAL", 690022);

/* pGauge class definition */

class pGauge
{
    var $pChartObject;

    /* Class creator */
    function pGauge()
    {
    }

    /* Draw a Gauge chart */
    function drawGauge($Object, $Values, $Format = "")
    {
        $this->pChartObject = $Object;

        $FixedMax = isset($Format["FixedMax"]) ? $Format["FixedMax"] : VOID;
        $MinAxis = isset($Format["MinAxis"]) ? $Format["MinAxis"] : 0;
        $MaxAxis = isset($Format["MaxAxis"]) ? $Format["MaxAxis"] : 101;
        $AxisR = isset($Format["AxisR"]) ? $Format["AxisR"] : 60;
        $AxisG = isset($Format["AxisG"]) ? $Format["AxisG"] : 60;
        $AxisB = isset($Format["AxisB"]) ? $Format["AxisB"] : 60;
        $AxisAlpha = isset($Format["AxisAlpha"]) ? $Format["AxisAlpha"] : 50;
        $AxisRotation = isset($Format["AxisRotation"]) ? $Format["AxisRotation"] : -49;
        $DrawTicks = isset($Format["DrawTicks"]) ? $Format["DrawTicks"] : true;
        $TicksLength = isset($Format["TicksLength"]) ? $Format["TicksLength"] : 2;
        $DrawBackground = isset($Format["DrawBackground"]) ? $Format["DrawBackground"] : true;
        $BackgroundR = isset($Format["BackgroundR"]) ? $Format["BackgroundR"] : 255;
        $BackgroundG = isset($Format["BackgroundG"]) ? $Format["BackgroundG"] : 255;
        $BackgroundB = isset($Format["BackgroundB"]) ? $Format["BackgroundB"] : 255;
        $BackgroundAlpha = isset($Format["BackgroundAlpha"]) ? $Format["BackgroundAlpha"] : 50;
        $BackgroundGradient = isset($Format["BackgroundGradient"]) ? $Format["BackgroundGradient"] : null;
        $SegmentHeight = isset($Format["SegmentHeight"]) ? $Format["SegmentHeight"] : SEGMENT_HEIGHT_AUTO;
        $WriteLabels = isset($Format["WriteLabels"]) ? $Format["WriteLabels"] : true;
        $LabelPos = isset($Format["LabelPos"]) ? $Format["LabelPos"] : GAUGE_LABELS_ROTATED;
        $LabelPadding = isset($Format["LabelPadding"]) ? $Format["LabelPadding"] : 4;
        $FontSize = $Object->FontSize;
        $X1 = $Object->GraphAreaX1;
        $Y1 = $Object->GraphAreaY1;
        $X2 = $Object->GraphAreaX2;
        $Y2 = $Object->GraphAreaY2;
        /* Cancel default tick length if ticks not enabled */
        if ($DrawTicks == false) {
            $TicksLength = 0;
        }
        /* Data Processing */
        $Data = $Values->getData();
        /* Catch the number of required axis */
        $LabelSerie = $Data["Abscissa"];
        $Points = 0;
        foreach ($Data["Series"] as $SerieName => $DataArray) {
            if (count($DataArray["Data"]) > $Points) {
                $Points = count($DataArray["Data"]);
            }
        }

        /* Draw the axis */
        $CenterX = ($X2 - $X1) / 2 + $X1;
        $CenterY = ($Y2 - $Y1) / 2 + $Y1;

        $EdgeHeight = min(($X2 - $X1) / 2, ($Y2 - $Y1) / 2);
        if ($WriteLabels) {
            $EdgeHeight = $EdgeHeight - $FontSize - $LabelPadding - $TicksLength;
        }

        /* Determine the scale if set to automatic */
        if ($SegmentHeight == SEGMENT_HEIGHT_AUTO) {
            if ($FixedMax != VOID) {
                $Max = $FixedMax;
            } else {
                $Max = 0;
                foreach ($Data["Series"] as $SerieName => $DataArray) {
                    if ($SerieName != $LabelSerie) {
                        if (max($DataArray["Data"]) > $Max) {
                            $Max = max($DataArray["Data"]);
                        }
                    }
                }
            }
        }
        $Axisoffset = 180;
        /* Background processing */
        if ($DrawBackground) {
            $RestoreShadow = $Object->Shadow;
            $Object->Shadow = false;
            if ($BackgroundGradient == null) {
                $Color = array(
                    "R" => $BackgroundR,
                    "G" => $BackgroundG,
                    "B" => $BackgroundB,
                    "Alpha" => $BackgroundAlpha
                );
                $Object->drawFilledCircle($CenterX, $CenterY, $EdgeHeight, $Color);
            } else {
                $Color = array("R" => 100, "G" => 100, "B" => 100, "Alpha" => 88);
                $Object->drawFilledCircle($CenterX, $CenterY, $EdgeHeight - 2, $Color);
                $Color = array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 255);
                $Object->drawFilledCircle($CenterX, $CenterY, $EdgeHeight - 6, $Color);
            }
            $Object->Shadow = $RestoreShadow;
        }

        /* Axis to axis lines */
        $Color = array("R" => $AxisR, "G" => $AxisG, "B" => $AxisB, "Alpha" => $AxisAlpha);
        $Radius = $EdgeHeight;
        // circulo externo
        $Object->drawCircle($CenterX, $CenterY, $Radius, $Radius, $Color);

        /* Axis lines */
        $ID = $MinAxis;
        for ($i = 0; $i <= 280; $i = $i + (280 / 101)) {
            if ($WriteLabels) {
                $LabelX = cos(deg2rad($i + $AxisRotation + $Axisoffset)) * ($EdgeHeight + $LabelPadding + $TicksLength) + $CenterX;
                $LabelY = sin(deg2rad($i + $AxisRotation + $Axisoffset)) * ($EdgeHeight + $LabelPadding + $TicksLength) + $CenterY;
                if ($LabelSerie != "") {
                    $Label = isset($Data["Series"][$LabelSerie]["Data"][$ID]) ? $Data["Series"][$LabelSerie]["Data"][$ID] : "";
                } else {
                    $Label = $ID;
                }
                if (($ID % 10 == 0) || (in_array($ID, $DataArray["Data"]))) {
                    if ($ID % 10 == 0) {
                        if ($LabelPos == GAUGE_LABELS_ROTATED) {
                            $Object->drawText($LabelX, $LabelY, $Label, array(
                                "Angle" => (360 - ($i + $AxisRotation + $Axisoffset)) - 90,
                                "Align" => TEXT_ALIGN_BOTTOMMIDDLE
                            ));
                        } else {
                            if ((floor($LabelX) == floor($CenterX)) && (floor($LabelY) < floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_BOTTOMMIDDLE));
                            }
                            if ((floor($LabelX) > floor($CenterX)) && (floor($LabelY) < floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_BOTTOMLEFT));
                            }
                            if ((floor($LabelX) > floor($CenterX)) && (floor($LabelY) == floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_MIDDLELEFT));
                            }
                            if ((floor($LabelX) > floor($CenterX)) && (floor($LabelY) > floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_TOPLEFT));
                            }
                            if ((floor($LabelX) < floor($CenterX)) && (floor($LabelY) < floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_BOTTOMRIGHT));
                            }
                            if ((floor($LabelX) < floor($CenterX)) && (floor($LabelY) == floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_MIDDLERIGHT));
                            }
                            if ((floor($LabelX) < floor($CenterX)) && (floor($LabelY) > floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_TOPRIGHT));
                            }
                            if ((floor($LabelX) == floor($CenterX)) && (floor($LabelY) > floor($CenterY))) {
                                $Object->drawText($LabelX, $LabelY, $Label, array("Align" => TEXT_ALIGN_TOPMIDDLE));
                            }
                        }
                    }
                    if (in_array($ID, $DataArray["Data"])) {
                        $ColorDotted = array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 100);
                        $Object->drawLine($CenterX, $CenterY, $LabelX, $LabelY, $ColorDotted);
                        $Object->drawArrow($CenterX, $CenterY, $LabelX, $LabelY,
                            array("Size" => 3, "FillR" => 0, "FillG" => 0, "FillB" => 0, "Alpha" => 100));
                    } else {
                        $ColorDotted = array("R" => 255, "G" => 255, "B" => 255, "Alpha" => 100);
                        $Object->drawLine($CenterX, $CenterY, $LabelX, $LabelY, $ColorDotted);
                    }
                }
            }
            $ID++;
        }
        /* Draw a customized filled circles */
        $CircleSettings = array("R" => 0, "G" => 0, "B" => 0, "Alpha" => 88);
        $Object->drawFilledCircle($CenterX, $CenterY, 5, $CircleSettings);
    }
}

?>