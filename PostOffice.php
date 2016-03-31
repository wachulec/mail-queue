<?php
// "mylog"? laravelowy - logi maili na localu
// do singleton
namespace App\PostOffice;

use App\Models\Mails;
use App\PostOffice\Post\Post;

class PostOffice
{
	private $mailbox = null;

	private $postman = null;

	private $limit = 3;

	private $overdueTime = "-2 weeks";

	public function doPostOfficeDuty()
	{
		$this->prepare();

		$postbag = $this->mailbox->retrieve( $this->limit );

		$this->postman->fillPostbag( $postbag );

		$this->postman->deliever();

		$this->refreshMailStats( $this->postman->getProgress() );

		$this->postman->clearPostbag();

		return $this;
	}

	public function cleanOverduePosts()
	{
		$overdues = Mails::where('status', Post::SEND)
		->where('done_at', '<', date('Y-m-d H:i:s', strtotime( $this->overdueTime )))
		->delete();

		return $this;
	}

	private function prepare()
	{
		$this->mailbox = new Mailbox();
		$this->postman = new Postman();

		return $this;
	}

	private function refreshMailStats( $stats )
	{
		$ids = array_keys( $stats );
		$posts = Mails::whereIn('id', $ids)->get();

		foreach( $posts as $post ) {
			$post->status = $stats[ $post->id ];

			if( $post->status == Post::SEND ) {
				$post->done_at = date("Y-m-d H:i:s");
			}

			$post->save();
		}

		return $this;
	}

	public function setLimit( $limit )
	{
		$this->limit = $limit;
		return $this;
	}

	public function setOverdueTime( $stringtime )
	{
		$this->overdueTime = $stringtime;
		return $this;
	}

	public function getOverdueTime()
	{
		return $this->overdueTime;
	}

	public function getMailsByStatus( $status = Post::PENDING )
	{
		return Mails::where('status', $status)->get();
	}
}