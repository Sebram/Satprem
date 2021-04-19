<?php
	
	/*
	 *  builder.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */

class 
{
	private $indexctrl;
	private $searchctrl;
	private $addctrl;
	private $editctrl;
	private $showctrl;
	private $showlistctrl;
	private $deletectrl;
	private $fields = array();
	private $entityname;
	private $header;
	private $footer;
	private $path;
	private $controls;
   	private $bundlename;
   	private $form;
	private $setfos;
	private $admin;


/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/


	public function setFos($state){
		$this->setfos = $state;
	}
	
	public function setEntityname($entity){
		$this->entityname = $entity;
	}

	public function setBundlenamespace($bundlePath)
	{
		$this->bundlename = $bundlePath;
	}

	public function setBundlename($bundlePath)
	{
		$this->bundlename = $bundlePath;
	}

	public function setFields($arrayFields){
		$this->fields = $arrayFields;
	}

	public function setPath($path){
		$this->path = $path;
	}


	public function getFields(){
		return $this->fields;
	}


/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/



	public function setAdminCtrl(){
		$this->admin = "<?php\r\n";
		$this->admin .= "namespace ".$this->getBundlenamespace()."\Controller;\r\n";
		$this->admin .= "\r\n";
		$this->admin .= "use Symfony\Bundle\FrameworkBundle\Controller\Controller;\r\n";
		$this->admin .= "use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;\r\n";
		$this->admin .= "\r\n";
		$this->admin .= "class AdminController extends Controller\r\n";
		$this->admin .= "{\r\n";
		$this->admin .= "    /**\r\n";
		$this->admin .= "     * @Route(\"/admin\")\r\n";
		$this->admin .= "     */\r\n";
		$this->admin .= "    public function indexAction()\r\n";
		$this->admin .= "    {\r\n";
		$this->admin .= "        return \$this->render('".$this->getBundlename().":Admin:admin.html.php');\r\n";
		$this->admin .= "    }\r\n";
		$this->admin .= "}\r\n";
		$this->admin .= "\r\n";
	}


	public function buildHeaderCtrl()
	{  
		$this->header='<?php '."\r\n";
		$this->header.='namespace '.$this->getBundlenamespace().'\Controller;'." \n\r";
		$this->header.='use Symfony\Bundle\FrameworkBundle\Controller\Controller;'." \n\r";
		$this->header.='use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;'." \n\r";
		if($this->getFosState()) $this->header.='use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;'." \n\r";
		$this->header.='use Symfony\Component\HttpFoundation\Request; '." \n\r";
		$this->header.='use Doctrine\ORM\EntityManager; '." \n\r";
		$this->header.='use '.$this->getBundlenamespace().'\Entity\\'.$this->getEntityname().'; '." \n\r";
		$this->header.='use Symfony\Component\HttpFoundation\Session\Session;'." \n\r";
		$this->header.='//use Symfony\Component\Form\FormFactory;'." \n\r";
		$this->header.='class '.$this->getEntityname().'Controller extends Controller'." \n\r";
		$this->header.='{';
		$this->indexctrl ="\r\n";
		$this->indexctrl.=' 		/** '."\r\n";
		$this->indexctrl.=' 		* @Route("/'.strtolower($this->getEntityname()).'/index", name="'.strtolower($this->getEntityname()).'_index") '."\r\n";
		if($this->getFosState()){	$this->indexctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
		$this->indexctrl.=' 		*/ '."\r\n";
		$this->indexctrl.=' 		public function indexAction(Request $request) '."\r\n";
		$this->indexctrl.=' 		{ '."\r\n";
		$this->indexctrl.=' 			$em=$this->getDoctrine()->getManager();'."\r\n";
		$this->indexctrl.=' 			$entities = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();'."\r\n";
 		$this->indexctrl.=' 			$entArray = array();'."\r\n";
 		$this->indexctrl.=' 			foreach ($entities as  $key => $value) {'."\r\n";
 		$this->indexctrl.=' 				$f = explode("\\\\", $value);'."\r\n";
 		$this->indexctrl.=' 				$entArray[$key] = $f[3];'."\r\n";
 		$this->indexctrl.=' 			}'."\r\n";
		$this->indexctrl.=' 			$data = $request->request->all(); '."\r\n";
		$this->indexctrl.=' 			return $this->render(\''.$this->getBundlename().':'.$this->getEntityname().':index.html.php\', array( '."\r\n";
		$this->indexctrl.=' 			\'entities\' => $entArray, '."\r\n";
		$this->indexctrl.=' 			\'name\' => \'Sebram\', '."\r\n";
		$this->indexctrl.=' 			\'value\' => \'1\' '."\r\n";
		$this->indexctrl.=' 			)); '."\r\n";
		$this->indexctrl.=' 		} '."\r\n";
	}	




	public function buildSearchCtrl()
	{
		$this->searchctrl ="\r\n\r\n";
		$this->searchctrl.='       /**'."\r\n";
	    $this->searchctrl.='       * @Route("/'.strtolower($this->getEntityname()).'/search", name="'.strtolower($this->getEntityname()).'_search")'."\r\n";
	   	if($this->getFosState()){	$this->searchctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
	    $this->searchctrl.='        */'."\r\n";
	    $this->searchctrl.='        public function searchAction(Request $request)'."\r\n";
	    $this->searchctrl.='        {'."\r\n";
	    $this->searchctrl.='	      $dta = $request->request->all();'."\r\n";
	    $this->searchctrl.="\r\n";
	    $this->searchctrl.='	      if(!empty($dta))$like = $dta[\'recherche\'];'."\r\n";
	    
	    $this->searchctrl.='	      $entityManager=$this->getDoctrine()->getRepository(\''.$this->getBundlename().':'.$this->getEntityname().'\');'."\r\n";
	    $this->searchctrl.='	      $queryBuilder = $entityManager->createQueryBuilder(\'s\');'."\r\n";
	    $this->searchctrl.='	      $queryBuilder->where(\'s.'.$this->getFields()[0]['name'].' LIKE :like\')'."\r\n";
	    for($x = 1;$x<count($this->getFields());$x++){
	    	$this->searchctrl.='	      ->orWhere(\'s.'.$this->getFields()[$x]['name'].' LIKE :like\')'."\r\n";
	    }
	    $this->searchctrl.='	      		->setParameter(\'like\', \'%\'.$like.\'%\')'."\r\n";
	    $this->searchctrl.='	      		->orderBy(\'s.id\', \'DESC\');'."\r\n";
	    $this->searchctrl.='	      $data = $queryBuilder->getQuery()->getResult();'."\r\n";
	    $this->searchctrl.='          /*->setMaxResults(\'10\');*/'."\r\n";
	    $this->searchctrl.="\r\n";    
	    $this->searchctrl.='	      return $this->render(\''.$this->getBundlename().':'.$this->getEntityname().':search.html.php\', array('."\r\n";
	    $this->searchctrl.='	      	\'data\'=>$data,'."\r\n";
	    $this->searchctrl.='	      	\'like\'=>$like,'."\r\n";
	    $this->searchctrl.='	      ));'."\r\n";
	    $this->searchctrl.='	  }'."\r\n";
	}

	

	public function buildForms()
	{ 	
		foreach ($this->getFields() as $key => $value) 
		{
	    	if($value['type'] == 'string') {
	    		$this->form.='		    ->add("'.$value['name'].'",   "text",array( '."\r\n";
	    		$this->form.='		          "attr" => array("class"=>"col-md-12 col-lg-12 col-sm-12 col-xs-12 form-control") '."\r\n";
		    	$this->form.='		          )) '."\r\n";	
	    	}
	    	if($value['type'] == 'text') {
	    		$this->form.='		    ->add("'.$value['name'].'", "textarea",array( '."\r\n";
			    $this->form.='		          "attr" => array("class"=>"textarea col-md-12 col-lg-12 col-sm-12 col-xs-12 form-control") '."\r\n";
			    $this->form.='		          )) '."\r\n";	
	    	}
		    if($value['type'] == 'date') {
	    		$this->form.='		    ->add("'.$value['name'].'", "date", array( '."\r\n";
			    $this->form.='		          "attr" => array("class"=>"col-md-12 col-lg-12 col-sm-12 col-xs-12 form-control") '."\r\n";
			    $this->form.='		          )) '."\r\n";
	    	}
		    if($value['type'] == 'boolean') {
	    		$this->form.='		    ->add("'.$value['name'].'", "checkbox", array( '."\r\n";
			    $this->form.='		          "attr" => array("class"=>"col-md-12 col-lg-12 col-sm-12 col-xs-12 form-control") '."\r\n";
			    $this->form.='		          )) '."\r\n";
	    	}    
	    }

	    $this->form.='					; ';
	    
	}


	public function buildAddCtrl()
	{	
		$this->addctrl.='	   /** '."\r\n";
	    $this->addctrl.='	    * @Route("/'.strtolower($this->getEntityname()).'/add", name="'.strtolower($this->getEntityname()).'_add")'."\r\n";
	    if($this->getFosState()){$this->addctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
	    $this->addctrl.='	    */'."\r\n";
	    $this->addctrl.='	   public function addAction(Request $request)'."\r\n";
	    $this->addctrl.='	   {'."\r\n";
	    $this->addctrl.='	       $data=$request->request->all();'."\r\n";
		$this->addctrl.=''."\r\n";  
	    $this->addctrl.='			$'.strtolower($this->getEntityname()).' = new '.$this->getEntityname().'();'."\r\n";
		$this->addctrl.=''."\r\n";
	    $this->addctrl.='	       $fm = $this->get("form.factory")->createBuilder("form", $'.strtolower($this->getEntityname()).');'."\r\n";
	    $this->addctrl.='	       '."\r\n";
	    $this->addctrl.='	       $fm'."\r\n";

	    $this->addctrl.=  $this->getZeForms();
	    
	    $this->addctrl.='	       $form = $fm->getForm();  '."\r\n";
		$this->addctrl.=''."\r\n"; 
	    $this->addctrl.='	       $message = \'<div class="alert alert-info" role="alert">Well ! Create a new '.strtolower($this->getEntityname()).'!</div>\';'."\r\n";
	    $this->addctrl.='	       '."\r\n";
	    $this->addctrl.='	       $form->handleRequest($request);'."\r\n";
		$this->addctrl.=''."\r\n"; 
	    $this->addctrl.='	       // On vérifie que les valeurs entrées sont correctes'."\r\n";
	    $this->addctrl.='	       // (Nous verrons la validation des objets en détail dans le prochain chapitre)'."\r\n";
	    $this->addctrl.='	       if ($form->isValid()) {'."\r\n";
	    $this->addctrl.='	         // On l\'enregistre notre objet $advert dans la base de données, par exemple'."\r\n";
	    $this->addctrl.='	           $em = $this->getDoctrine()->getManager();'."\r\n";
	    $this->addctrl.='	           $em->persist($'.strtolower($this->getEntityname()).');'."\r\n";
	    $this->addctrl.='	           $em->flush();'."\r\n";
	    $this->addctrl.='	           $success = \'<div class="alert alert-success" role="alert">'.$this->getEntityname().' perfectly added!</div>\';'."\r\n";
	    $this->addctrl.='	           return $this->render("'.$this->getBundlename().':'.$this->getEntityname().':add.html.php", array('."\r\n";
	    $this->addctrl.='	           "success" => $success, '."\r\n";
	    $this->addctrl.='	           "message" => \'\', '."\r\n";
	    $this->addctrl.='	           "form" => $form->createView(),'."\r\n";
	    $this->addctrl.='	           ));'."\r\n";
		$this->addctrl.=''."\r\n";
	    $this->addctrl.='	      }else'."\r\n";
	    $this->addctrl.='	        return $this->render("'.$this->getBundlename().':'.$this->getEntityname().':add.html.php", array('."\r\n";
	    $this->addctrl.='	          "success" => "",'."\r\n";
	    $this->addctrl.='	          "message" => $message, '."\r\n";
	    $this->addctrl.='	          "form" => $form->createView(),'."\r\n";
	    $this->addctrl.='	          ));'."\r\n";
	    $this->addctrl.='	  }'."\r\n";
	}





/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/





	public function buildEditCtrl()
	{
		$this->editctrl ='		/** '."\r\n";
	    $this->editctrl.='		 * @Route("/'.strtolower($this->getEntityname()).'/edit/{id}", name="'.strtolower($this->getEntityname()).'_edit") '."\r\n";
	    if($this->getFosState()){$this->editctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
	    $this->editctrl.='		 */ '."\r\n";
	    $this->editctrl.='		public function editAction(Request $request, $id) '."\r\n";
	    $this->editctrl.='		{ '."\r\n";
	    $this->editctrl.='		    $repository = $this '."\r\n";
	    $this->editctrl.='		      ->getDoctrine() '."\r\n";
	    $this->editctrl.='		      ->getManager() '."\r\n";
	    $this->editctrl.='		      ->getRepository("'.$this->getBundlename().':'.$this->getEntityname().'") '."\r\n";
	    $this->editctrl.='		      ; '."\r\n";
		$this->editctrl.='		 '."\r\n";
	    $this->editctrl.='		    $'.strtolower($this->getEntityname()).' = $repository->find($id); '."\r\n";
		$this->editctrl.='		 '."\r\n";
	    $this->editctrl.='		    $fm = $this->get("form.factory")->createBuilder("form", $'.strtolower($this->getEntityname()).'); '."\r\n";
	    $this->editctrl.='		    $fm '."\r\n";


	    $this->editctrl.= $this->getZeForms();
	    

	    $this->editctrl.='		   $form = $fm->getForm(); '."\r\n";
	    $this->editctrl.='		   $form->handleRequest($request); '."\r\n";
	    $this->editctrl.='		   if ($form->isValid()) { '."\r\n";
	    $this->editctrl.='		        // On l\'enregistre notre objet $advert dans la base de données, par exemple '."\r\n";
	    $this->editctrl.='		        $em = $this->getDoctrine()->getManager(); '."\r\n";
	    $this->editctrl.='		        $em->persist($'.strtolower($this->getEntityname()).'); '."\r\n";
	    $this->editctrl.='		        $em->flush(); '."\r\n";
	    $this->editctrl.='		        echo $success = \'<div class="alert alert-success" role="alert">'.$this->getEntityname().' perfectly edited!</div>\'; '."\r\n";
	    $this->editctrl.='		        return $this->render("'.$this->getBundlename().':'.$this->getEntityname().':edit.html.php", array( '."\r\n";
	    $this->editctrl.='		        "success" => $success,  '."\r\n";
	    $this->editctrl.='		        "message" => "",  '."\r\n";
	    $this->editctrl.='		        "form" => $form->createView(), '."\r\n";
	    $this->editctrl.='		        "id"=>$id, '."\r\n";
	    $this->editctrl.='		        )); '."\r\n";
	    $this->editctrl.='		    }else{ '."\r\n";
	    $this->editctrl.='		        $message = \'<div class="alert alert-info" role="alert">Well ! Edit that '.strtolower($this->getEntityname()).'!</div>\'; '."\r\n";
	    $this->editctrl.='		            return $this->render("'.$this->getBundlename().':'.$this->getEntityname().':edit.html.php", array( '."\r\n";
	    $this->editctrl.='		              "message" => $message,  '."\r\n";
	    $this->editctrl.='		              "success" => "",  '."\r\n";
	    $this->editctrl.='		            "form" => $form->createView(), '."\r\n";
	    $this->editctrl.='		            "id"=>$id, '."\r\n";
	    $this->editctrl.='		        )); '."\r\n";
	    $this->editctrl.='		      } '."\r\n";
	    $this->editctrl.='		} '."\r\n";
	}




/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/
	

	

	public function buildShowCtrl()
	{
		$this->showctrl ='		/** '."\r\n";
	    $this->showctrl.='		 * @Route("/'.strtolower($this->getEntityname()).'/show/{id}" , name="'.strtolower($this->getEntityname()).'_show") '."\r\n";
	    if($this->getFosState()){$this->showctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
	    $this->showctrl.='		 */ '."\r\n";
	    $this->showctrl.='		public function showAction($id) '."\r\n";
	    $this->showctrl.='		{ '."\r\n";
	    $this->showctrl.='		    $data = $this->getDoctrine()->getRepository("'.$this->getBundlename().':'.$this->getEntityname().'")->find($id); '."\r\n";
	    $this->showctrl.='		    return $this->render("'.$this->getBundlename().':'.$this->getEntityname().':show.html.php", array( '."\r\n";
	    $this->showctrl.='		       "data" => $data,  '."\r\n";
	    $this->showctrl.='		    )); '."\r\n";
	    $this->showctrl.='		}	 '."\r\n";

	}


	


/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/




	public function buildShowListCtrl()
	{
		$this->showlistctrl=' ';
		$this->showlistctrl.='			/** '."\r\n";
		$this->showlistctrl.='		     * @Route("'.strtolower($this->getEntityname()).'/show_list", name="'.strtolower($this->getEntityname()).'_show_list") '."\r\n";
		if($this->getFosState()){$this->showlistctrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
		$this->showlistctrl.='		     */ '."\r\n";
		$this->showlistctrl.='		    public function show_listAction() '."\r\n";
		$this->showlistctrl.='		    {    '."\r\n";
		$this->showlistctrl.='		        $entityManager=$this->getDoctrine()->getRepository(\''.$this->getBundlename().':'.$this->getEntityname().'\'); '."\r\n";    
		$this->showlistctrl.='		        $queryBuilder = $entityManager->createQueryBuilder(\'s\'); '."\r\n";
		$this->showlistctrl.='		        $queryBuilder->orderBy(\'s.id\', \'DESC\'); '."\r\n";
		$this->showlistctrl.='		        $data = $queryBuilder->getQuery()->getResult(); '."\r\n";
		$this->showlistctrl.='		        return $this->render(\''.$this->getBundlename().':'.$this->getEntityname().':show_list.html.php\', array( '."\r\n";
		$this->showlistctrl.='		            \'data\' => $data,  '."\r\n";
		$this->showlistctrl.='		        )); '."\r\n";
		$this->showlistctrl.='		    } '."\r\n";
	}




/*--------------------------------------------------------------------*/
/*--------------------------------------------------------------------*/




	public function buildDeleteCtrl()
	{
		$this->deletectrl ='		/** '."\r\n"; 
		$this->deletectrl.='		 * @Route("/'.strtolower($this->getEntityname()).'/delete/{id}", name="'.strtolower($this->getEntityname()).'_delete") '."\r\n"; 
		if($this->getFosState()){$this->deletectrl.=' 		* @Security("has_role(\'ROLE_ADMIN\')")'."\r\n";}
		$this->deletectrl.='		 */ '."\r\n"; 
		$this->deletectrl.='		public function deleteAction(Request $request, $id) '."\r\n"; 
		$this->deletectrl.='		{    '."\r\n"; 
		$this->deletectrl.='			$data = $request->request->all(); '."\r\n"; 
		$this->deletectrl.='		  	$entityManager=$this->getDoctrine()->getManager(); '."\r\n"; 
		$this->deletectrl.='		  	$'.strtolower($this->getEntityname()).' = $entityManager->getRepository(\''.$this->getBundlename().':'.$this->getEntityname().'\')->find($id);  '."\r\n"; 
		$this->deletectrl.='		  	if(!$'.strtolower($this->getEntityname()).'){ '."\r\n"; 
		$this->deletectrl.='		  	  throw $this->createNotFoundException("No '.$this->getEntityname().'s found for id ".$id." !"); '."\r\n"; 
		$this->deletectrl.='		  	}		 '."\r\n"; 
		$this->deletectrl.='		  	$entityManager->remove($'.strtolower($this->getEntityname()).');	 '."\r\n"; 
		$this->deletectrl.='		  	$entityManager->flush(); '."\r\n"; 
		$this->deletectrl.='		  	$message = \'<div class="alert alert-success" role="alert">Your '.strtolower($this->getEntityname()).' is deleted!</div>\'; '."\r\n"; 	
		$this->deletectrl.='		  	return $this->render(\''.$this->getBundlename().':'.$this->getEntityname().':delete.html.php\', array( '."\r\n"; 
		$this->deletectrl.='		  	    "message"=>$message, '."\r\n"; 
		$this->deletectrl.='		  	)); '."\r\n"; 
		$this->deletectrl.='		} '."\r\n"; 
	}


	public function buildFooterCtrl(){
		$this->footer = '}';
	}


	public function buildIndexCtrl()
	{
		
	}


	public function WriteCtrl()
	{
		$create = $this->getControllers();
		touch($this->path);
		
		if (is_writable($this->path)) 
		{
		    if (!$handle = fopen($this->path, 'w+')) {
		         echo "Impossible d'ouvrir le fichier ($this->path)";
		         exit;
		    }
		    // Ecrivons quelque chose dans notre fichier.
		    if (fwrite($handle, $create) === FALSE) {
		        echo "Impossible d'écrire dans le fichier ($this->path)";
		        exit;
		    }

		    //echo "\r\Ctrl writed\r\n";

		    fclose($handle);

		} 
		else 
		{
		    echo "Le fichier ".$this->path." n'est pas accessible en écriture.";
		}
	}


	public function WriteAdminCtrl($default, $chemin)
	{
		$create = $default;
		touch($chemin);
		
		if (is_writable($chemin)) 
		{
		    if (!$handle = fopen($chemin, 'w+')) {
		         echo "Impossible d'ouvrir le fichier ($this->path)";
		         exit;
		    }
		    // Ecrivons quelque chose dans notre fichier.
		    if (fwrite($handle, $create) === FALSE) {
		        echo "Impossible d'écrire dans le fichier ($this->path)";
		        exit;
		    }

		    //echo "\r\Ctrl writed\r\n";

		    fclose($handle);

		} 
		else 
		{
		    echo "Le fichier ".$chemin." n'est pas accessible en écriture.";
		}
	}


	public function getFosState(){
		return $this->setfos;
	}

	public function getBundlenamespace()
	{	$name=""; 
		$b = explode( '/', $this->bundlename );
		if(count($b) == 2) $name = $b[1];	
		if(count($b) == 3) $name = $b[1].'\\'.$b[2];

		return $name;
	}


	public function getBundlename()
	{	$name=""; 
		$b = explode( '/', $this->bundlename );
		if(count($b) == 2) $name = $b[1];	
		if(count($b) == 3) $name = $b[1].$b[2];

		return $name;
	}

	public function getEntityname(){
		return $this->entityname;
	}

	
	public function getHeader(){
		return $this->header;
	}


	public function getIndexCtrl(){
		return $this->indexctrl;
	}

	public function getSearchCtrl(){
		return $this->searchctrl;
	}

	public function getZeForms(){
		return $this->form;
	}

	public function getAddCtrl()
	{
		return $this->addctrl;
	}
	
	public function getEditCtrl(){
		return $this->editctrl;
	}

	public function getShowCtrl(){
		return $this->showctrl;
	}

	public function getShowListCtrl(){
		return $this->showlistctrl;
	}

	public function getDeleteCtrl(){
		return $this->deletectrl;
	}
	

	public function getAdminCtrl(){
		return $this->admin;
	}

	public function getFooter(){
		return $this->footer;
	}

	public function getControllers(){
		$this->controls .= "";
		$this->controls .= $this->getHeader()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getIndexCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getSearchCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getAddCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getEditCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getShowCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getShowListCtrl()."\r\n\r\n\r\n\r\n";
		$this->controls .= $this->getDeleteCtrl()."\r\n\r\n\r\n\r\n";
		
		$this->controls .= $this->getFooter()."\r\n\r\n\r\n\r\n";
		return $this->controls;
	}


}