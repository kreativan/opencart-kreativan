<?php
class ControllerKreativanAjax extends Controller {

  public function index() {

    $json = [];

    if (isset($this->request->post['is_ajax']) && $this->request->post['is_ajax'] == "1") {
      
      $json["is_ajax"] = true;

    }

    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

  }

  /* =========================================================== 
    Categories menu
  =========================================================== */

  public function offcanvascategoryhtml() {

    $this->load->language('kreativan/kreativan');
    $this->load->model('catalog/category');

    $data = [];
    $array = [];

    $category_id = isset($this->request->get['category_id']) ? $this->request->get['category_id'] : 0;

    // Current Category
    $this_category = $this->model_catalog_category->getCategory($category_id);
    $this_path = $category_id != "0" && $this_category["parent_id"] != "0" ? $this_category["parent_id"] . "_" : "";
    $this_path .= $category_id; 
    $data["parent_id"] = $category_id != "0" ? $this_category["parent_id"] : "";
    $data["href"] = $this->url->link('product/category', "path={$this_path}");
    $data["is_root"] = $category_id == "0" ? true : false;

    $data["headline"] = $category_id != "0" ? $this_category["name"] : $this->language->get('text_categories');
    $data["text_all"] = $this->language->get('text_all');

    $categories = $this->model_catalog_category->getCategories($category_id);

    foreach($categories as $category) {

      $path = $category_id != "0" && $category["parent_id"] != "0" ? $category["parent_id"] . "_" : "";
      $path .= $category["category_id"]; 

      $children = $this->model_catalog_category->getCategories($category["category_id"]);

      $array[] = [
        "category_id" => $category["category_id"],
        "status" => $category["status"],
        "parent_id" => $category["parent_id"],
        "name" => $category["name"],
        "href" => $this->url->link('product/category', "path={$path}"),
        "children" => count($children),
        "is_active" => ($path == $this->request->get['path']) ? 1 : 0,
      ];

    }
    

    // print_r($array);

    $data["categories"] = $array;

    echo $this->load->view('kreativan/offcanvas_menu_ajax', $data);

  }


  /* =========================================================== 
    User Menu
  =========================================================== */

  public function usermenu() {
    $this->load->language('extension/module/account');
    $this->load->language('kreativan/kreativan');

    $data = [];

    $data['logged'] = $this->customer->isLogged();
    $user_name = $this->customer->getFirstName();
    $data['user_name'] = $user_name;
    $data["hello"] = sprintf($this->language->get('text_hello'), $user_name);

    $route = isset($this->request->get['route']) ? $this->request->get['route'] : false;
    
    $menu = [
      "account" => [
        "title" => $this->language->get('text_account'),
        "href" => $this->url->link('account/account', '', true),
        "is_active" => ($route && $route == "account/account") ? true : false,
      ],
      "edit" => [
        "title" => $this->language->get('text_edit'),
        "href" => $this->url->link('account/edit', '', true),
        "is_active" => ($route && $route == "account/edit") ? true : false,
      ],
      "address" => [
        "title" => $this->language->get('text_address'),
        "href" => $this->url->link('account/address', '', true),
        "is_active" => ($route && $route == "account/address") ? true : false,
      ],
      "wishlist" => [
        "title" => $this->language->get('text_wishlist'),
        "href" => $this->url->link('account/wishlist', '', true),
        "is_active" => ($route && $route == "account/wishlist") ? true : false,
      ],
      "orders" => [
        "title" => $this->language->get('text_order'),
        "href" => $this->url->link('account/order', '', true),
        "is_active" => ($route && $route == "account/order") ? true : false,
      ],
      "transactions" => [
        "title" => $this->language->get('text_transaction'),
        "href" => $this->url->link('account/transaction', '', true),
        "is_active" => ($route && $route == "account/transaction") ? true : false,
      ],
      "download" => [
        "title" => $this->language->get('text_download'),
        "href" => $this->url->link('account/download', '', true),
        "is_active" => ($route && $route == "account/download") ? true : false,
      ],
      "reward" => [
        "title" => $this->language->get('text_reward'),
        "href" => $this->url->link('account/reward', '', true),
        "is_active" => ($route && $route == "account/reward") ? true : false,
      ],
      "return" => [
        "title" => $this->language->get('text_return'),
        "href" => $this->url->link('account/reward', '', true),
        "is_active" => ($route && $route == "account/return") ? true : false,
      ],
      "newsletter" => [
        "title" => $this->language->get('text_newsletter'),
        "href" => $this->url->link('account/newsletter', '', true),
        "is_active" => ($route && $route == "account/newsletter") ? true : false,
      ],
      "recurring" => [
        "title" => $this->language->get('text_recurring'),
        "href" => $this->url->link('account/recurring', '', true),
        "is_active" => ($route && $route == "account/recurring") ? true : false,
      ],
      "logout" => [
        "title" => $this->language->get('text_logout'),
        "href" => $this->url->link('account/logout', '', true),
        "is_active" => ($route && $route == "account/logout") ? true : false,
      ],
    ];
    

    $data["menu"] = $menu;

    echo $this->load->view('kreativan/user_menu_ajax', $data);
  }

  /* =========================================================== 
    Global Menu
  =========================================================== */

  public function globalmenu() {
    $this->load->language('kreativan/kreativan');
    $this->load->model('catalog/information');
    $this->load->language('common/footer');
    
    $route = isset($this->request->get['menu_route']) ? $this->request->get['menu_route'] : false;
    $information_id = isset($this->request->get['information_id']) ? $this->request->get['information_id'] : "";

    $data['name'] = $this->config->get('config_name');

    // Information
    $data['informations'] = array();
		foreach ($this->model_catalog_information->getInformations() as $result) {
			$data['informations'][] = array(
        'title' => $result['title'],
        'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id']),
        'is_active' => ($information_id == $result['information_id']) ?  true: false,
      );
		}

    $menu = [
      "special" => [
        "title" => $this->language->get('text_special'),
        "href" => $this->url->link('product/special', '', true),
        "is_active" => ($route && $route == "product/special") ? true : false,
      ],
      "manufacturer" => [
        "title" => $this->language->get('text_manufacturer'),
        "href" => $this->url->link('product/manufacturer', '', true),
        "is_active" => ($route && $route == "product/manufacturer") ? true : false,
      ],
      "voucher" => [
        "title" => $this->language->get('text_voucher'),
        "href" => $this->url->link('account/voucher', '', true),
        "is_active" => ($route && $route == "account/voucher") ? true : false,
      ],
      "affiliate" => [
        "title" => $this->language->get('text_affiliate'),
        "href" => $this->url->link('affiliate/login', '', true),
        "is_active" => ($route && $route == "affiliate/login") ? true : false,
      ],
    ];

    $data["menu"] = $menu;

    $support = [
      "return" => [
        "title" => $this->language->get('text_return'),
        "href" => $this->url->link('account/return/add', '', true),
        "is_active" => ($route && $route == "account/return/add") ? true : false,
      ],
      "contact" => [
        "title" => $this->language->get('text_contact'),
        "href" => $this->url->link('information/contact', '', true),
        "is_active" => ($route && $route == "information/contact") ? true : false,
      ],
    ];

    $data["support"] = $support;

    //
    // Languages
    //
    $this->load->model('localisation/language');
    $data['lang_code'] = $this->session->data['language'];
    $data["is_multilang"] = $this->config->get('kreativan_multilang') == "1" ? true : false;
    $data["is_currency"] = $this->config->get('kreativan_multicurency') == "1" ? true : false;

    $data['languages'] = array();
    $languages = $this->model_localisation_language->getLanguages();
		foreach ($languages as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name' => $result['name'],
					'code' => $result['code']
				);
			}
		}

    //
    // currency
    //
    $data['currency_code'] = $this->session->data['currency'];
    $data['currencies'] = array();
		$currencies = $this->model_localisation_currency->getCurrencies();
		foreach ($currencies as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

    echo $this->load->view('kreativan/global_menu_ajax', $data);

  }


  /* =========================================================== 
    Filters
  =========================================================== */
  public function filter() {
    echo $this->load->controller('extension/module/filter');
  }

}