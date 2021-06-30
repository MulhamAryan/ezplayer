<?php

    class System extends Databases {
        public $config;

        public function __construct()
        {
            parent::__construct();
            global $config;
            $this->config = $config;
        }

        public function getCurrentRendering(){
            $renderingTable = $this->getTable(Databases::renderings);
            $countRedenringJob = $this->sql("SELECT COUNT(id) as current_rendering FROM {$renderingTable} where current_status NOT IN ('error','done') and renderer_id = '{$this->config->rendererid}'","select");
            return $countRedenringJob["current_rendering"];
        }

        public function getRendererInfo(){
            $rendererTable = $this->getTable(Databases::renderers);
            $renderer = $this->sql("SELECT * FROM {$rendererTable} where id = '{$this->config->rendererid}'","select");
            return $renderer;
        }

    }