<?php
class ControllerKreativanHero extends Controller {
	public function index() {

		$this->load->model('design/banner');
		$this->load->model('tool/image');
    $this->load->model('tool/kreativan_resize');

    $hero = $this->config->get('hero');
    $data["hero"] = $hero;

    $data['ratio'] = !empty($hero["ratio"]) ? "ratio: {$hero["ratio"]};" : "";
    $data['ratio_mobile'] = !empty($hero["ratio_mobile"]) ? "ratio: {$hero["ratio_mobile"]};" : "";
    
    $width_l = 1900;
    $width_m = 1024;
    $width_s = 600;

    $m_l = 980;
    $m_m = 640;
    $m_s = 480;

    if($hero["status"]) {

      $banners = $this->model_design_banner->getBanner($hero["banner"]);
      $banners_mobile = $this->model_design_banner->getBanner($hero["banner_mobile"]);

      if(count($banners) > 0) {
        foreach ($banners as $result) {
          if (is_file(DIR_IMAGE . $result['image'])) {

            $image_l = $this->model_tool_kreativan_resize->width($result['image'], $width_l);
            $image_m =  $this->model_tool_kreativan_resize->width($result['image'], $width_m);
            $image_s =  $this->model_tool_kreativan_resize->width($result['image'], $width_s);

            $data['items'][] = array(
              'title' => $result['title'],
              'link'  => $result['link'],
              'image' => "image/" . $result['image'],
              'image_l' => $image_l,
              'image_m' => $image_m,
              'image_s' => $image_s,
            );
            
          }
        }
      }

      if(count($banners_mobile) > 0) {
        foreach ($banners_mobile as $result) {
          if (is_file(DIR_IMAGE . $result['image'])) {

            $image_l = $this->model_tool_kreativan_resize->width($result['image'], $m_l);
            $image_m =  $this->model_tool_kreativan_resize->width($result['image'], $m_m);
            $image_s =  $this->model_tool_kreativan_resize->width($result['image'], $m_s);

            $data['items_mobile'][] = array(
              'title' => $result['title'],
              'link'  => $result['link'],
              'image_l' => $image_l,
              'image_m' => $image_m,
              'image_s' => $image_s,
            );
            
          }
        }
      }

    }

		return $this->load->view('kreativan/hero', $data);

	}

  public function ajax() {

    $this->load->model('design/banner');
		$this->load->model('tool/image');
    $this->load->model('tool/kreativan_resize');

    $hero = $this->config->get('hero');

    $data = [];
    $data["hero"] = $hero;
    $data["type"] = isset($this->request->get['type']) && $this->request->get['type'] == "mobile" ? "mobile" : "desktop";
    $data['ratio'] = !empty($hero["ratio"]) ? "ratio: {$hero["ratio"]};" : "";
    $data['ratio_mobile'] = !empty($hero["ratio_mobile"]) ? "ratio: {$hero["ratio_mobile"]};" : "";

    $data['items'] = array();
    $data['items_mobile'] = array();
    
    $width_l = 1900;
    $width_m = 1024;
    $width_s = 600;

    $m_l = 980;
    $m_m = 640;
    $m_s = 480;

    if($hero["status"]) {

      $banners = $this->model_design_banner->getBanner($hero["banner"]);
      $banners_mobile = $this->model_design_banner->getBanner($hero["banner_mobile"]);

      if(count($banners) > 0) {
        foreach ($banners as $result) {
          if (is_file(DIR_IMAGE . $result['image'])) {

            $image_l = $this->model_tool_kreativan_resize->width($result['image'], $width_l);
            $image_m =  $this->model_tool_kreativan_resize->width($result['image'], $width_m);
            $image_s =  $this->model_tool_kreativan_resize->width($result['image'], $width_s);

            $data['items'][] = array(
              'title' => $result['title'],
              'link'  => $result['link'],
              'image' => "image/" . $result['image'],
              'image_l' => $image_l,
              'image_m' => $image_m,
              'image_s' => $image_s,
            );

          }
        }
      }

      if(count($banners_mobile) > 0) {
        foreach ($banners_mobile as $result) {
          if (is_file(DIR_IMAGE . $result['image'])) {

            $image_l = $this->model_tool_kreativan_resize->width($result['image'], $m_l);
            $image_m =  $this->model_tool_kreativan_resize->width($result['image'], $m_m);
            $image_s =  $this->model_tool_kreativan_resize->width($result['image'], $m_s);

            $data['items_mobile'][] = array(
              'title' => $result['title'],
              'link'  => $result['link'],
              'image_l' => $image_l,
              'image_m' => $image_m,
              'image_s' => $image_s,
            );

          }
        }
      }

    }

    echo $this->load->view('kreativan/hero_ajax', $data);

  }

}
