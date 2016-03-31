<?php

namespace App\PostOffice\Post;

use App\Models\Users;

class ExpiredEnunsNotifi extends Post
{
	protected $subject = 'DodajAuto.pl - masz nieaktywne ogłoszenia';

	protected $fromEmail = 'kontakt@dodajauto.pl';

	protected $fromName = 'Portal DodajAuto.pl';

	public function __construct( Users $user )
	{
		parent::__construct( $user );
		$this->data = ['expired' => $user->getEndedEnuns()];
	}

	protected function condition()
	{
		return $this->user->getEndedEnuns() ? false : true;
	}
}

