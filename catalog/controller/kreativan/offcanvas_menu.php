<?php
class ControllerKreativanOffcanvasMenu extends Controller {

	public function index() {

    $this->load->language('common/menu');
    
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		// Custom Data & Options
		// ========================================================================
		$data["current_category_id"] = $this->currentCategoryId();
		$data["current_child_category_id"] = $this->currentCategoryId(false);

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
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}


		return $this->load->view('kreativan/offcanvas_menu', $data);

	}

	public function currentCategoryId($split = true) {
		$id = 0;
		if(isset($this->request->get['path'])) {
			if($split === true) {
				$parts = explode('_', (string)$this->request->get['path']);
				$id = $parts[0];
			} else {
				$id = (string) $this->request->get['path'];
			}
		}
		return $id;
	}
}
