<?php

class view {

        private $output = '';
        
        private $bencmark = 0;
        
        private $cache = array();

        function __construct() {
                
        }

        public function load($file, $template = '') {
                $start = microtime(true);
                if (!empty($template)) {
                        if(!empty($this->cache[$file]))
                        {
                                $this->output = str_replace('{' . strtoupper($template) . '}', $this->cache[$file], $this->output);
                        }
                        else if (file_exists("view/" . $file . ".html")) {
                                ob_start();
                                include("view/" . $file . ".html");
                                $content = ob_get_contents();
                                ob_end_clean();
                                $this->output = str_replace('{' . strtoupper($template) . '}', $content, $this->output);
                                $this->cache[$file] = $content;
                        }
                } else {
                        if (file_exists("view/" . $file . ".html")) {
                                $this->output .= file_get_contents("view/" . $file . ".html");
                        }
                }
                $this->benchmark += ((microtime(true)-$start)*1000);
        }

        public function replace($template, $value) {
                if (!is_array($value))
                        $this->output = str_replace('{' . strtoupper($template) . '}', $value, $this->output);
        }

        public function replace_array($array) {
                foreach ($array as $key => $value) {
                        if (!is_array($value))
                                $this->replace($key, $value);
                }
        }

        public function cleanup($template) {
                $this->output = preg_replace('/\{' . $template . '(\w*)\}/', '', $this->output);
        }

        public function __destruct() {
                echo $this->output;
        }

}

?>