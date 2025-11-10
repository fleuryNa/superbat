<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

//Helper pour la gestion des uploads
if (!function_exists('calcule_distance')) {
    function calcule_distance($point1,$point2,$unite="km",$precision=2) {
        //recuperation de l'instance de codeigniter
        $ci = & get_instance();
        $degrees = rad2deg(acos((sin(deg2rad($point1["lat"]))*sin(deg2rad($point2["lat"]))) + (cos(deg2rad($point1["lat"]))*cos(deg2rad($point2["lat"]))*cos(deg2rad($point1["long"]-$point2["long"])))));
		// Conversion de la distance en degrés à l'unité choisie (kilomètres, milles ou milles nautiques)
		switch($unite) {
			case 'km':
				$distance = $degrees * 111.13384; // 1 degré = 111,13384 km, sur base du diamètre moyen de la Terre (12735 km)
				break;
			case 'mi':
				$distance = $degrees * 69.05482; // 1 degré = 69,05482 milles, sur base du diamètre moyen de la Terre (7913,1 milles)
				break;
			case 'nmi':
				$distance =  $degrees * 59.97662; // 1 degré = 59.97662 milles nautiques, sur base du diamètre moyen de la Terre (6,876.3 milles nautiques)
		}
		//return array(round($distance, $precision)." ".$unite);
		return array(round($distance, $precision));

                
    }
}
