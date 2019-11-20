<?php

class InvalidAccessLevelConfigException extends \Exception {};

class ValidateRouteAccessLevel extends \Quantum\Middleware\Foundation\SystemMiddleware
{

    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = \QM::config()->getCurrentRoute();

        if ($route === false)
            return;

        if ($route->has('strict_access_levels') && $route->has('min_access_level'))
        {
            throw new InvalidAccessLevelConfigException('Route access level must be either strict_access_level or min_access_level');
        }

        if ($route->isMissingParam('strict_access_levels') && $route->isMissingParam('min_access_level'))
        {
            throw new InvalidAccessLevelConfigException('Route access level must be either strict_access_level or min_access_level');
        }

        $user = \Auth::getUserFromSession();

        if ($route->has('min_access_level'))
        {
            $minAccessLevel = qs(\QM::config()->getCurrentRouteMinAccessLevel());

            if ($minAccessLevel->isEmpty() || $minAccessLevel->equals('public'))
                return;


            $client_uri = Quantum\Config::getInstance()->getHostedAppConfig()->getUri();
            $client = \AuthClient::find_by_uri($client_uri);

            if (empty($client))
                throw new InvalidAccessLevelConfigException('Auth Client not found:'.$client_uri);

            if (empty($user))
                $request->redirect($client->getAuthUrl());

            $maxAccessLevel = qs(\QM::config()->getCurrentRouteMaxAccessLevel());

            if ($maxAccessLevel->isEmpty())
                $maxAccessLevel = 'root';

            if ($user->isRoot())
                return;

            if (!$user->isActive())
                $this->getOutput()->displaySystemError('invalid_access_permissions');

            $userLevel = $user->getAccessLevel();
            $minLevel = \AccessLevel::find_by_uri($minAccessLevel);
            $maxLevel = \AccessLevel::find_by_uri($maxAccessLevel);

            $userPriority = $userLevel->priority;
            $minPriority  = $minLevel->priority;
            $maxPriority  = $maxLevel->priority;

            if ($userPriority >= $minPriority && $userPriority <= $maxPriority)
                return;

            $this->logException('invalid_access_permissions', $route->getUri());
            $this->getOutput()->displaySystemError('invalid_access_permissions');
        }

        if ($route->has('strict_access_levels'))
        {
            $levels = \QM::config()->getCurrentRouteStrictAccessLevels();

            if (empty($levels))
                return;

            if (in_array('public', $levels))
                return;

            $client_uri = Quantum\Config::getInstance()->getHostedAppConfig()->getUri();
            $client = \AuthClient::find_by_uri($client_uri);

            if (empty($client))
                throw new InvalidAccessLevelConfigException('Auth Client not found:'.$client_uri);

            if (empty($user))
                $request->redirect($client->getAuthUrl());

            if ($user->isRoot())
                return;

            if (!$user->isActive())
                $this->getOutput()->displaySystemError('invalid_access_permissions');

            foreach ($levels as $level)
            {
                $accessLevel = \AccessLevel::find_by_uri($level);
                if (empty($accessLevel))
                    throw new InvalidAccessLevelConfigException('Access level not not found:'.$level);

                if ($accessLevel->uri === $user->getAccessLevelUri())
                    return;
            }

            $this->logException('invalid_strict_access_permissions', $route->getUri());
            $this->getOutput()->displaySystemError('invalid_access_permissions');
        }

    }

}