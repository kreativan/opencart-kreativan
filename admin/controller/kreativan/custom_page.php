<?php
class ControllerKreativanCustomPage extends Controller {
	public function index() {

		$this->load->language('kreativan/custom_page');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		// Save settings
		// ========================================================================
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('custom_page', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('kreativan/custom_page', 'user_token=' . $this->session->data['user_token'], true));
		}

		// Cleanup setting DB
		// ========================================================================
		$data["cleanup_db_url"] = $this->url->link('kreativan/custom_page', 'user_token=' . $this->session->data['user_token'] . '&cleaup_db=1', true);
		if(isset($this->request->get["cleaup_db"])) {
			$this->model_setting_setting->deleteSetting('custom_page');
			$this->response->redirect($this->url->link('kreativan/custom_page', 'user_token=' . $this->session->data['user_token'], true));
		}

		// Save User Token
		$data['user_token'] = $this->session->data['user_token'];

		// Breadcrumbs
		// ========================================================================
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('kreativan/custom_page', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('kreativan/custom_page', 'user_token=' . $this->session->data['user_token'], true)
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
		if (isset($this->request->post['custom_page_option'])) {
			$data['custom_page_option'] = $this->request->post['custom_page_option'];
		} else {
			$data['custom_page_option'] = $this->config->get('custom_page_option');
		}

		// Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('kreativan/custom_page', $data));

	}

}
