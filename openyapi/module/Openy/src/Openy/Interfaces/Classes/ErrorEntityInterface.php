<?php

namespace Openy\Interfaces\Classes;

use \Openy\Model\Classes\MessageEntity as Message;

interface ErrorEntityInterface
{
	/**
	 * Tells whenever entity has collected errors or not
	 * @return boolean True if has collected errors or False otherwise
	 */
	public function hasErrors();

	/**
	 * Tells whenever entity contains an error identified by given code
	 * @param  String|Int $code The error code
	 * @return boolean          True if error identified with $code has been found or False otherwise
	 */
	public function inErrors($code);

	/**
	 * Returns an array with errors found when processing this entity
	 * @return Array of \Openy\Model\Classes\MessageEntity Collected Error messages
	 */
	public function getErrors();

	/**
	 * Sets the errors found when processing this entity
	 * @param Array of \Openy\Model\Classes\MessageEntity $errors Collected Error messages
	 * @return ErrorEntityInterface The entity with the errors populated
	 */
	public function setErrors($errors);

	/**
	 * Adds a new Error to inner collection
	 * @param Message $error Message explaining error found when processing entity
	 * @return ErrorEntityInterface The entity with the new error
	 */
	public function addError(Message $error);


	/**
	 * Empties the collected errors and leave class with no error feedback
	 * @return ErrorEntityInterface The entity without errors
	 */
	public function clearErrors();




}