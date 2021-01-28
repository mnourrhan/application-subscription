<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Repositories;

use Flobbos\Crudable\Contracts\Crud;
use Flobbos\Crudable;
use Illuminate\Database\Eloquent\Model;

/**
 * Description of BaseRepository
 *
 * @author Nourhan
 */
class BaseRepository implements Crud
{

    use Crudable\Crudable;

    /**
     *
     * @var Model
     */
    protected $model = null;

    protected function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @author Ahmed Helal Ahmed
     * to gel all methods of model here
     */
    public function __call($name, $arguments)
    {
        return $this->model->$name(...$arguments);
    }

    /**
     *
     * @param  $params
     * @return mixed
     */
    public function pluck(...$params)
    {
        return $this->model->pluck(...$params);
    }

    /**
     * @param $attributes
     * @param $data
     * @return mixed
     */
    public function updateOrCreate($attributes, $data)
    {
        return $this->model->updateOrCreate($attributes, $data);
    }
}
