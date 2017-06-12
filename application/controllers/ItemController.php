<?php

class ItemController extends Controller{
	public function index(){
		
		$this->assign('data','123');
		$this->render();
	}
}