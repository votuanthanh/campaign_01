<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Exceptions\Api\UnknowException;

class ForgotPasswordController extends ApiController
{
    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $this->doAction(function () use ($response) {
            $this->linkResponse($response);
        });
    }

    protected function linkResponse($response)
    {
        $message = trans($response);

        if ($response == Password::INVALID_USER) {
            throw new UnknowException($message);
        }

        $this->compacts['message'] = trans($message);
    }
}
