<?php
class ControllerKreativanProductAttributes extends Controller {

  public function index() {
    $this->load->model('catalog/product');

    $data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

    return $this->load->view('kreativan/product_attributes', $data); 

  }

}