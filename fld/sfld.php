<?php
	/*
	 *  sfld.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */

class sfld{	
	
	private $type;
	private $value;
	private $name;
	

	public function setName($n){
		$this->name = $n;
	}
	public function setType($t){
		$this->type = $t;
	}
	public function setValue($v){
		$this->value = $v;
	}
	

	public function setArrayToCtrl(){
		$array = array(
			'name'=>$this->name,
			'type'=>$this->type
			);
		array_push($this->fiedsTab, $array);
	}


	public function arrayFieldName(){
		return array(
			'name'=>$this->name,
			'type'=>$this->type
			);
	}
    
	public function getsfld(){
		if($this->type == "string") return $this->name.":".$this->type."(".$this->value.") ";
		else return $this->name.":".$this->type." ";
	}



}