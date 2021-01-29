<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Validators;

/**
 * Description of PurchaseValidator
 *
 * @author Nourhan
 */
class PurchaseValidator extends BaseRequestValidator
{

    /**
     * Validation rules
     *
     * @return array
     */
    protected function rules(): array {
        return [
            'receipt' => ['required', 'string']
        ];
    }

}
