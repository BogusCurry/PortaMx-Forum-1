<?php
/*
* \file index.php
* Supress direct acceess to the title icon directory.
*
* \author PortaMx - Portal Management Extension
* \author Copyright 2008-2017 by PortaMx - http://portamx.com
* \version Virgo 2.0 Beta 4
* \date 05.04.2016
*/

if(file_exists(realpath('../../../../Settings.php')))
{
	require(realpath('../../../../Settings.php'));
	header('Location: ' . $boardurl);
}
else
	exit;
?>
