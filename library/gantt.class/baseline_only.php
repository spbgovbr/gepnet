<?php

/*******************************************************************************
 * gantt php class example and configuration file
 * this example shows a initial planned chart
 * and dependencies
 * version 0.1
 * Copyright (C) 2005 Alexandre Miguel de Andrade Souza
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License.
 * Please see the accompanying file COPYING for licensing details!
 *
 * If you need a commercial license of this class to your project, please contact
 * alexandremasbr@gmail.com
 *******************************************************************************/
include 'gantt.class.php';


//generic  definitions to graphic, you dont need to change this. Only if you want
$definitions['planned']['y'] = 0;
$definitions['planned']['height'] = 8;
$definitions['planned_adjusted']['y'] = 19;
$definitions['planned_adjusted']['height'] = 9;
$definitions['real']['y'] = 21;
$definitions['real']['height'] = 5;
$definitions['img_bg_color'] = array(204, 204, 255);
$definitions['title_color'] = array(255, 255, 255);
$definitions['title_bg_color'] = array(0, 0, 128);
$definitions['milestone']['title_bg_color'] = array(204, 204, 230);
$definitions['today']['color'] = array(204, 204, 0);
$definitions['real']['hachured_color'] = array(204, 0, 0);//red
$definitions['workday_color'] = array(255, 255, 255); //white -> default color of the grid
$definitions['grid_color'] = array(218, 218, 218);
$definitions['groups']['color'] = array(0, 0, 0);//black
$definitions['groups']['bg_color'] = array(180, 180, 180);//grey
$definitions['planned']['color'] = array(255, 0, 0);//green
$definitions['planned_adjusted']['color'] = array(0, 0, 204); //blue
$definitions['real']['color'] = array(255, 255, 255);//red
$definitions['progress']['color'] = array(255, 255, 255); // white
$definitions['dependency_color'][END_TO_START] = array(0, 0, 0);//black
$definitions['dependency_color'][START_TO_START] = array(0, 0, 0);//black
$definitions['dependency_color'][END_TO_END] = array(0, 0, 0);//black
$definitions['dependency_color'][START_TO_END] = array(0, 0, 0);//black
$definitions['planned']['legend'] = 'INITIAL PLANNING';
$definitions['planned_adjusted']['legend'] = 'ADJUSTED PLANNING';
$definitions['real']['legend'] = 'REALIZED';
$definitions['progress']['legend'] = 'PROGRESS';
$definitions['milestone']['legend'] = 'MILESTONE';
$definitions['today']['legend'] = 'TODAY';
$definitions['limit']['cell']['m'] = '4'; // size of cells (each day)
$definitions['limit']['cell']['w'] = '8'; // size of cells (each day)
$definitions['limit']['cell']['d'] = '20';// size of cells (each day)
$definitions['grid']['x'] = 180; // initial position of the grix (x)
$definitions['grid']['y'] = 40; // initial position of the grix (y)
$definitions['row']['height'] = 40; // height of each row
$definitions['legend']['y'] = 35; // initial position of legent (height of image - y)
$definitions['legend']['x'] = 150; // distance between two cols of the legend
$definitions['legend']['y_'] = 35; //distance between the image bottom and legend botton
$definitions['legend']['ydiff'] = 20; //diference between lines of legend
$definitions['text_font'] = 3; //define the font to text -> 1 to 4 (gd fonts)
$definitions['title_font'] = 3;  //define the font to title -> 1 to 4 (gd fonts)
$definitions['milestones']['color'] = array(204, 204, 50);


//global definitions to graphic 
// change to you project data
//legends
$definitions['planned']['legend'] = 'INITIAL PLANNING';
$definitions['planned_adjusted']['legend'] = 'PLANEJADO AJUSTADO';
$definitions['real']['legend'] = 'REALIZADO';
$definitions['progress']['legend'] = 'PORCENTAGEM CONCLU*DA';
//personalize your project
$definitions['title_string'] = "projeto x";
$definitions['locale'] = "pt_BR";
$definitions['limit']['detail'] = 'd'; //w weak, m month , d day
$definitions['limit']['start'] = mktime(0, 0, 0, 12, 1, 2004); //these settings will define the size of
$definitions['limit']['end'] = mktime(0, 0, 0, 3, 29, 2005); //graphic and time limits

// use loops to define these variables with database data
// you need to set groups to graphic be created
$definitions['groups']['group'][0]['name'] = "phase 1";
$definitions['groups']['group'][0]['start'] = mktime(0, 0, 0, 12, 2, 2004);
$definitions['groups']['group'][0]['end'] = mktime(0, 0, 0, 3, 14, 2005);

// you need to set a group to every phase to show it rigth
$definitions['groups']['group'][0]['phase'][0] = 0;
$definitions['groups']['group'][0]['phase'][1] = 1;

//you have to set planned phase name even when show only planned adjusted
$definitions['planned']['phase'][0]['name'] = 'tarefa b';
$definitions['planned']['phase'][0]['start'] = mktime(0, 0, 0, 12, 2, 2004);
$definitions['planned']['phase'][0]['end'] = mktime(0, 0, 0, 1, 14, 2005);
/*$definitions['planned_adjusted']['phase'][0]['start'] = mktime(0,0,0,12,2,2004);
$definitions['planned_adjusted']['phase'][0]['end'] = mktime(0,0,0,1,18,2005);
$definitions['real']['phase'][0]['start'] = mktime(0,0,0,12,28,2004);
$definitions['real']['phase'][0]['end'] = mktime(0,0,0,1,20,2005);
$definitions['progress']['phase'][0]['progress']=70;*/

$definitions['planned']['phase'][1]['name'] = 'tarefa xyz';
$definitions['planned']['phase'][1]['start'] = mktime(0, 0, 0, 1, 14, 2005);
$definitions['planned']['phase'][1]['end'] = mktime(0, 0, 0, 2, 23, 2005);
/*$definitions['planned_adjusted']['phase'][1]['start'] = mktime(0,0,0,1,20,2005);
$definitions['planned_adjusted']['phase'][1]['end'] = mktime(0,0,0,2,25,2005);
$definitions['real']['phase'][1]['start'] = mktime(0,0,0,1,23,2005);
$definitions['real']['phase'][1]['end'] = mktime(0,0,0,2,27,2005);
$definitions['progress']['phase'][1]['progress']=30;*/


//dependencies to planned array
$definitions['dependency_planned'][0]['type'] = END_TO_START;
$definitions['dependency_planned'][0]['phase_from'] = 0;
$definitions['dependency_planned'][0]['phase_to'] = 1;

$definitions['dependency_planned'][1]['type'] = START_TO_START;
$definitions['dependency_planned'][1]['phase_from'] = 0;
$definitions['dependency_planned'][1]['phase_to'] = 1;

$definitions['dependency_planned'][2]['type'] = END_TO_END;
$definitions['dependency_planned'][2]['phase_from'] = 0;
$definitions['dependency_planned'][2]['phase_to'] = 1;

$definitions['dependency_planned'][3]['type'] = START_TO_END;
$definitions['dependency_planned'][3]['phase_from'] = 0;
$definitions['dependency_planned'][3]['phase_to'] = 1;
/*
//dependencies to planned adjusted array
$definitions['dependency'][0]['type']= END_TO_START; 
$definitions['dependency'][0]['phase_from']=0;
$definitions['dependency'][0]['phase_to']=1;

$definitions['dependency'][1]['type']= START_TO_START; 
$definitions['dependency'][1]['phase_from']=0;
$definitions['dependency'][1]['phase_to']=1;

$definitions['dependency'][2]['type']= END_TO_END; 
$definitions['dependency'][2]['phase_from']=0;
$definitions['dependency'][2]['phase_to']=1;

$definitions['dependency'][3]['type']= START_TO_END; 
$definitions['dependency'][3]['phase_from']=0;
$definitions['dependency'][3]['phase_to']=1;*/
$definitions['milestones']['milestone'][0]['data'] = mktime(0, 0, 0, 2, 25, 2005);
$definitions['milestones']['milestone'][0]['title'] = 'product done';
$definitions['groups']['group'][0]['milestone'][0] = 0; //need to set a group to show

//generate the graphic
$gt = new gantt($definitions);

// change size of image
// now draw ;)
//  $gt->draw();


?>
