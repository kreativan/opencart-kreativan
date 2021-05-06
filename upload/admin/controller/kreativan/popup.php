<?php
class ControllerKreativanPopup extends Controller {

  public function index() {

    $this->load->language('kreativan/settings');
    $this->load->model('setting/setting');

    // Save User Token
		$data['user_token'] = $this->session->data['user_token'];

    // Get languages
    $this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		// Heading Title
		$this->document->setTitle("Popup");


    // Cleanup setting DB
		// ========================================================================
		$data["cleanup_db_url"] = $this->url->link('kreativan/popup', 'user_token=' . $this->session->data['user_token'] . '&cleaup_db=1', true);
		if(isset($this->request->get["cleaup_db"])) {
			$this->model_setting_setting->deleteSetting('popup');
			$this->response->redirect($this->url->link('kreativan/popup', 'user_token=' . $this->session->data['user_token'], true));
		}

    // Save settings
		// ========================================================================
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('popup', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('kreativan/popup', 'user_token=' . $this->session->data['user_token'], true));
		}

    //  Data
    // ===========================================================

    if (isset($this->request->post['popup'])) {
      $data['popup'] = $this->request->post['popup'];
    } else {
      $data['popup'] = $this->config->get('popup');
    }

    // Image
    $this->load->model('tool/image');
    $data['thumb'] = !empty($data['popup']['image']) ? $this->model_tool_image->resize($data['popup']['image'], 200, 140) : "";


    // Controllers
		// ========================================================================
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('kreativan/popup', $data));

  }

}