<?php

/*
 * 		Smarty Plugin
 * -------------------------------------------------------------------------
 * 	File: 			function.meal_name.php
 *	Type:			function
 *	Name: 			mealName
 *	Description:	Take a numeric value and returns the corresponding meal name. 	
 * -------------------------------------------------------------------------
 *
 * Example usage:
 *
 * {meal_name($id)}
 * 
 */



	function return_meal_name($id, &$smarty) {
		if ($id == 1) {
			return "Breakfast";
		} elseif ($id == 2) {
			return "Lunch";
		} elseif ($id == 3) {
			return "Dinner";
		}

		return false;
	}