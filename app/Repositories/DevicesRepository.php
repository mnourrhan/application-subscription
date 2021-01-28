<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Repositories;


use App\Models\Device;

/**
 * Description of DevicesRepository
 *
 * @author Nourhan
 */
class DevicesRepository extends BaseRepository
{
    /**
     * DevicesRepository constructor.
     * @param Device $model
     */
    public function __construct(Device $model)
    {
        parent::__construct($model);
    }
}
