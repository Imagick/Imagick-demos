<?php

namespace ImagickDemo\Queue;

use Auryn\InjectionException;
use ImagickDemo\App;
use ImagickDemo\ImageCachePath;
use ImagickDemo\Navigation\CategoryInfo;
use Room11\HTTP\VariableMap\ArrayVariableMap;

class ImagickTaskRunner
{
    /**
     * @var \Auryn\Injector
     */
    private $injector;

    // <namespace>.<instrumented section>.<target (noun)>.<action (past tense verb)>.<measure (noun)>
    //
    // const event_imageGenerated = "phpimagick.imagickTask.image.generated.timeTaken";
    // const event_imageGenerated = "phpimagick.imagickTask.image.exception";

    // Both should have '.time' appended.
    const EVENT_IMAGE_GENERATED = "phpimagick.imagickTask.image.generated";
    const EVENT_PAGE_GENERATED = "phpimagick.imagickTask.page.generated";

    /**
     * @var TaskQueue
     */
    private $taskQueue;

    private $asyncStats;

    private $endTime;

    /**
     * @param ImagickTaskQueue $taskQueue
     * @param \Auryn\Injector $injector
     * @param \Stats\AsyncStats $asyncStats
     */
    public function __construct(
        ImagickTaskQueue $taskQueue,
        \Auryn\Injector $injector,
        \Stats\AsyncStats $asyncStats,
        ImageCachePath $imageCachePath
    ) {
        $this->taskQueue = $taskQueue;
        $this->injector = $injector;
        $this->asyncStats = $asyncStats;
        $this->imageCachePath = $imageCachePath;
        $this->endTime = $this->calculateEndTime();
    }

    private function calculateEndTime()
    {
        if (false) {
            // ImageMagick has a 'non-optimal' way of measuring time passed
            // https://github.com/ImageMagick/ImageMagick/issues/113
            // Currently it does not appear possible to have both protection
            $maxRunTime = 60; // one minute
            $maxRunTime *= 60; // 1hour
    
            // Each image generated hurries up the restart by 50 seconds
            // for a max of 72 images generated per run
            $taskPseudoTime = 50;
            $resetTimeResourceLimit = true;
        // End rant
        }
        else {
            //Start remove this when time limit can be controlled better
            $maxRunTime = \Imagick::getResourceLimit(\Imagick::RESOURCETYPE_TIME); // one minute
            if ($maxRunTime <= 10) {
                $maxRunTime = 45;
            }
        }
        
        $maxRunTime = 30;
        
        return time() + $maxRunTime;
    }

    /**
     *
     */
    public function run()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        \ImagickDemo\Imagick\functions::load();
        \ImagickDemo\ImagickDraw\functions::load();
        \ImagickDemo\ImagickPixel\functions::load();
        \ImagickDemo\ImagickKernel\functions::load();
        \ImagickDemo\ImagickPixelIterator\functions::load();
        \ImagickDemo\Tutorial\functions::load();

        continuallyExecuteCallable(
            [$this, 'actuallyRun'],
            0,
            1,
            600
        );
    }
    
    public static function tickTock()
    {
        static $count = 0;
        echo ".";
        $count += 1;
        if (($count % 20) == 0) {
            echo "\n";
        }
        $count = 0;
    }
    
    /**
     *
     */
    public function actuallyRun()
    {
//        echo "ImagickTaskRunner::actuallyRun\n";
        try {
//            echo "Waiting for task "."\n";
            $task = $this->taskQueue->waitToAssignTask();
        }
        catch (QueueException $qe) {
            echo "QueueException running the task: ".$qe->getMessage()."\n";
            return;
        }

        if (!$task) {
            self::tickTock();
            usleep(100000); //Sleep for 1/10th of a second
            return;
        }

        echo "A task!\n";

        try {
            $startTime = microtime(true);
            $this->execute($task);
            $time = microtime(true) - $startTime;
            $this->asyncStats->recordTime(self::EVENT_IMAGE_GENERATED, $time);
            echo "Task complete\n";
            $this->taskQueue->completeTask($task);
        }
        catch (\ImagickException $ie) {
            echo "ImagickException running the task: " . $ie->getMessage();
            $this->taskQueue->errorTask($task, get_class($ie).": ".$ie->getMessage());
            echo getExceptionText($ie);
        }
        catch (\Auryn\InjectorException $bae) {
            //Log failed job
            echo "InjectorException running the task: " . $bae->getMessage();
            $this->taskQueue->errorTask($task, get_class($bae).": ".$bae->getMessage());
        }
        catch (\Exception $e) {
            echo "Exception running the task: " . $e->getMessage();
            $this->taskQueue->errorTask($task, get_class($e).": ".$e->getMessage());
        }
    }


    private function execute(ImagickTask $task)
    {
        $pageInfo = $task->getPageInfo();
        $params = $task->getParams();
        $filename = $this->imageCachePath->getImageCacheFilename($pageInfo, $params);
        $imageTypes = ['jpg', 'gif', 'png'];

        echo "file base name is $filename\n";
        foreach ($imageTypes as $imageType) {
            $fullFilename = $filename . "." . $imageType;
            if (file_exists($fullFilename) == true) {
                echo "File $fullFilename already exists - skipping generation\n";
                return;
            }
        }

        $injector = clone $this->injector;
        
        $lowried = [];
        foreach ($params as $key => $value) {
            $lowried[':' . $key] = $value;
        }

        $variableMap = new ArrayVariableMap($params);
        $injector->alias('Room11\HTTP\VariableMap', get_class($variableMap));
        $injector->share($variableMap);

        if ($task->isCustomImage()) {
            $imageFunction = CategoryInfo::getCustomImageFunctionName($pageInfo);
        }
        else {
            $imageFunction = CategoryInfo::getImageFunctionName($pageInfo);
        }
        
        $controlClassName = CategoryInfo::getControlClassName($pageInfo);
        if ($controlClassName) {
            $injector->alias('ImagickDemo\Control', $controlClassName);
        }

        echo "Image Function name is: \n";
        var_dump($imageFunction);

        try {
            $result = App::renderImageAsFileResponse($imageFunction, $filename, $injector, $lowried);
            echo "file written: $filename \n";
        }
        catch (InjectionException $ie) {
            echo "InjectionException calling image function: ".var_export($imageFunction, true)."\n";
            echo "Details: ".$ie->getMessage()."\n";
            var_dump($ie->getDependencyChain());
        }
    }
}
