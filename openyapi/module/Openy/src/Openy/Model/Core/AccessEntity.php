<?php

namespace Openy\Model\Core;


class AccessEntity
{
	/**
	 * Unique identifier for an access
	 * @var (Unsigned) Int
	 */
	public $idremoteaccess;

	/**
	 * Timestamp for the access
	 * @var Timestamp
	 */
	public $time;
	/**
	 * IP or IPv6 of an access performed against openy
	 * @var String
	 */
	public $ip;

	/**
	 * Client behind the access performed
	 * @var Client
	 */
	public $client;

	/**
	 * Access Token
	 */
	public $token;
}