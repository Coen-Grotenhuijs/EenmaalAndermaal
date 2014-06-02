<?php

include("core/session.php");

class control extends session {

        protected $view;
        protected $get = array();
        public $post = array();

        public function __construct() {
                // sessie model laden
                $this->loadModel("session");

                // model dat hoort bij de huidige control laden
                $classname = get_class($this);
                $this->loadModel(str_replace("Control", "", $classname));


                // view aanmaken
                $this->view = new view();

                // HTML template laden
                if ($classname != 'ajaxControl')
                {
                        $this->loadView("template");
                }

                // huidige URL bepalen
                $urltemp = explode('/', $_SERVER["REQUEST_URI"]);
                $urltemp2 = explode('?', $urltemp[count($urltemp) - 1]);
                $url = $urltemp2[0];

                // request data filteren
                $this->filterrequestdata();

                // sessie constructor uitvoeren
                parent::__construct();

                // Ingelogd
                if ($this->logged_in) {
                        $this->loadView('header/loggedin', 'topbar');
                } else {
                        $this->loadView('header/loggedout', 'topbar');
                }
                
                // footer
                $this->loadView('footer/footer', 'footer');

                // Inlog formulier errors weergeven
                if (!empty($this->loginform)) {
                        $this->view->replace_array($this->loginform->geterrors());
                        $this->view->replace_array($this->loginform->getclasses());
                }
                
                // Rubrieken dropdown
                
                $this->loadModel('rubrieken');
                
                $rubrieken = $this->rubriekenModel->getRubrieken();
                
                foreach($rubrieken as $key=>$value)
                {
                        $this->loadView('header/rubriek','next_rubriek_dropdown');
                        $this->replaceView('rubriek_naam', $value['Naam']);
                        $this->replaceView('rubriek_nummer', $value['Nummer']);
                }
                $this->replaceView('next_rubriek_dropdown', '');
                
                $this->replaceView('aantalbiedingen','');
                
        }

        public function filterrequestdata() {
                foreach ($_GET as $key => $value) {
                        $this->get[$key] = $this->filter($value);
                }
                foreach ($_POST as $key => $value) {
                        $this->post[$key] = $this->filter($value);
                }
        }

        private function filter($data) {
                return str_replace("'", "", $data);
        }

        public function loadModel($model) {
                if (file_exists("model/" . $model . ".php")) {
                        include("model/" . $model . ".php");
                        $classname = $model . "Model";
                        if (class_exists($classname))
                                $this->$classname = new $classname;
                }
        }

        public function loadView($file, $template = '') {
                $this->view->load($file, $template);
        }

        public function replaceView($template, $value) {
                $this->view->replace($template, $value);
        }

        private function formfill() {
                foreach ($_POST as $key => $value) {
                        $this->replaceView('POST_' . $key, $value);
                        $this->replaceView('POST_' . $key . '_' . $value, 'selected');
                }
                foreach ($_GET as $key => $value) {
                        $this->replaceView('GET_' . $key, $value);
                        $this->replaceView('GET_' . $key . '_' . $value, 'selected');
                }
        }

        private function sessionfill() {
                foreach ($_SESSION as $key => $value) {
                        $this->replaceView('SESSION_' . $key, $value);
                        if(!is_array($value))
                        {
                                $this->replaceView('SESSION_' . $key . '_' . $value, 'selected');
                        }
                }
        }

        public function __destruct() {
                $timer = new Timer();
                $this->replaceView('js_countdown', $timer->getJavascript());

                $this->formfill();
                $this->sessionfill();
                $this->view->cleanup('POST');
                $this->view->cleanup('GET');
                $this->view->cleanup('ERROR');
                $this->view->cleanup('CLASS');
                $this->view->cleanup('JS');
                $this->view->cleanup('SESSION');
                unset($this->view);
        }

}

?>