<?php

include('config.php');

$gantt = new JSONGanttConnector($res, $dbtype);

$gantt->mix("open", 1);
//$gantt->enable_order("sortorder");

//$gantt->render_links("gantt_links", "id", "source,target,type");
//$gantt->render_links("agepnet200.tb_atividadecronograma", "idatividadecronograma", "source,target,type");
$gantt->render_table("agepnet200.tb_atividadecronograma", "idatividadecronograma",
    "datinicio,duration,nomatividadecronograma,numdiasrealizados,idgrupo", "");

?>