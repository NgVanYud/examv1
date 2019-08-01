<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Http\Resources\Term\SubjectTermResource;
use App\Http\Resources\Term\TermCollection;
use App\Http\Resources\Term\TermResource;
use App\Repositories\Term\TermRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TermController extends Controller
{
    public $termRepository;

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conditions = [
            'orderBy' => ($request->order_by ? $request->order_by : 'updated_at'),
            'sortDesc' => ($request->sort_desc == 'true' ? 'desc' : 'desc'),
            'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10)
        ];
        $user = auth('manager')->user();
        $terms = [];
        if ($user->hasRole(config('access.roles_list.curator'))) {
          $terms = $this->termRepository
            ->orderBy($conditions['orderBy'], $conditions['sortDesc'])
            ->paginate($conditions['perPage']);
          return TermResource::collection($terms);
        }
        throw new GeneralException('Invalid Data', 403);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTermRequest $request)
    {
        return new TermResource($this->termRepository->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($term)
    {
        return new TermResource($term);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTermRequest $request, $term)
    {
        return new TermResource($this->termRepository->update($term, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
