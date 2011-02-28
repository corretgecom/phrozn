<?php
/**
 * Copyright 2011 Victor Farazdagi
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at 
 *
 *          http://www.apache.org/licenses/LICENSE-2.0 
 *
 * Unless required by applicable law or agreed to in writing, software 
 * distributed under the License is distributed on an "AS IS" BASIS, 
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 
 * See the License for the specific language governing permissions and 
 * limitations under the License. 
 *
 * @category    Phrozn
 * @package     Phrozn\Site
 * @author      Victor Farazdagi
 * @copyright   2011 Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrozn\Site;
use Phrozn\Site\View;

/**
 * Default implementation of Phrozn Site 
 *
 * @category    Phrozn
 * @package     Phrozn\Site
 * @author      Victor Farazdagi
 */
class DefaultSite 
    extends Base
    implements \Phrozn\Site
{
    /**
     * Create static version of site.
     * Ideally, only parts that changed should be recompiled into Phrozn site.
     *
     * @return void
     */
    public function compile()
    {
        $this
            ->buildQueue()
            ->processQueue();
    }

    /**
     * Process view by view compilation
     *
     * @return \Phrozn\Sitee
     */
    private function processQueue()
    {
        $vars = array();

        foreach ($this->getQueue() as $view) {
            $inputFile = str_replace(getcwd(), '.', $view->getInputFile());
            $outputFile = str_replace(getcwd(), '.', $view->getOutputFile());
            try {
                $destinationDir = dirname($view->getOutputFile());
                if (!is_dir($destinationDir)) {
                    mkdir($destinationDir, 0777, true);
                }
                $view->compile($vars);
                $this->getOutputter()
                    ->stdout('%b' . $inputFile . '%n parsed')
                    ->stdout('%b' . $outputFile . '%n written');
            } catch (\Exception $e) {
                $this->getOutputter()
                     ->stderr($inputFile . ': ' . $e->getMessage());
            }
        }
        return $this;
    }
}
