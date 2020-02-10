<?php
class Loader {
  private $models = [ ];

  public function view($view, $data = []) {
    foreach ($data as $key=>$value) {
      $$key = $value; // ajoute une variable locale de nom $key avec la valeur $value
    }
    include "views/${view}.php";
  }

  public function load($view = null, $data = []) {
    $this->define_helper();
    $data = $this->inject_model_data($data);
    $this->view('header', $data);
    if ($view!==null) { $this->view($view, $data); }
    $this->view('footer', $data);
  }

  public function define_helper() {
    function set_value($name) { return isset($_POST[$name])?htmlentities($_POST[$name]):''; }
  }

   public function add_model($model_name, $model) {
    $this->models [$model_name] = $model;
  }

  private function inject_model_data($data) {
    foreach ( $this->models as $model )
      $data = $model->inject_data ( $data );
    return $data;
  }
}