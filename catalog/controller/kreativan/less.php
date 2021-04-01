<?php
class ControllerKreativanLess extends Controller {

	public function index() {

    $theme = $this->config->get('config_theme');
    $compile = $this->config->get('kreativan_less') ? true : false;

    if($compile) {
      return $this->compile();
    } else {
      return "catalog/view/theme/{$theme}/assets/less/main.css";
    }

	}

  public function compile() {

    $theme = $this->config->get('config_theme');

    require_once("catalog/view/theme/{$theme}/vendor/less.php/lib/Less/Autoloader.php");
    Less_Autoloader::register();
    
    $css_file = "catalog/view/theme/{$theme}/assets/less/main.css";
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

    $less_files = array(
      "catalog/view/theme/{$theme}/assets/less/main.less" => $root
    );

    $options = array(
      'cache_dir' => "cache/",
      'compress'=> true,
    );

    $css_file_name = Less_Cache::Get($less_files, $options);
    $compiled = file_get_contents("cache/" . $css_file_name);
    file_put_contents($css_file, $compiled);

    return $css_file;

  }


}
