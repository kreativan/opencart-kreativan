<?php
class ControllerKreativanHeaderbar extends Controller {

	public function index() {

    $this->load->language('kreativan/kreativan');

    $kreativan = $this->config->get('kreativan');

    $data = [];
    $data["kreativan"] = $kreativan;

    $data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');

	  $data['logged']     = $this->customer->isLogged();
    $data['register']   = $this->url->link('account/register', '', true);
		$data['login']      = $this->url->link('account/login', '', true);
		$data['telephone']  = $this->config->get('config_telephone');

    $user_name = $this->customer->getFirstName();
    $data['user_name'] = $user_name;
    $data["hello"] = sprintf($this->language->get('text_hello'), $user_name);

    $data["social_links"] = [];
		if($kreativan["social_links"] && count($kreativan["social_links"])) {
			foreach($kreativan["social_links"] as $key => $value) {
				if(!empty($value)) {
					$data["social_links"][$key] = $value;
				}
			}
		} 

    return $this->load->view('kreativan/headerbar', $data);

  }

}
