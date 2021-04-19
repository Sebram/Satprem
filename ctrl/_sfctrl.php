<?php
//	php app/console generate:controller --actions=showPostAction:/article/{id} --actions=getListAction:/_list-posts/{max}:AcmeBlogBundle:Post:list_posts.html.twig

class sfctrl{

	private $entityname;
	private $bundleName;

	private $startcommand;
	private $actionsFunc=array();
	private $endcommand;

	public function setEntityName($entityName){
 		$this->entityname = $entityName;
	}

	public function setBundleName($bundlePath)
	{	
		$bundle = explode('/', $bundlePath);
		
		if(count($bundle) == 2) $this->bundleName = $bundle[1];
		
		if(count($bundle) == 3) $this->bundleName = $bundle[1].$bundle[2];
	}

	public function setStartCommand(){
		$this->startcommand = "php app/console generate:controller --no-interaction --controller=".$this->bundleName.":".$this->entityname." --route-format=annotation --template-format=php ";
	}

	public function setEndCommand(){
		$this->endcommand = ":".$this->bundleName.":".$this->entityname.":".$this->entityname.".html.php";
	}


	public function setActionsFunc($actionType, $id=null){
		if($id!=null){
			$action = " --actions=".$actionType."Action:/".strtolower($this->entityname)."/".$actionType."/{".$id."}:".$this->bundleName.":".$this->entityname.":".$actionType.".html.php";
			array_push($this->actionsFunc, $action);
		}
		if($id==null){
			$action = " --actions=".$actionType."Action:/".strtolower($this->entityname)."/".$actionType.":".$this->bundleName.":".$this->entityname.":".$actionType.".html.php";
			array_push($this->actionsFunc, $action);
		}
	}

	public function getActionsFunc(){
		return $this->actionsFunc;
	}

	public function constructController(){
		$controller = $this->startcommand;
		foreach ($this->getActionsFunc() as $key => $value) {
			$controller .= $value;
		}
		//$controller .= $this->endcommand;

		return $controller;
	}
}