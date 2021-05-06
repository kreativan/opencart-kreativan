<?php
class ControllerExtensionModuleAioProducts extends Controller {

  public function index($setting) {

    $this->load->language('/kreativan/kreativan');
    $this->load->model('catalog/product');
    $this->load->model('tool/image');

    $width = !empty($setting['width']) ? $setting['width'] : 80;
    $height = !empty($setting['height']) ? $setting['height'] : 80;

    //  Latest
    // ===========================================================

    $data['latest'] = [];

    if(isset($setting['product_group']['latest'])) {

      $latest = $this->model_catalog_product->getLatestProducts($setting['limit']);

      if ($latest) {
        foreach ($latest as $result) {
          
          if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], $width, $height);
          } else {
            $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
          }

          if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
            $price = false;
          }

          if (!is_null($result['special']) && (float)$result['special'] >= 0) {
            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            $tax_price = (float)$result['special'];
          } else {
            $special = false;
            $tax_price = (float)$result['price'];
          }

          if ($this->config->get('config_tax')) {
            $tax = $this->currency->format($tax_price, $this->session->data['currency']);
          } else {
            $tax = false;
          }

          if ($this->config->get('config_review_status')) {
            $rating = $result['rating'];
          } else {
            $rating = false;
          }

          $data['latest'][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'rating'      => $rating,
            'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
          );

        }
      }

    }

    //  Bestseller
    // ===========================================================

  	$data['bestseller'] = array();

    if(isset($setting['product_group']['bestseller'])) {

      $bestseller = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

      if ($bestseller) {
      foreach ($bestseller as $result) {
        if ($result['image']) {
          $image = $this->model_tool_image->resize($result['image'], $width, $height);
        } else {
          $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
        }

        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
          $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
        } else {
          $price = false;
        }

        if (!is_null($result['special']) && (float)$result['special'] >= 0) {
          $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          $tax_price = (float)$result['special'];
        } else {
          $special = false;
          $tax_price = (float)$result['price'];
        }

        if ($this->config->get('config_tax')) {
          $tax = $this->currency->format($tax_price, $this->session->data['currency']);
        } else {
          $tax = false;
        }

        if ($this->config->get('config_review_status')) {
          $rating = $result['rating'];
        } else {
          $rating = false;
        }

        $data['bestseller'][] = array(
          'product_id'  => $result['product_id'],
          'thumb'       => $image,
          'name'        => $result['name'],
          'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
          'price'       => $price,
          'special'     => $special,
          'tax'         => $tax,
          'rating'      => $rating,
          'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
        );
      }
    }

    }

    //  Special
    // ===========================================================

    $data['special'] = array();

    $filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

    if(isset($setting['product_group']['special'])) {

      $special = $this->model_catalog_product->getProductSpecials($filter_data);

      $i = 0;

      if ($special) {
        foreach ($special as $result) {
          if($i++ > $setting['limit']) break;
          if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], $width, $height);
          } else {
            $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
          }

          if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
            $price = false;
          }

          if (!is_null($result['special']) && (float)$result['special'] >= 0) {
            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            $tax_price = (float)$result['special'];
          } else {
            $special = false;
            $tax_price = (float)$result['price'];
          }

          if ($this->config->get('config_tax')) {
            $tax = $this->currency->format($tax_price, $this->session->data['currency']);
          } else {
            $tax = false;
          }

          if ($this->config->get('config_review_status')) {
            $rating = $result['rating'];
          } else {
            $rating = false;
          }

          $data['special'][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'rating'      => $rating,
            'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
          );
        }
      }

    }

    //  Popular
    // ===========================================================
    $data['popular'] = array();

    if(isset($setting['product_group']['popular'])) {

      $popular = $this->model_catalog_product->getPopularProducts($setting['limit']);

      if ($popular) {
        foreach ($popular as $result) {
          if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], $width, $height);
          } else {
            $image = $this->model_tool_image->resize('placeholder.png', $width, $height);
          }

          if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
          } else {
            $price = false;
          }

          if (!is_null($result['special']) && (float)$result['special'] >= 0) {
            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            $tax_price = (float)$result['special'];
          } else {
            $special = false;
            $tax_price = (float)$result['price'];
          }

          if ($this->config->get('config_tax')) {
            $tax = $this->currency->format($tax_price, $this->session->data['currency']);
          } else {
            $tax = false;
          }

          if ($this->config->get('config_review_status')) {
            $rating = $result['rating'];
          } else {
            $rating = false;
          }

          $data['popular'][] = array(
            'product_id'  => $result['product_id'],
            'thumb'       => $image,
            'name'        => $result['name'],
            'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
            'price'       => $price,
            'special'     => $special,
            'tax'         => $tax,
            'rating'      => $rating,
            'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
          );
        }
      }

    }

    //  Other data
    // ===========================================================
    $data['review_status'] = $this->config->get('config_review_status');
    $data["text_latest"] = $this->language->get('text_aio_latest');
    $data["text_bestsellers"] = $this->language->get('text_aio_bestsellers');
    $data["text_special"] = $this->language->get('text_aio_special');
    $data["text_popular"] = $this->language->get('text_aio_popular');

    return $this->load->view('extension/module/aio_products', $data);

  }

}