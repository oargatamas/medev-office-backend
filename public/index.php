<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:22
 */

include "../vendor/autoload.php";

use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Application\MedevApp;


$application = MedevApp::fromJsonFile(__DIR__."/../config/config.json");


$fileService = new OfficeFileService($application);
$fileService->registerService("/drive");


$application->run();
