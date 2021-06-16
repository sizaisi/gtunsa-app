<?php
header('Content-type:'.$mime);
header('Content-Disposition:inline; filename='.$nombre_asignado.$extension);

echo $file;