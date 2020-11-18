<?php

class RecaptchaV3Middleware  extends \Quantum\Middleware\Foundation\SystemMiddleware
{
    public function __construct()
    {
        $this->frontend_key = get_overridable_route_setting('recatpchav3_frontend_key');

        $this->backend_key = get_overridable_route_setting('recatpchav3_backend_key');

        $this->getOutput()->set('recaptcha_key', $this->frontend_key);

        $this->setJavascript();
    }

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        if (!$request->hasPostParam('recaptcha_response'))
            return;

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = $this->backend_key;
        $recaptcha_response = $request->getPostParam('recaptcha_response');

        $recaptcha = qurl($recaptcha_url)
                    ->withParameter('secret', $recaptcha_secret)
                    ->withParameter('response', $recaptcha_response)
                    ->readEntireTextStream();

        $recaptcha = json_decode($recaptcha);

        if ($recaptcha->success === true) {
            to_runtime_registry('recaptchav3_validated', 'yes');
        }
        else
        {
            \ExternalErrorLoggerService::error('recaptcha_validation_error', ['response' => $recaptcha]);
        }

    }

    private function setJavascript()
    {
        $this->getOutput()->set('recaptchav3_js', '<script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                                    <script>
                                                        function onSubmit(token) {
                                                            document.getElementById("recaptcha_response").value = token;
                                                            document.getElementById("login_form").submit();
                                                        };
                                                    </script>');
    }

}