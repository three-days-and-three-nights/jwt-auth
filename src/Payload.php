<?php


namespace thans\jwt;

use thans\jwt\claim\Factory;
use thans\jwt\claim\Issuer;
use thans\jwt\claim\Audience;
use thans\jwt\claim\Expiration;
use thans\jwt\claim\IssuedAt;
use thans\jwt\claim\JwtId;
use thans\jwt\claim\NotBefore;
use thans\jwt\claim\Subject;

class Payload
{
    protected $classMap = [
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
        ];

    protected $claims;

    public function __construct(protected Factory $factory)
    {
    }

    public function customer(array $claim = [])
    {
        foreach ($claim as $key => $value) {
            $this->factory->customer(
                $key,
                is_object($value) ? $value->getValue() : $value
            );
        }

        return $this;
    }

    public function get()
    {
        $claim = $this->factory->builder()->getClaims();
        
        return $this->toPlainArray($claim);
    }

    public function check($refresh = false)
    {
        $this->factory->validate($refresh);

        return $this;
    }

    public function toPlainArray($claim)
    {
        return (new \think\Collection($claim))->map(function ($item) {
            return $item->getValue();
        })->toArray();
    }
}
