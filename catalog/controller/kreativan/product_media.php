<?php
class ControllerKreativanProductMedia extends Controller {

  public function index() {
    $this->load->model('tool/image');
    $this->load->model('catalog/product');

    if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

    $product_info = $this->model_catalog_product->getProduct($product_id);

    $main_w  = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width');
    $main_h  = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height');

    $popup_w = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_width');
    $popup_h = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_popup_height');

    $thumb_w = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_width');
    $thumb_h = $this->config->get('theme_' . $this->config->get('config_theme') . '_image_additional_height');

    if ($product_info['image']) {
      $data['popup'] = $this->model_tool_image->resize($product_info['image'], $popup_w, $popup_h);
    } else {
      $data['popup'] = '';
    }

    if ($product_info['image']) {
      $data['main'] = $this->model_tool_image->resize($product_info['image'], $main_w, $main_h);
    } else {
      $data['main'] = '';
    }
    
    if ($product_info['image']) {
      $data['main_thumb'] = $this->model_tool_image->resize($product_info['image'], $thumb_w, $thumb_h);
    } else {
      $data['main_thumb'] = '';
    }
    
    $data['images'] = array();

    $results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

    foreach ($results as $result) {
      $data['images'][] = array(
        'main' => $this->model_tool_image->resize($result['image'], $main_w, $main_h),
        'popup' => $this->model_tool_image->resize($result['image'], $popup_w, $popup_h),
        'thumb' => $this->model_tool_image->resize($result['image'], $thumb_w, $thumb_h)
      );
    }

    $data["width"] = $main_w;
    $data["height"] = $main_h;

    return $this->load->view('kreativan/product_media', $data);

  }


}