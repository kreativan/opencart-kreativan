<?php
class ControllerKreativanNavbar extends Controller {

	public function index() {

		// Logo
    if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			$data['wishlist_count'] = $this->model_account_wishlist->getTotalWishlist();
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			$data['wishlist_count'] = isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0;
		}

		// Compare
		$this->load->language('product/category');
		$compare_count = isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0;
		$data['compare_url'] = $this->url->link('product/compare');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), $compare_count);
		$data['compare_count'] = $compare_count;

		// cart
		$count_vouchers = isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0;
		$cart_count = $this->cart->countProducts() + $count_vouchers;
		$data['cart_count'] = $cart_count;
		$data['text_cart'] = $this->language->get('text_cart');


		// Other
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));

		$data['name'] = $this->config->get('config_name');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);

		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

    $data["show_search"] = (isset($this->request->get['route']) && $this->request->get['route'] == "product/search") ? 0 : 1;

    return $this->load->view('kreativan/navbar', $data);

  }

}
