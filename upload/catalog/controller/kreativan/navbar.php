<?php
class ControllerKreativanNavbar extends Controller {

	public function index() {

    $this->load->language('kreativan/kreativan');
    $this->load->language('account/account');
    $this->load->model('tool/image');

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
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
    
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

    $data["show_search"] = (isset($this->request->get['route']) && $this->request->get['route'] == "product/search") ? 0 : 1;
    
    // 

    $route = isset($this->request->get['route']) ? $this->request->get['route'] : false;

   // Menu
   // ===========================================================

    $menu = [
      0 => [
        "title" => $this->language->get('text_special'),
        "href" => $this->url->link('product/special'),
        "is_active" => ($route && $route == "product/special") ? true : false,
      ],
      1 => [
        "title" => $this->language->get('text_contact'),
        "href" => $this->url->link('information/contact'),
        "is_active" => ($route && $route == "information/contact") ? true : false,
      ],
    ];

    $data["menu"] = $menu;

    // User Menu
    // ===========================================================
  
    $user_menu = [
      0 => [
        "title" => $this->language->get('text_account'),
        "href" => $this->url->link('account/account', '', true),
        "is_active" => ($route && $route == "account/account") ? true : false,
      ],
      1 => [
        "title" => $this->language->get('text_order'),
        "href" => $this->url->link('account/order', '', true),
        "is_active" => ($route && $route == "account/order") ? true : false,
      ],
      2 => [
        "title" => $this->language->get('text_transaction'),
        "href" => $this->url->link('account/transaction', '', true),
        "is_active" => ($route && $route == "account/transaction") ? true : false,
      ],
      3 => [
        "title" => $this->language->get('text_download'),
        "href" => $this->url->link('account/download', '', true),
        "is_active" => ($route && $route == "account/download") ? true : false,
      ],
      4 => [
        "title" => $this->language->get('text_logout'),
        "href" => $this->url->link('account/logout', '', true),
        "is_active" => ($route && $route == "account/logout") ? true : false,
      ],
    ];

    $data["user_menu"] = $user_menu;

    // Categories Data
    // ========================================================================
    $data['categories'] = array();

    $categories = $this->model_catalog_category->getCategories(0);

    foreach ($categories as $category) {
      if ($category['top']) {
        // Level 2
        $children_data = array();

        $children = $this->model_catalog_category->getCategories($category['category_id']);

        foreach ($children as $child) {
          $filter_data = array(
            'filter_category_id'  => $child['category_id'],
            'filter_sub_category' => true
          );

          $children_data[] = array(
            'category_id' => $category['category_id'] . '_' . $child['category_id'],
            'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
            'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
          );
        }

        // Level 1
        $data['categories'][] = array(
          'category_id' => $category['category_id'],
          'name'        => $category['name'],
          'children'    => $children_data,
          'href'        => $this->url->link('product/category', 'path=' . $category['category_id']),
          'image'       => $this->model_tool_image->resize($category['image'], 80, 80),
        );
      }
    }

    $current_category_id = (isset($this->request->get['route']) && $this->request->get['route'] == "product/category") ? $this->request->get['path'] : 0;
    $current_category_id = explode("_", $current_category_id);
    $current_category_id = $current_category_id [0];
    $data["current_category"] = $current_category_id;

    return $this->load->view('kreativan/navbar', $data);

  }

}
