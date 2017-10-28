<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 28.10.17
 * Time: 16.36
 */

namespace Everywhere\Api\Auth;


use Everywhere\Api\Contract\Auth\AuthenticationAdapterInterface;
use Everywhere\Api\Contract\Integration\AuthRepositoryInterface;
use Zend\Authentication\Adapter\Callback;

class AuthenticationAdapter extends Callback implements AuthenticationAdapterInterface
{
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->setCallback(function() use($authRepository) {
            return $authRepository->authenticate(
                $this->getIdentity(),
                $this->getCredential()
            );
        });
    }
}