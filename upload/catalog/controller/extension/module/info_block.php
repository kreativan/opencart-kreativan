<?php
class ControllerExtensionModuleInfoBlock extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$data['items'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
        $title_arr = explode("|", $result['title']);
				$data['items'][] = array(
					'title' => $title_arr[0],
          'subtitle' => $title_arr[1],
					'link'  => $result['link'],
					'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
				);
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/info_block', $data);
	}
}