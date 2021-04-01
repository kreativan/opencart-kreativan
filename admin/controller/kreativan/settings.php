<?php
class ControllerKreativanSettings extends Controller {
	public function index() {

		$this->load->language('kreativan/settings');
		$this->load->model('setting/setting');

		// Save User Token
		$data['user_token'] = $this->session->data['user_token'];

		// Heading Title
		$this->document->setTitle($this->language->get('heading_title'));

		// Save settings
		// ========================================================================
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('kreativan', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'], true));
		}

		// Cleanup setting DB
		// ========================================================================
		$data["cleanup_db_url"] = $this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'] . '&cleaup_db=1', true);
		if(isset($this->request->get["cleaup_db"])) {
			$this->model_setting_setting->deleteSetting('kreativan_settings');
			$this->response->redirect($this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'], true));
		}

		// Breadcrumbs
		// ========================================================================
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'], true)
		);


		// Custom data
		// ========================================================================
    $data["kreativan"] = [
      "Name" => "Ivan Milincic",
      "Job" => "Web Developer",
      "Email" => "kreativan.dev@gmail.com",
      "Website" => "kreativan.dev",
    ];

		// Form Data
		// ========================================================================
    if (isset($this->request->post['kreativan_less'])) {
			$data['kreativan_less'] = $this->request->post['kreativan_less'];
		} else {
			$data['kreativan_less'] = $this->config->get('kreativan_less');
		}

		// Hero
		// ========================================================================
		$this->load->model('design/banner');
		$data['banners'] = $this->model_design_banner->getBanners();

		if (isset($this->request->post['kreativan_hero_status'])) {
			$data['kreativan_hero_status'] = $this->request->post['kreativan_hero_status'];
		} else {
			$data['kreativan_hero_status'] = $this->config->get('kreativan_hero_status');
		}

		if (isset($this->request->post['kreativan_hero_banner'])) {
			$data['kreativan_hero_banner'] = $this->request->post['kreativan_hero_banner'];
		} else {
			$data['kreativan_hero_banner'] = $this->config->get('kreativan_hero_banner');
		}

		// Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('kreativan/settings', $data));

	}

}
