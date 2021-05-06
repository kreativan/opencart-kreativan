<?php

class ControllerKreativanMobileHeader extends Controller {

  public function index() {

    $this->load->model('tool/image');
    $this->load->language('account/account');
    $this->load->language('kreativan/kreativan');

    $route = isset($this->request->get['route']) ? $this->request->get['route'] : "";
    $route .= isset($this->request->get['information_id']) ? "&information_id={$this->request->get['information_id']}" : "";
    $data["route"] = $route;
    

    $data["logged"] = $this->customer->isLogged();
    $data['home'] = $this->url->link('common/home');
    $data['name'] = $this->config->get('config_name');
    $data['login'] = $this->url->link('account/login', '', true);

    // Logo
    if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

    $current_category_id = (isset($this->request->get['route']) && $this->request->get['route'] == "product/category") ? $this->request->get['path'] : 0;
    $current_category_id = explode("_", $current_category_id);
    $current_category_id = $current_category_id [0];
    $data["current_category"] = $current_category_id;

    // cart
		$count_vouchers = isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0;
		$cart_count = $this->cart->countProducts() + $count_vouchers;
		$data['cart_count'] = $cart_count;
		$data['text_cart'] = $this->language->get('text_cart');

    // filters

    $is_filter = false;

    if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

    if (isset($this->request->get['path'])) {
      $this->load->model('catalog/category');
      $category_id = end($parts);
      $filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);
      if ($filter_groups) $is_filter = true;
    }

    $data["is_filter"] = $is_filter;
    $data["current_filter"] = isset($this->request->get['filter']) ? $this->request->get['filter'] : "";

    return $this->load->view('kreativan/mobile_header', $data);

  }

}