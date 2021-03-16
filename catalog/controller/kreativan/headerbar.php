<?php
class ControllerKreativanHeaderbar extends Controller {

	public function index() {

    $data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		$data['text_contact'] = $this->language->get('contact');

	  $data['logged'] = $this->customer->isLogged();
    $data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
    $data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

    $data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');

    return $this->load->view('kreativan/headerbar', $data);

  }

}
