<?php
	/*
	 *  sfent.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */
class sfent{
	

	private $project;
	private $name;
	
	public function setProject($pro){
		$this->project = $pro;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getsfent(){
		return " --entity=".$this->project.":".$this->name." --fields='";
	}

	 
	function writeDate($bundlepath, $entity)
	{
		$iNewFunc = "\r\n";
		$iNewFunc.="     public function _construct(){\r\n";
		$iNewFunc.="       \$this->date = date_format(new \DateTime, 'd/m/Y');\r\n";
		$iNewFunc.="     }\r\n";
		$iNewFunc.="}\r\n";
		$iUseStatement ="use Symfony\Component\Validator\Constraints\DateTime;";
		$aFileContent = file($bundlepath.'/Entity/'.$entity.'/'.$entity.'.php');
		/*
		 * On prend chaque ligne du fichier php,
		 * et on met dans un Array
		 */
		$indice = count($aFileContent);
		$part1 = array_slice($aFileContent, 0, 3);
		$part2 = array($iUseStatement, PHP_EOL);
		$part3 = array_slice($aFileContent, 4, $indice - 5);
		$part4 = array($iNewFunc, PHP_EOL); // la ligne a ajouter
		
		$aNewFileContentToWrite = array_merge(
			$part1, $part2, $part3, $part4
			);
		
		$part=implode(' ', $aNewFileContentToWrite);
		//var_dump($part);

		if( file_put_contents($bundlepath.'/Entity/'.$entity.'/'.$entity.'.php', $part ))
		  print 'ok';
		else
		  echo 'Erreur d\'écriture dans le fichier';
	}
	
	 

}