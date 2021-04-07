<?php
class ControllerKreativanHero extends Controller {
	public function index() {

		$this->load->model('design/banner');
		$this->load->model('tool/image');
    $this->load->model('tool/kreativan_resize');

		$data['items'] = array();
    $data['hero_status'] = $this->config->get('hero_status');
    $data['ratio'] = !empty($this->config->get('hero_ratio')) ? $this->config->get('hero_ratio') : "4:1";
    
    $width_l = 1900;
    $width_m = 1024;
    $width_s = 600;

		$results = $this->model_design_banner->getBanner($this->config->get('hero_banner'));

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {

        $image_l = $this->model_tool_kreativan_resize->width($result['image'], $width_l);
        $image_m =  $this->model_tool_kreativan_resize->width($result['image'], $width_m);
        $image_s =  $this->model_tool_kreativan_resize->width($result['image'], $width_s);

				$data['items'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
          'image' => "image/" . $result['image'],
          'image_l' => $image_l,
          'image_m' => $image_m,
          'image_s' => $image_s,
				);
        
			}
		}

		return $this->load->view('kreativan/hero', $data);

	}

}
