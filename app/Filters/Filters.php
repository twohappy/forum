<?php
/**
 * Created by PhpStorm.
 * User: twohappy
 * Date: 2017/7/1
 * Time: 下午1:48
 */

namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request, $builder;
    protected $filters = [];

    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $builder;
    }

    public function getFilters()
    {
        return $this->request->intersect($this->filters);
    }
}