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

    //  Banners
    // ===========================================================
    $this->load->model('design/banner');
    $data['banners'] = $this->model_design_banner->getBanners();

    //  Data
    // ===========================================================
    if (isset($this->request->post['hero'])) {
      $data['hero'] = $this->request->post['hero'];
    } else {
      $data['hero'] = $this->config->get('hero');
    }

    // Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('kreativan/hero', $data));

  }

}