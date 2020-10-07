<?php

class InvalidAccessLevelConfigException extends \Exception {};

class ValidateRouteAccess extends \Quantum\Middleware\Foundation\SystemMiddleware
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
        //dd($user);

        if ($route->has('min_access_level'))
        {
            $minAccessLevel = qs(\QM::config()->getCurrentRouteMinAccessLevel());

            if ($minAccessLevel->isEmpty() || $minAccessLevel->equals('public'))
                return;

            if (empty($user))
                $request->redirect('/login');

            $maxAccessLevel = qs(\QM::config()->getCurrentRouteMaxAccessLevel());

            if ($maxAccessLevel->isEmpty())
                $maxAccessLevel = 'root';

            if ($user->access_level === 'root')
                return;

            if (!$user->is_active)
                $this->getOutput()->displaySystemError('invalid_access_permissions');

            $userLevel = $user->getAccessLevel();

            $access_levels = \Quantum\ActiveAppFileDatabase::get('access_levels');

            foreach ($access_levels as $access_level)
            {
                if ($access_level->name == $minAccessLevel) {
                    $minLevel = $access_level;
                }

                if ($access_level->name == $maxAccessLevel) {
                    $maxLevel = $access_level;
                }
            }

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

            if (empty($user))
                $request->redirect('/login');

            if ($user->access_level === 'root')
                return;

            if (!$user->is_active)
                $this->getOutput()->displaySystemError('invalid_access_permissions');

            foreach ($levels as $level)
            {
                if ($level->name === $user->access_level)
                    return;
            }

            $this->logException('invalid_strict_access_permissions', $route->getUri());
            $this->getOutput()->displaySystemError('invalid_access_permissions');
        }

    }

}