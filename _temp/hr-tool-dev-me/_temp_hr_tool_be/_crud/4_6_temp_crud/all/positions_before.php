<?php
$listKeySearch=['parent.title','positions.title','positions.description'];
if(!empty($params['requestor']))
{
    $requestor= explode('-',$params['requestor']);
    unset($params['requestor']);
}
?>