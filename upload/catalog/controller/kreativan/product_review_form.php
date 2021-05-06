<?php
class ControllerKreativanProductReviewForm extends Controller {

  public function index() {

    $this->load->language('product/product');
    $this->load->model('catalog/review');

    $data["product_id"] = $this->request->get['product_id'];

    // Captcha
		if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha'] = '';
		}

    if ($this->customer->isLogged()) {
      $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
    } else {
      $data['customer_name'] = '';
    }

    return $this->load->view('kreativan/product_review_form', $data); 

  }

}