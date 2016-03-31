<?php

namespace App\PostOffice;

use App\PostOffice\Post\Post;

class Postman
{
    private $postbag = [];

    public function deliever()
    {
        foreach( $this->postbag as &$post )  {
            $post['status'] = $post['post']->send();
        }

        return $this;
    }

    public function push( Post $post, $id = null )
    {
        $this->postbag[ $id ] = [
            'post'      => $post, 
            'status'    => Post::INPROGRESS
        ];

        return $this;
    }

    public function fillPostbag( array $postbag )
    {
        $this->postbag =  $postbag;
        return $this;
    }

    public function clearPostbag()
    {
        $this->postbag = [];
        return $this;
    }

    public function getProgress()
    {
        $stats = $this->postbag;
        foreach( $stats as &$post ) {
            $post = $post['status'];
        }

        return $stats;
    }
}