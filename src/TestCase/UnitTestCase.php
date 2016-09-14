<?php
/**
 * This file is part of the "cosma/phest" project
 *
 * (c) Cosmin Voicu<cosmin.voicu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Cosma\Phest\TestCase;

use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Di\ServiceInterface;

abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DiInterface
     */
    protected $di;

    protected function setUp()
    {
        parent::setUp();

        $di = Di::getDefault();

        if (!($di instanceof DiInterface)) {
            throw new \PHPUnit_Framework_IncompleteTestError(
                'Di::getDefault() should return a Phalcon\DiInterface object.'
            );
        }

        $this->setDi($di);
    }

    /**
     * Check if the test case is setup properly
     *
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!($this->getDi() instanceof DiInterface)) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run Cosma\Phest\TestCase\UnitTestCase::setUp()');
        }
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }

    /**
     * @return DiInterface
     */
    protected function getDi()
    {
        return $this->di;
    }

    /**
     * @param DiInterface $di
     */
    protected function setDi( DiInterface $di)
    {
        $this->di = $di;
    }


    /**
     * @param $serviceName
     * @param $mock
     *
     * @return ServiceInterface
     */
    protected function mockService($serviceName, $mock)
    {
        return $this->getDi()->set($serviceName, $mock);
    }

    /**
     * @return mixed|string
     */
    protected function getTestClassPath()
    {
        $testClassPath = false;

        $debugTrace = debug_backtrace();

        if (isset($debugTrace[0]['file'])) {
            $testPath      = strpos($debugTrace[0]['file'], "src/", 1);
            $filePath      = substr($debugTrace[0]['file'], $testPath + 4);
            $testClassPath = str_replace('.php', '', $filePath);
        }

        return $testClassPath;
    }

    /*
     * {@inheritdoc}
     *
     */
    public function runBare()
    {
        for ($i = 0; $i <= $this->getNumberOfRetries(); $i++) {
            try {
                if ($i > 0) {
                    //purple on yellow background colour
                    echo "\033[35m\033[43mR\033[0m";
                }
                parent::runBare();

                return;
            } catch (\Exception $exception) {
            }
        }
        if (isset($exception) && $exception) {
            throw $exception;
        }
    }

    /**
     * @return int
     */
    private function getNumberOfRetries()
    {
        $annotations = $this->getAnnotations();

        if (isset($annotations['method']['retry'])) {
            if (
                isset($annotations['method']['retry'][0]) &&
                is_numeric($annotations['method']['retry'][0])

            ) {
                return $annotations['method']['retry'][0];
            }

            return 1;
        }

        if (isset($annotations['class']['retry'])) {
            if (
                isset($annotations['class']['retry'][0]) &&
                is_numeric($annotations['class']['retry'][0])

            ) {
                return $annotations['class']['retry'][0];
            }

            return 1;
        }

        return 0;
    }
}