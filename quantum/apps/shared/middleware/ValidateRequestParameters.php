<?php


class ValidateRequestParameters extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $post = $request->getRawPost();
        $get = $request->getRawGet();
        //dd($get);

        foreach ($post as $key => $value)
        {
            $param = WafRequestParam::getParam($key, $value, 'POST');
            if ($param->isBlacklisted())
                $this->blockAccess($param);
        }

        foreach ($get as $key => $value)
        {
            $param = WafRequestParam::getParam($key, $value, 'GET');
            if ($param->isBlacklisted())
                $this->blockAccess($param);

        }


    }

    private function blockAccess($param)
    {
        $this->logException('blacklisted_param', $param->sample);

        $this->getOutput()->displaySystemError('500');
    }

}