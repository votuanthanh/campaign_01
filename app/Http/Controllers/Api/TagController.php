<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\TagInterface;

class TagController extends ApiController
{
    protected $tagRepository;

    public function __construct(TagInterface $tagRepository)
    {
        parent::__construct();
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->doAction(function () {
            $this->compacts['tags'] = $this->tagRepository->simplePaginate();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->doAction(function () use ($id) {
            $this->compacts['tag'] = $this->tagRepository->findOrFail($id);
        });
    }
}
