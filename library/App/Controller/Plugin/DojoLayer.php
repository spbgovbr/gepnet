<?php

class App_Controller_Plugin_DojoLayer extends Zend_Controller_Plugin_Abstract
{
    public $layerScript;
    public $buildProfile;
    protected $_build;

    public function dispatchLoopShutdown()
    {
        $this->layerScript = APPLICATION_PATH . '/../public/js/custom/main.js';
        $this->buildProfile = APPLICATION_PATH . '/../misc/scripts/custom.profile.js';
        /*
        Zend_Debug::dump($layerScript);
        Zend_Debug::dump(file_exists($this->layerScript));
        exit;
        */
        if (!file_exists($this->layerScript)) {
            $this->generateDojoLayer();
        }

        if (!file_exists($this->buildProfile)) {
            $this->generateBuildProfile();
        }
    }

    public function getBuild()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->initView();
        if (null === $this->_build) {
            $this->_build = new Zend_Dojo_BuildLayer(array(
                'view' => $viewRenderer->view,
                'layerName' => 'custom.main',
                'consumeJavascript' => false,
            ));
        }
        return $this->_build;
    }

    public function generateDojoLayer()
    {
        $build = $this->getBuild();
        $layerContents = $build->generateLayerScript();
        if (!is_dir(dirname($this->layerScript))) {
            mkdir(dirname($this->layerScript));
        }
        file_put_contents($this->layerScript, $layerContents);
    }

    public function generateBuildProfile()
    {
        $profile = $this->getBuild()->generateBuildProfile();
        if (!is_dir(dirname($this->buildProfile))) {
            mkdir(dirname($this->buildProfile));
        }
        file_put_contents($this->buildProfile, $profile);

        $builtbat = realpath(APPLICATION_PATH . '/../public/js/dojo-1.4.3/util/buildscripts/build.bat');
        exec($builtbat . ' 
        	profileFile=' . $this->buildProfile . ' 
        	action=release
            layerOptimize=shrinksafe
            stripConsole=all 
            cssOptimize=comments', $captured_output, $return_value);
        /*
        exec( $ME['base_url'].'lib/dojo/dojo/util/buildscripts/build.sh
                        profileFile='.$profilename.'
                        action=release
                        layerOptimize=shrinksafe
                        stripConsole=all
                        releaseDir='.$ME['base_dir'].'lib/dojo/release/
                        cssOptimize=comments
                        ', $captured_output, $return_value);
        */

        //$VIEW->dojo()->addLayer($layername);
    }
}