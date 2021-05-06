<?php
class ControllerKreativanLess extends Controller {

	public function index() {

    $kreativan = $this->config->get('kreativan');

    $theme = $this->config->get('config_theme');
    $suffix = !empty($kreativan["less_suffix"]) && $kreativan["less_suffix"] != "" ? "?v={$kreativan["less_suffix"]}" : "";
    
    if($kreativan["less"]) {
      return $this->compile();
    } else {
      return "catalog/view/theme/{$theme}/assets/css/main.css{$suffix}";
    }

	}

  public function compile() {

    $theme = $this->config->get('config_theme');

    require_once("vendor/less.php/lib/Less/Autoloader.php");
    Less_Autoloader::register();
    
    $css_file = "catalog/view/theme/{$theme}/assets/css/main.css";
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

    return "cache/$css_file_name";

  }


}
