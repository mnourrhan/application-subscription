<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services;

use App\Repositories\DevicesRepository;

/**
 * Description of RegisteringDeviceService
 *
 * @author Nourhan
 */
class RegisteringDeviceService
{
    /**
     *
     * @var DevicesRepository
     */
    private $repo = null;

    public function __construct(DevicesRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param $attributes
     * @param $data
     * @return mixed
     */
    public function execute($attributes, $data)
    {
        return $this->repo->updateOrCreate($attributes, $data);
    }
}
