<?php

namespace ImagickDemo\Controller;

use Intahwebz\Request;
use ImagickDemo\Response\JsonResponse;
use ImagickDemo\Queue\ImagickTaskQueue;



/**
 * Class Image
 * @package ImagickDemo\Controller
 */
class Image {

    /**
     * @param $category
     * @param $example
     * @param \ImagickDemo\Control $control
     * @param array $customImageParams
     * @return JsonResponse
     */
    function getImageJobStatus(
        $category,
        $example,
        $imageFunction,
        \ImagickDemo\Control $control,
        \ImagickDemo\Example $exampleController
    ) {

        $data = [];
        $customImageParams = $exampleController->getCustomImageParams();
        $fullParams = $control->getFullParams($customImageParams);
        
        $filename = getImageCacheFilename($category, $example, $fullParams);
        $data['filename'] = $filename;
        $data['finished'] = false;
        $data['debug'] = var_export($fullParams, true);
        $data['params'] = $fullParams;
        $data['asdsd'] = var_export($imageFunction, true);

        foreach (getKnownExtensions() as $extension) {
            if (file_exists($filename.'.'.$extension) == true) {
                $data['finished'] = true;
                break;
            }
        }

        return new JsonResponse($data);
    }


    /**
     * @param \Auryn\Provider $injector
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    private function getImageResponseInternal(\Auryn\Provider $injector, $params) {

        $logCallable = function ($imageFunction,
                                 $category,
                                 $example) use ($params) {

            $string  = "[\n";
            $string .= var_export($imageFunction, true);
            $string .= ",\n";
            $string .= var_export($params, true);
            $string .= ",\n";
            $string  .= "],\n";

            file_put_contents("test.data.php", $string, FILE_APPEND);
        };

        $cacheImageFile = function ($imageFunction, 
                                    $category,
                                    $example,
                                    \Auryn\Provider $injector) use ($params) {
            $filename = getImageCacheFilename($category, $example, $params);
            $lowried = [];
            foreach($params as $key => $value) {
                $lowried[':'.$key] = $value;
            }

            return renderImageAsFileResponse($imageFunction, $filename, $injector, $lowried);
        };

        $getCachedImageResponse = function($category, $example) use ($params) {
            return getCachedImageResponse($category, $example, $params);
        };
        
        $processImageTask = function (Request $request,
                                      $imageFunction,
                                      ImagickTaskQueue $taskQueue,
                                      $category, $example) use ($params) {

            $job = $request->getVariable('job', false);
            if ($job === false) {
                if ($taskQueue->isActive() == false) {
                    //Queue isn't active - don't bother queueing a task
                    return null;
                }

                $task = \ImagickDemo\Queue\ImagickTask::create(
                    $category, $example,
                    $imageFunction, $params
                );

                $taskQueue->addTask($task);
            }

            if ($request->getVariable('noredirect') == true) {
                return new \ImagickDemo\Response\ErrorResponse(503, "image still processing.");
            }

            return redirectWaitingTask($request, intval($job));
        };

        global $cacheImages;
        if ($cacheImages == false) {
            $callables = [
                //$logCallable,
                'checkGetOriginalImage',
                'directImageFunction'
            ];
        }
        else {
            $callables = [
                //$logCallable,
                'checkGetOriginalImage',
                $getCachedImageResponse, //This also reads the image when generated by a task
                $processImageTask,
                $cacheImageFile,
                'directImageFunction'
            ];
        }

        foreach ($callables as $callable) {
            $result = $injector->execute($callable);
            if ($result) {
                return $result;
            }
        }

        throw new \Exception("Failed to process image request.");
    }

    /**
     * @param \Auryn\Provider $injector
     */
    function getCustomImageResponse(
        \Auryn\Provider $injector,
        $customImageFunction,
        \ImagickDemo\Example $exampleController,
        \ImagickDemo\Control $control
    ) {
        $injector->defineParam('imageFunction', $customImageFunction);
        $params = $control->getFullParams($exampleController->getCustomImageParams());

        return $this->getImageResponseInternal($injector, $params);
    }

    /**
     * @param Request $request
     * @param \Auryn\Provider $injector
     * @return array|callable
     */
    function getImageResponse(\Auryn\Provider $injector, \ImagickDemo\Control $control) {
        $params = $control->getFullParams([]);

        return $this->getImageResponseInternal($injector, $params);
    }
}