<?php
	/*
	 *  sfajax.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */
	 
class sfajax
{	
	private $divFrom;
	private $divTo;
	private $method;
	private $routename;
	private $path;	
	private $BundlePath;	
	private $jxFunc;
	private $entity;

	public function setPath($pth)
	{
		$this->path = $pth;
	}

	public function setBundlePath($bundlepth)
	{
		$this->bundlePath = $bundlepth;
	}
	
	public function getBundlePath()
	{
		return $this->bundlePath;
	}

	public function setDivFrom($dF)
	{
		$this->divFrom = $dF;
	}
	
	public function setDivTo($dT)
	{
		$this->divTo = $dT;
	}
	
	public function setMethod($m)
	{
		$this->method = $m;
	}
	
	public function setEntity($ent)
	{
		$this->entity = $ent;
	}

	public function getEntity(){

		return $this->entity;
	}

	public function setContent() // need JQuery
	{	/*	
		$url = $_SERVER['SERVER_HOST'] . dirname(__FILE__);

		$array = explode('/',$url); $count = count($array);

		$base = $array[$count-1];*/
/*		$baseroute="";
		$route = getcwd().'/'.$this->getBundlePath().'/Resources/views/'.$this->getEntity().'/index.html.php';
		$currentFileRoute = explode('/', $route);
		
		if(count($this->getBundlePath()) == 2) // si y a 3 fichiers (ex: src/Acme/Bundle)
		{
			$x = count($currentFileRoute);
			$x = $x - 1;
			$x = $x - 8;
			$baseroute = $currentFileRoute[$x];
			$route = '/'.$baseroute.'/'.$this->getBundlePath().'/Resources/views/'.$this->getEntity().'/index.html.php';
			
		}

		if(count($this->getBundlePath()) == 1)
		{
			
			$x = count($currentFileRoute);
			$x = $x - 1;  // parce que commence à 0 !
			$x = $x - 7;
			$baseroute = $currentFileRoute[$x];
			$route = '/'.$baseroute.'/'.$this->getBundlePath().'/Resources/views/'.$this->getEntity().'/index.html.php';
			
		}
*/
		$this->jxFunc = 'function '.$this->getEntity().'Ajax(route, div1){'." \r\n";
		$this->jxFunc .= '	if(div1!=null) var data = $(div1).serialize(); '." \r\n";
		$this->jxFunc .= '	if(div1==null) var data = $("'.$this->divFrom.'").serialize(); '." \r\n";
		$this->jxFunc .= " \r\n";
		$this->jxFunc .= ' 	$.ajax({'." \r\n";
		$this->jxFunc .= ' 		url: route,'." \r\n";
		$this->jxFunc .= ' 		type: "'.$this->method.'",'." \r\n";
		$this->jxFunc .= ' 		data: data,'." \r\n";
		$this->jxFunc .= ' 		success: function(r){'." \r\n";
		$this->jxFunc .= ' 			$("'.$this->divTo.'").html(r);'." \r\n";
		$this->jxFunc .= ' 		}'." \r\n";
		$this->jxFunc .= ' 	 });'." \r\n";
		$this->jxFunc .= ' };'." \r\n\r\n";
		$this->jxFunc .= 'function rUsure(route, div1) { '." \r\n";
		$this->jxFunc .= '	var c = confirm("Vous souhaiter vraiment effacer ces données !");'." \r\n";
		$this->jxFunc .= '	var data = $(div1).serialize();'." \r\n";
		$this->jxFunc .= '	if(c == true){'." \r\n";
		$this->jxFunc .= '		'.$this->getEntity().'Ajax(route, div1);'." \r\n";
		$this->jxFunc .= '	}'." \r\n";
		$this->jxFunc .= '}'." \r\n";
		$this->jxFunc .= 'function quit() {  '." \r\n";
		$this->jxFunc .= '	var c = confirm("Vous souhaiter vous déconnecter ? "); '." \r\n";
		$this->jxFunc .= ''." \r\n";
		$this->jxFunc .= '	if(c == true){ '." \r\n";
		$this->jxFunc .= '		document.location="../logout"; '." \r\n";
		$this->jxFunc .= '	} '." \r\n";
		$this->jxFunc .= '}'." \r\n";
	}

	public function getContent(){
		return $this->jxFunc;
	}

	public function writeFile()
	{	

		if(!file_exists("web/spmAjax")){ 
			mkdir("web/spmAjax"); 
			touch($this->path);
			chmod($this->path, 0777);
		}
		if(file_exists("web/spmAjax")){ 
			chmod("web/spmAjax", 0777);
			touch($this->path);
			chmod($this->path, 0777);
			if (is_writable($this->path)) 
			{
			    if (!$handle = fopen($this->path, 'w+')) {
			         echo "Impossible d'ouvrir le fichier ($this->path)";
			         exit;
			    }
			    // Ecrivons quelque chose dans notre fichier.
			    if (fwrite($handle, $this->getContent()) === FALSE) {
			        echo "Impossible d'écrire dans le fichier ($this->path)";
			        exit;
			    }

			  //  echo "\najax writed\r\n";

			    fclose($handle);

			} 
			else 
			{
			    echo "Le fichier $this->path n'est pas accessible en écriture.";
			}
		}

		if( chmod("web/spmAjax", 0755)&& !chmod($this->path, 0755)) echo "vérifier les droits du fichier 'web' ";
	}
		
		

}
?>