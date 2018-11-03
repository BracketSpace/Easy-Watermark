<?php
/**
 * @copyright:	Wojtek Szałkiewicz
 * @license:	GPLv2 or later
 * 
 * This class is a base class for wordpress plugins.
 * It's a part of package in which you found it.
 * See readme.txt for more information.
 */

class EW_Pluggable
{
	/**
 	 * Adds wordpress action
	 *
	 * @chainable
	 * @param  string  action name
	 * @param  string  function name
	 * @param  integer priority
	 * @param  integer accepted arguments
	 * @return object
	 */
	protected function add_action($actionName, $funcName = null, $priority = 10, $accepted_args = 1){
		add_action($actionName,
			array($this, (!empty($funcName) ? $funcName : $actionName)),
			$priority, $accepted_args);

		return $this;
	}

	/**
 	 * Adds wordpress filter
	 *
	 * @chainable
	 * @param  string  filter name
	 * @param  string  function name
	 * @param  integer priority
	 * @param  integer accepted arguments
	 * @return object
	 */
	protected function add_filter($filterName, $funcName = null, $priority = 10, $accepted_args = 1){
		add_filter($filterName,
			array($this, (!empty($funcName) ? $funcName : $filterName)),
			$priority, $accepted_args);

		return $this;
	}
}
