<?php

use Quantum\Http\HeaderFactory;
use Quantum\PageCache;

/**
 * Class PageCacheMiddleware
 */
class PageCacheMiddleware extends \Quantum\Middleware\Foundation\SystemMiddleware
{
    /**
     * @var
     */
    private $uri;

    /**
     * @param \Quantum\Request $request
     * @param Closure $closure
     * @return mixed|void
     */
    public function handle(\Quantum\Request $request, \Closure $closure)
    {
        $route = \QM::config()->getCurrentRoute();

        if (!$route || !PageCache::isRouteCachable($route))
            return;

        $this->uri = PageCache::getKeyForRoute($route);

        $this->invalidateCacheIfNeeded();

        $this->renderCachedPageIfNeeded();

        if (PageCache::shouldCacheRouteRequest($route))
        {
            ob_start([$this, 'processBuffer']);
        }
    }

    /**
     *
     */
    public function invalidateCacheIfNeeded()
    {
        $request = $this->getRequest();

        if ($request->isPost() || $request->isPut())
        {
            PageCache::flushUriCacheIfNeeded($this->uri);
        }
    }


    /**
     *
     */
    public function renderCachedPageIfNeeded()
    {
        $request = $this->getRequest();

        if (!$request->isGet() && !$request->isHead())
            return;

        $cached_response = \Quantum\PageCache::getCachedHeadersAndContent($this->uri);

        if (!$cached_response)
            return;

        $content = $cached_response['content'];
        $headers = $cached_response['headers'];
        $content_date = $cached_response['content_date'];
        $if_modified_since = $this->getIfModifiedSinceHeader();

        HeaderFactory::setLastModifiedHeader($cached_response['content_date']);

        if ($if_modified_since && ( strtotime( $if_modified_since ) === strtotime( $content_date )))
        {
            header( $this->getRequest()->getServerParam( 'SERVER_PROTOCOL', '' ) . ' 304 Not Modified', true, 304 );
            HeaderFactory::setExpiresHeader( gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
            HeaderFactory::setCacheControlHeader();
            HeaderFactory::setCacheHitHeader('revalidated');
            exit();
        }

        foreach ($headers as $header)
        {
            header($header);
        }

        HeaderFactory::setExpiresHeader($cached_response['content_expiration']);
        HeaderFactory::setCacheControlHeader($cached_response['content_expiration']);
        HeaderFactory::setCacheHitHeader('hit');
        HeaderFactory::setContentTypeHeader($content);
        HeaderFactory::setCacheContentDateHeader($cached_response['content_date']);


        echo $content;
        exit();
    }

    /**
     * @param $buffer
     * @param $phase
     * @return bool
     */
    public function processBuffer($buffer, $phase)
    {
        if ($this->canProcessBuffer($buffer))
        {
            $current_route = \QM::config()->getCurrentRoute();

            $expiration = PageCache::getRouteExpiration($current_route);

            $stored_data = PageCache::store($this->uri, $buffer, $expiration);

            if (isset($stored_data['content']))
            {
                HeaderFactory::setLastModifiedHeader($stored_data['content_date']);

                HeaderFactory::setExpiresHeader($stored_data['content_expiration']);
                HeaderFactory::setCacheControlHeader($stored_data['content_expiration']);
                HeaderFactory::setCacheHitHeader('miss');
                HeaderFactory::setContentTypeHeader($stored_data['content']);
                HeaderFactory::setCacheContentDateHeader($stored_data['content_date']);
            }
        }

        return false;
    }

    /**
     * @param $buffer
     * @return bool
     */
    public function canProcessBuffer($buffer )
    {
        if ( strlen( $buffer ) <= 255 )
        {
            return false;
        }

        if ( http_response_code() !== 200 )
        {
            return false;
        }

        return true;
    }

    /**
     * @return mixed|string
     */
    private function getIfModifiedSinceHeader()
    {
        $headers = $this->getRequest()->getHeaders();

        if (!empty($headers))
            return isset( $headers['If-Modified-Since'] ) ? $headers['If-Modified-Since'] : '';
    }



}