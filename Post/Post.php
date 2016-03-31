<?php

namespace App\PostOffice\Post;

use Mail;
use App\Models\Users;

abstract class Post
{
	const ERROR 			= 0;

	const SEND 				= 3;

	const REJECTED 			= -1;

	const INPROGRESS		= 2;

	const PENDING 			= 1;

	protected $user 		= null;

	protected $subject 		= '';

	protected $fromEmail	= '';

	protected $fromName		= '';

	protected $data 		= [];

	private $dir 			= 'posts';

	private $template 		= '';

	public function __construct( Users $user )
	{
		$this->user = $user;
		$this->template = lcfirst( (new \ReflectionClass( $this ))->getShortName() );

		return $this;
	}

	public function send()
	{
		if( $this->condition() ) {
			return self::REJECTED;
		}

		try {

			Mail::send($this->dir.'.'.$this->template, $this->data, function ($m) {
				$m->from( $this->fromEmail, $this->fromName )
				  ->to( $this->user->email, $this->user->name." ".$this->user->surname )
				  ->subject( $this->subject )
				  ->replyTo( $this->fromEmail, $this->fromName );
			});
			$status = self::SEND;

		} catch( Exception $e ) {
			$status = self::ERROR;
		}

		return $status;
	}

	public function setData( array $data )
	{
		$this->data = $data;

		return $this;
	}

	public function getUserId()
	{
		return $this->user->id;
	}

	protected function condition() {}
}