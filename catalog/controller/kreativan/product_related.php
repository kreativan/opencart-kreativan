<?php
class ControllerKreativanProductRelated extends Controller {

  public function index() {
    $this->load->model('tool/image');
    $this->load->model('catalog/product');

    $data['products'] = array();

    $results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

    foreach ($results as $result) {
      if ($result['image']) {
        $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
      } else {
        $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
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
        $rating = (int)$result['rating'];
      } else {
        $rating = false;
      }

      $data['products'][] = array(
        'product_id'  => $result['product_id'],
        'thumb'       => $image,
        'name'        => $result['name'],
        'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
        'price'       => $price,
        'special'     => $special,
        'tax'         => $tax,
        'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
        'rating'      => $rating,
        'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
      );
    }

    return $this->load->view('kreativan/product_related', $data);

  }


}