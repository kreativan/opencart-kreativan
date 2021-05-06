<?php
class ControllerKreativanPopup extends Controller {
	public function index() {

    $this->load->model('tool/kreativan_resize');

    $language_id = $this->config->get('config_language_id');
    $popup = $this->config->get('popup');

    $title = $popup["$language_id"]['title'];
    $title = !empty($title) ? $title : $popup[1]['title'];

    $description = $popup["$language_id"]['description'];
    $description = !empty($description) ? $description : $popup[1]['description'];

    if($popup['image']) {
      $image = $this->model_tool_kreativan_resize->width($popup['image'], 420);
    } else {
      $image = "";
    }
  
    $data["popup"] = $popup;
    $data["title"] = $title;
    $data["description"] = html_entity_decode($description);
    $data["image"] = $image;

    $kreativan = $this->config->get('kreativan');

    $data["social_links"] = [];
		if($kreativan["social_links"] && count($kreativan["social_links"])) {
			foreach($kreativan["social_links"] as $key => $value) {
				if(!empty($value)) {
					$data["social_links"][$key] = $value;
				}
			}
		}

    if(!$popup["status"]) {
      unset($this->session->data["popup"]);
      $data["show_popup"] = false;
    } elseif($popup["status"] == "debug") {
      $data["show_popup"] = true;
    } else {
      $data["show_popup"] = isset($this->session->data["popup"]) && !empty($this->session->data["popup"]) ? false : true;
    }

    return $this->load->view('kreativan/popup', $data);

  }

  public function close() {
    $popup = $this->config->get('popup');
    if($popup["status"] != "debug") {
      $this->session->data["popup"] = 1;
    } else {
      unset($this->session->data["popup"]);
    }
  }

}