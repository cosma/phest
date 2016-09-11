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

abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
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