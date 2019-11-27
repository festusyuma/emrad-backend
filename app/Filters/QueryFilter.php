<?php

namespace Emrad\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter {

    protected $request;

    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * get all the request in the get method string
     *
     * @return Array $request
     */
    public function filters()
    {
        return $this->request->all();
    }

    /**
     * calls the corresponding function and passes the required parameters on every request
     *
     * @param Builder $builder
     *
     * @return Builder $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }

        return $this->builder;
    }
}
