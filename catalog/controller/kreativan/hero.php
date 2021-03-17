<?php
class ControllerKreativanHero extends Controller {
	public function index() {

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$data['items'] = array();
    $data['hero_status'] = $this->config->get('kreativan_hero_status');

		$results = $this->model_design_banner->getBanner($this->config->get('kreativan_hero_banner'));

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$data['items'][] = array(
					'title' => $result['title'],
					'link'  => $result['link'],
          'image' => "image/" . $result['image'],
					'image_resized' => $this->model_tool_image->resize($result['image'], 1400, 500),
				);
			}
		}

		return $this->load->view('kreativan/hero', $data);
	}
}
