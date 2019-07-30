<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 30.
 * Time: 11:04
 */

namespace MedevOffice\Services\File\Middleware;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\ValidatePermission;
use MedevOffice\Services\File\FileService;
use MedevSlim\Core\Service\APIService;
use Slim\Http\Request;
use Slim\Http\Response;

class PermissionChecker
{
    /**
     * @var APIService
     */
    private $service;

    /**
     * @var array
     */
    private $requiredPermissions;

    /**
     * PermissionChecker constructor.
     * @param APIService $service
     * @param string[] $requiredPermissions
     */
    public function __construct(APIService $service, $requiredPermissions)
    {
        $this->service = $service;
        $this->requiredPermissions = $requiredPermissions;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var OAuthToken $authToken */
        $authToken = $request->getAttribute(OAuthService::AUTH_TOKEN);
        $routeArgs = $request->getAttribute("routeInfo")[2];

        $userId = $authToken->getUser()->getIdentifier();
        $itemId = $routeArgs[FileService::FILE_ID] ?? $routeArgs[FileService::FOLDER_ID];

        (new ValidatePermission($this->service))->handleRequest([
            ValidatePermission::USER_ID => $userId,
            ValidatePermission::ITEM_ID => $itemId,
            ValidatePermission::PERMISSIONS => $this->requiredPermissions
        ]);

        return $next($request, $response);
    }
}