<?php
/*
* \file index.php
* Supress direct acceess to the title icon directory.
*
* \author PortaMx - Portal Management Extension
* \author Copyright 2008-2017 by PortaMx - https://www.portamx.com
*/

if(file_exists(realpath('../../../../Settings.php')))
{
	require(realpath('../../../../Settings.php'));
	header('Location: ' . $boardurl);
}
else
	exit;
?>
