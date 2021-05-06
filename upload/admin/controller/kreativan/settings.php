<?php
class ControllerKreativanSettings extends Controller {
	public function index() {

		$this->load->language('kreativan/settings');
		$this->load->model('setting/setting');

		// Save User Token
		$data['user_token'] = $this->session->data['user_token'];
    $data['store_settings'] = $this->url->link('setting/setting', 'user_token=' . $this->session->data['user_token'], true);

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
			$this->model_setting_setting->deleteSetting('kreativan');
			$this->response->redirect($this->url->link('kreativan/settings', 'user_token=' . $this->session->data['user_token'], true));
		}

		//  Data
		// ========================================================================
    if (isset($this->request->post['kreativan'])) {
			$data['kreativan'] = $this->request->post['kreativan'];
		} else {
			$data['kreativan'] = $this->config->get('kreativan');
		}


		// Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('kreativan/settings', $data));

	}

}
