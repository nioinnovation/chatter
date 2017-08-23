<?php

namespace DevDojo\Chatter\Events;

use Illuminate\Http\Request;

class ChatterAfterEditResponse
{
    /**
     * @var Request
     */
    public $request;

    /**
     * @var Models::post()
     */
    public $post;

    /**
     * Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request, $post)
    {
        $this->request = $request;

        $this->post = $post;
    }
}
