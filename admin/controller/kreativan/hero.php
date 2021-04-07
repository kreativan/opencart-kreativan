<?php
class ControllerKreativanHero extends Controller {

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
			$this->model_setting_setting->editSetting('hero', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('kreativan/hero', 'user_token=' . $this->session->data['user_token'], true));
		}

    // Cleanup setting DB
		// ========================================================================
		$data["cleanup_db_url"] = $this->url->link('kreativan/hero', 'user_token=' . $this->session->data['user_token'] . '&cleaup_db=1', true);
		if(isset($this->request->get["cleaup_db"])) {
			$this->model_setting_setting->deleteSetting('hero');
			$this->response->redirect($this->url->link('kreativan/hero', 'user_token=' . $this->session->data['user_token'], true));
		}

    // Breadcrumbs
    // ========================================================================
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('kreativan/hero', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('kreativan/hero', 'user_token=' . $this->session->data['user_token'], true)
    );

    // Hero
    // ========================================================================
    $this->load->model('design/banner');
    $data['banners'] = $this->model_design_banner->getBanners();

    if (isset($this->request->post['hero_status'])) {
      $data['hero_status'] = $this->request->post['hero_status'];
    } else {
      $data['hero_status'] = $this->config->get('hero_status');
    }

    if (isset($this->request->post['hero_banner'])) {
      $data['hero_banner'] = $this->request->post['hero_banner'];
    } else {
      $data['hero_banner'] = $this->config->get('hero_banner');
    }

    if (isset($this->request->post['hero_ratio'])) {
      $data['hero_ratio'] = $this->request->post['hero_ratio'];
    } else {
      $data['hero_ratio'] = $this->config->get('hero_ratio');
    }

    // Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('kreativan/hero', $data));

  }

}