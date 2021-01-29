<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * Description of BaseRequestValidator
 *
 * @author Nourhan
 */
abstract class BaseRequestValidator
{

    /**
     *
     * @var array
     */
    private $errors = [];

    /**
     *
     * @var Validator
     */
    protected $validator = null;

    /**
     *
     * @var array
     */
    private $rules = [];

    /**
     *
     * @var array
     */
    private $messages = [];

    /**
     * Base request validator constructor
     *
     */
    public function __construct()
    {
        $this->rules = $this->rules();
        $this->messages = $this->messages();
    }

    /**
     * Validate function that implements the validation logic
     *
     * @param Request $request
     * @return \self
     */
    public function validate(Request $request): self
    {
        $this->validator = Validator::make($request->all(), $this->rules, $this->messages);

        return $this;
    }

    /**
     * Check if that validation is failed
     *
     * @return bool
     */
    public function failed(): bool
    {
        if (!isset($this->validator)) {
            $this->validate(app('request'));
        }

        return $this->validator->fails();
    }

    /**
     * Return validation errors
     *
     * @return array
     */
    public function errors(): array
    {
        // return $this->errors = $this->formatErrors($this->validator->errors()->messages());

        return $this->validator->errors()->messages();
    }

    /**
     * Rerun errors in formatted array with filed and message
     *
     * @param array $errors
     * @return array
     */
    private function formatErrors(array $errors): array
    {
        $formattedErrors = [];


        foreach ($errors as $field => $messages) {
            $inputErrors = array_map(function ($message) use ($field) {
                return [
                    'field' => $field,
                    'message' => $message,
                ];
            }, $messages);

            $formattedErrors = array_merge($inputErrors, $formattedErrors);
        }

        return $formattedErrors;
    }

    /**
     * The validation rules
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Validation messages
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
        ];
    }

    /**
     * Append new rule to the validation rules
     *
     * @param string $filed The new field
     * @param string $rule  The new rule
     */
    protected function appendRule(string $filed, string $rule)
    {
        $this->rules[$filed] = $rule;
    }
}
