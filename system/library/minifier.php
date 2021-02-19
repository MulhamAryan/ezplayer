<?php
    require "minify/matthiasmullie-minify/src/Minify.php";
    class Minifier{

        private $filecontent;

        public function fileGetContents($filedir){
            if(file_exists($filedir)){
                try{
                    $content = file_get_contents($filedir);
                    if($content != false){
                        return $content;
                    }
                    else{
                        exit();
                    }
                }catch(Exception $e){
                    exit();
                }
            }
        }
        public function js($filedir)
        {
            require "minify/matthiasmullie-minify/src/JS.php";

            $content = $this->fileGetContents($filedir);
            try {
                $minifier = new MatthiasMullie\Minify\JS($content);
                return $minifier->minify();
            } catch (Exception $e) {
                ob_end_clean();
                return $e->getMessage();
            }
        }

        public function css($filedir){
            require "minify/matthiasmullie-minify/src/CSS.php";
            require "minify/matthiasmullie-pathconverter/src/ConverterInterface.php";
            require "minify/matthiasmullie-pathconverter/src/Converter.php";

            $content = $this->fileGetContents($filedir);
            try {
                $minifier = new MatthiasMullie\Minify\CSS($content);
                return $minifier->minify();
            } catch (Exception $e) {
                return $e->getMessage();
            }

        }
    }