<?php declare(strict_types=1);

namespace kumaomao\kmauth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Contract\MiddlewareInterface;
use Swoft\Http\Server\Router\Route;
use Swoft\Http\Server\Router\Router;
use Swoft\Stdlib\Contract\Arrayable;
use Swoft\View\Renderer;
use Swoft\View\ViewRegister;
use Throwable;
use function context;
use function current;
use function is_object;
use function strpos;

/**
 * Class ViewMiddleware - The middleware of view render
 *
 * @Bean()
 */
class KmauthMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Response $response */
        $response = $handler->handle($request);


        return $response;
    }
}
