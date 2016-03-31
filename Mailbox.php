<?php

namespace App\PostOffice;

use App\Models\Mails;
use App\Models\Users;
use App\PostOffice\Post\Post;
use Exception;

class Mailbox
{
    public function retrieve( $amount )
    {
        $rows = Mails::where('status', 1)
                ->orderBy( 'created_at', 'asc' )
                ->take( $amount )
                ->get();
 
        $posts = [];

        foreach( $rows as $row ) {
            $status = 2; //in progress

            $posts[ $row->id ] = [
                'post'      => $this->createMailObject( $row ),
                'status'    => $status
            ];

            $row->status = $status;
            $row->save();
        }

        return $posts;
    }

    public function throwMany( array $posts )
    {
        foreach( $posts as $post) {
            $this->throwSingle( $post );
        }

        return $this;
    }

    public function throwSingle( Post $post )
    {
        $ref = new \ReflectionClass( $post );

        $mail          = new Mails();
        $mail->mail    = $ref->getName();
        $mail->status  = Post::PENDING;
        $mail->user_id = $post->getUserId();
        $mail->save();

        return $this;
    }

    private function createMailObject( $row )
    {
        $ref = new \ReflectionClass( $row->mail );
        $obj = $ref->newInstance( Users::findOrFail( $row->user_id ) );
        
        return $obj;
    }
}