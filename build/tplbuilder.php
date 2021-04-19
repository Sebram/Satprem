<?php
	
	/*
	 *  tplbuilder.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */
	
class Tplbuilder{


	private $layout;
	private $index;
	private $search;
	private $add;
	private $edit;
	private $show;
	private $showlist;
	private $delete;
	private $entityname;
	private $template;
	private $path;
	private $projectname;
	private $fields = array();
	private $setFos;
	private $adminpage;

	public function setFos($state){
		$this->setfos = $state;
	}

	public function setFields($arrayFields){
		$this->fields = $arrayFields;
	}

	public function setProjectname($pro){
		$this->projectname = $pro;
	}

	public function setEntityname($ent){
		$this->entityname = $ent;
	}
	
	public function setField(){

	}

	public function getField(){
		
	}




	public function setAdminPage(){  // Satprem login page  
		$this->adminpage = '<?php $view->extend(\'::layout.html.php\');?>'." \r\n";
		$this->adminpage .=' <div class="well">'." \r\n";
		$this->adminpage .=' 		<h4>Satprem Admin </h4>'." \r\n";
		$this->adminpage .=' 	</div> '." \r\n";
		$this->adminpage .=' <div class="container">'." \r\n";
		$this->adminpage .=' 		<script> loginAjax("<?php echo $view[\'router\']->path(\'fos_user_security_login\') ?>", ctrl.getN()); </script>'." \r\n";
		$this->adminpage .=' 	<div id="reponse"> </div> '." \r\n";
		$this->adminpage .=' 	<div id="routeDisplay"> </div> '." \r\n";
		$this->adminpage .=' </div>'." \r\n";
	}



	public function layoutTpl($entities)
	{	
		$this->layout ='<!DOCTYPE html>'." \r\n";
		$this->layout.='<html>'." \r\n";
		$this->layout.='    <head>'." \r\n";
		$this->layout.='        <meta charset="utf-8">'." \r\n";
		$this->layout.='        <meta http-equiv="X-UA-Compatible" content="IE=edge">'." \r\n";
		$this->layout.='        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'." \r\n";
		$this->layout.='        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">'." \r\n";
		$this->layout.=''." \r\n";
		$this->layout.='        <title><?php $view[\'slots\']->output(\'title\', \''.strtolower($this->getProjectname()).'\') ?></title>'." \r\n";
		$this->layout.=''." \r\n";
		$this->layout.='        <link rel="stylesheet" href=\'<?php echo $_SERVER[\'BASE\']?>/bootstrap/css/bootstrap.css\' /> '." \r\n";
		$this->layout.='        <link rel="stylesheet" href="<?php echo $_SERVER[\'BASE\']?>/spmCss/style.css" /> '." \r\n";
		$this->layout.='        <script src="<?php echo $_SERVER[\'BASE\']?>/jquery/dist/jquery.js" type="text/javascript"></script>'." \r\n";
		$this->layout.='        <script src="<?php echo $_SERVER[\'BASE\']?>/bootstrap/js/bootstrap.js" type="text/javascript"></script>'." \r\n";
		$this->layout.='        '." \r\n";
		if(count($entities) > 1){
			foreach($entities as $key => $ent){  // to get all entityname 
				$ex = explode('.', $ent);
				$val[$key] = $ex[0]; /* echo $val[$key];*/
				$this->layout .='        <script src="<?php echo $_SERVER[\'BASE\']?>/spmAjax/'.$val[$key].'.js" type="text/javascript"></script>'." \r\n";
			}
		}
		if($this->getFosState()){
			$this->layout.='        <script src="<?php echo $_SERVER[\'BASE\']?>/spmAjax/login.js" type="text/javascript"></script>'." \r\n";
		}
		$this->layout.='        <script src="<?php echo $_SERVER[\'BASE\']?>/spmAjax/'.$this->getEntityname().'.js" type="text/javascript"></script>'." \r\n";
		$this->layout.='        <!--[if lt IE 9]>'." \r\n";
		$this->layout.='            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>'." \r\n";
		$this->layout.='        <![endif]-->'." \r\n";
		$this->layout.='  </head>'." \r\n";
		$this->layout.='  <body class="hold-transition skin-blue sidebar-mini">'." \r\n";
		$this->layout.='     '." \r\n";
		$this->layout.='           <?php $view[\'slots\']->output(\'_content\'); ?>'." \r\n";
		$this->layout.='        '." \r\n";
		$this->layout.='  </body>'." \r\n";
		$this->layout.='</html>'." \r\n";
	}



	public function adminTpl()  // Satprem Admin page
	{  
		$this->index='<?php $view->extend(\'::layout.html.php\');?>'." \r\n";
		$this->index.=' <div class="container">'." \r\n";
		$this->index.=' 	<div class="row"><br/><br/>'." \r\n";
		$this->index.=' 		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">'." \r\n";
		$this->index.=' 			<ul class="nav nav-pills nav-stacked nav-pills-stacked-example"> '." \r\n";
		$this->index.=' 				<li class="active">'." \r\n";
		$this->index.=' 					<a href="#">'." \r\n";
		$this->index.=' 						<i class="glyphicons glyphicons-circle-o"></i> '." \r\n";
		$this->index.=' 						<b>Sat</b>prem Admin<br/>'." \r\n";
		$this->index.=' 						Manage <b>'.strtoupper($this->getEntityname()).' contents</b>'." \r\n";
		$this->index.=' 					</a>'." \r\n";
		$this->index.=' 				</li> '." \r\n";

		$this->index.=' 				<li> '." \r\n";
		$this->index.=' 					<a href="javascript:void(0);" id="menu"> '." \r\n";
		$this->index.=' 						<span style="font-size:23px" class="glyphicon glyphicon-list"></span>  MANAGE CONTENT  '." \r\n";
		$this->index.=' 					</a>'." \r\n";
		$this->index.=' 					<ul id="ulActive" style="display:none;"> '." \r\n";
		$this->index.=' 					<?php foreach($entities as $key => $ent){  '." \r\n";
		$this->index.=' 						$smallent[$key] = strtolower($ent);  '." \r\n";
		$this->index.=' 						if($smallent[$key] != "user" && $smallent[$key] != "group"){ ?>'." \r\n";
		$this->index.=' 						<li> <a href="../<?=$smallent[$key]?>/index"><?=ucfirst($smallent[$key])?></a></li> '." \r\n";
		$this->index.=' 					<?php } } ?>	 '." \r\n";
		$this->index.=' 					</ul> '." \r\n";
		$this->index.=' 				</li>'." \r\n";

		$this->index.=' 				<li role="presentation" > '." \r\n";
		$this->index.='						<a href="javascript:void(0);" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_show_list\') ?>\' )"> '." \r\n";
		$this->index.='							<span style="font-size:26px" class="glyphicon glyphicon-eye-open"></span> SHOW'." \r\n";
		$this->index.='						</a>'." \r\n";
		$this->index.=' 				</li> '." \r\n";
		$this->index.='					<li role="presentation">'." \r\n";
		$this->index.='						<a href="javascript:void(0);" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_add\') ?>\')" > '." \r\n";
		$this->index.='							<span style="font-size:26px" class="glyphicon glyphicon-plus-sign"></span> ADD'." \r\n";
		$this->index.='						</a>'." \r\n";
		$this->index.='					</li> '." \r\n";
		$this->index.='					<li>'." \r\n";
		$this->index.='						<input type="text" id="recherche" onchange="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_search\')?>\', \'#recherche\')" name="recherche" class="form-control" placeholder="Rechercher">'." \r\n";
		$this->index.='					</li>'." \r\n";

		if($this->getFosState()){
			$this->index.=' 			<?php if ($view[\'security\']->isGranted(\'ROLE_ADMIN\')): ?>'." \r\n";
			$this->index.=' 				<li role="presentation" > '." \r\n";
			$this->index.='						<a href="javascript:void(0);" onclick="quit()" >'." \r\n";
			$this->index.='							<span style="font-size:26px" class="glyphicon glyphicon-log-out"></span> LOGOUT'." \r\n";
			$this->index.='						</a>'." \r\n";
			$this->index.=' 				</li> '." \r\n";
			$this->index.=' 			<?php endif ?>'." \r\n";
			$this->index.=' 			<?php if (!$view[\'security\']->isGranted(\'ROLE_ADMIN\')): ?>'." \r\n";
			$this->index.=' 				<li role="presentation" > '." \r\n";
			$this->index.='						<a href="javascript:void(0);" onclick="loginAjax(\'<?php echo $view[\'router\']->path(\'fos_user_security_login\') ?>\', ctrl.getN())" >'." \r\n";
			$this->index.='							<span style="font-size:26px" class="glyphicon glyphicon-log-in"></span> LOGIN'." \r\n";
			$this->index.='						</a>'." \r\n";
			$this->index.=' 				</li> '." \r\n";
			$this->index.=' 			<?php endif ?>'." \r\n";
		}

		$this->index.='				</ul>'." \r\n";
		$this->index.='			</div>'." \r\n";
		$this->index.='			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">'." \r\n";
		$this->index.='				<div id="routeDisplay" >'." \r\n";
		$this->index.='				</div>'." \r\n";
		$this->index.='				<div id="reponse">'." \r\n";
		$this->index.='				</div>'." \r\n";
		$this->index.='			</div>	'." \r\n";  
		$this->index.='		</div>	'." \r\n";		
		$this->index.='	</div>'." \r\n";
		$this->index.='	<script>	'." \r\n";		
		$this->index.='		var i=1;'." \r\n";
		$this->index.='		$("#menu").click(function() {'." \r\n";					
		$this->index.='			if(i%2!=0){'." \r\n";
		$this->index.='				$( "#ulActive" ).fadeIn( "speed", function() {'." \r\n";
		$this->index.='				// Animation complete'." \r\n";
		$this->index.='				})'." \r\n";
		$this->index.='			}'." \r\n";
		$this->index.='		if(i%2==0){'." \r\n";
		$this->index.='			$( "#ulActive" ).fadeOut( "speed", function() {'." \r\n";
		$this->index.='			// Animation complete'." \r\n";
		$this->index.='			})'." \r\n";
		$this->index.='		}'." \r\n";
		$this->index.='		i++;				'." \r\n";
		$this->index.='	});	'." \r\n";
		$this->index.='</script>'." \r\n";
	}	


	public function addTpl()
	{
		$this->add='<div id="routediv">'." \r\n";
		$this->add.='	<div class="row">'." \r\n";
		$this->add.='		<div class="col-lg-12">'." \r\n";
		$this->add.='				<?php if(isset($message)) echo $message; ?> '." \r\n";
		$this->add.='				<?php if(isset($success)) echo $success; ?> '." \r\n";
		$this->add.='		</div>'." \r\n";
		$this->add.='	</div>'." \r\n";
		$this->add.='	<div class="row well">'." \r\n";
		$this->add.='		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">'." \r\n";
		$this->add.='			<form id="formAdd">'." \r\n";
		$this->add.='					<?php echo $view[\'form\']->block($form, \'form\') ?>'." \r\n";
		$this->add.='					<div class="col-md-3 col-lg-3 col-sm-3 col-xs-3"></div>'." \r\n";
		$this->add.='					<br/><br/>	'." \r\n";
		$this->add.='					<button type="submit" class="btn btn-info col-lg-6" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_add\')?>\', \'#formAdd\')">'." \r\n";
		$this->add.='					Save'." \r\n";
		$this->add.='					</button>'." \r\n";
		$this->add.='			</form>'." \r\n";
		$this->add.='			</div>'." \r\n";
		$this->add.='		</div>'." \r\n";
		$this->add.='	</div>		'." \r\n";

		foreach( $this->fields as $f ){

			if($f['type'] == "text"){
				$this->add.='	<script>'." \r\n";
				$this->add.='		$(\'#form_'.$f['name'].'\').focus(function()'." \r\n";
				$this->add.='	    {'." \r\n";
				$this->add.='	       $(this).animate({\'height\': \'185px\'}, \'speed\' );//Expand the textarea on clicking on it'." \r\n";
				$this->add.='	       return false;'." \r\n";
				$this->add.='	     });'." \r\n";
				$this->add.='	</script>'." \r\n\r\n";	
			}
			
		}

		$this->add.='</div>'." \r\n";
	}


	public function searchTpl()
	{
		$this->search ='<br/>'." \r\n";
		$this->search.='<?php foreach($data as $d){ ?>'." \r\n";
		$this->search.='	<div class="row well">'." \r\n";

		$this->search.='		<span class="col-md-3 col-lg-3 col-sm-3 col-xs-3">'." \r\n";
		$this->search.='			<?php $id = $d->getId(); ?>'." \r\n";
		$this->search.='				<div class="btn btn-info" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_edit\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->search.='					 <span class="glyphicon glyphicon-pencil"></span> '." \r\n";
		$this->search.='				</div>'." \r\n";
		$this->search.='		</span>'." \r\n";

		
		$this->search.='		<span class="col-md-3 col-lg-3 col-sm-3 col-xs-3">'." \r\n";
		$this->search.='			<?php $id = $d->getId(); ?>'." \r\n";
		$this->search.='				<div class="btn btn-info" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_show\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->search.='					<span class="glyphicon glyphicon-eye-open"></span>'." \r\n";
		$this->search.='				</div>'." \r\n";
		$this->search.='		</span>'." \r\n";

		foreach($this->getFields() as $f )
		{
			$this->search.='		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">'." \r\n";
			$this->search.='				<br/><strong>'.ucfirst($f['name']).': </strong><br/><?=$d->get'.ucfirst($f['name']).'()?>'." \r\n";
			$this->search.='		</div><br/>'." \r\n";
    	}
		$this->search.='<hr/>	</div>'." \r\n";
		$this->search.='<?php }?>'." \r\n";
		$this->search.='<!-- search.html.php -->'." \r\n";
	}


	public function editTpl()
	{  
		$this->edit ='';
		$this->edit.='<div id="routediv">'." \r\n";
		$this->edit.='	<div class="row">'." \r\n";
		$this->edit.='		<div class="col-lg-12">'." \r\n";
		$this->edit.='				<?=$message?>'." \r\n";
		$this->edit.='		</div>'." \r\n";
		$this->edit.='	</div>'." \r\n";
		$this->edit.='	<div class="row well">'." \r\n";
		$this->edit.='		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">'." \r\n";
		$this->edit.='			<form id="formEdit">'." \r\n";
		$this->edit.='					<?php echo $view[\'form\']->block($form, \'form\') ?>'." \r\n";
		$this->edit.='					<div class="col-md-3 col-lg-3 col-sm-3 col-xs-3"></div>'." \r\n";
		$this->edit.='					<br/><br/>	'." \r\n";
		$this->edit.='					<button type="submit" class="btn btn-info col-lg-6" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_edit\', array(\'id\'=>$id)) ?>\', \'#formEdit\')">'." \r\n";
		$this->edit.='					Save'." \r\n";
		$this->edit.='					</button>'." \r\n";
		$this->edit.='			</form>'." \r\n";
		$this->edit.='			</div>'." \r\n";
		$this->edit.='		</div>'." \r\n";
		$this->edit.='	</div>		'." \r\n";
		$this->edit.='	<script>'." \r\n";
		$this->edit.='		$(\'#form_'.strtolower($this->getEntityname()).'\').focus(function()'." \r\n";
		$this->edit.='	    {'." \r\n";
		$this->edit.='	       $(this).animate({\'height\': \'185px\'}, \'speed\' );//Expand the textarea on clicking on it'." \r\n";
		$this->edit.='	       return false;'." \r\n";
		$this->edit.='	     });'." \r\n";
		$this->edit.='	</script>'." \r\n";
		$this->edit.='</div>'." \r\n";
	}




	public function showTpl(){
		$this->show ='<br/>'." \r\n";
		$this->show.='<div id="routediv"> '." \r\n";
		$this->show.='	<div class="row well">'." \r\n";

		$this->show.='			<span class="col-md-3 col-lg-3 col-sm-3 col-xs-3">'." \r\n";
		$this->show.='				<?php $id = $data->getId(); ?>'." \r\n";
		$this->show.='				<div class="btn btn-danger" onclick="rUsure(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_delete\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->show.='					<span class="glyphicon glyphicon-trash"></span>'." \r\n";
		$this->show.='				</div>'." \r\n";
		$this->show.='			</span>'." \r\n";

		$this->show.='			<span class="col-md-2 col-lg-2 col-sm-2 col-xs-2">'." \r\n";
		$this->show.='				<?php $id = $data->getId(); ?>'." \r\n";
		$this->show.='				<div class="btn btn-info" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_edit\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->show.='					 <span class="glyphicon glyphicon-pencil"></span> '." \r\n";
		$this->show.='				</div>'." \r\n";
		$this->show.='			</span>'." \r\n";

		

		foreach($this->getFields() as $f )
		{
			$this->show.='		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">'." \r\n";
			$this->show.='				<br/><strong>'.ucfirst($f['name']).': </strong><br/><?=$data->get'.ucfirst($f['name']).'()?>'." \r\n";
			$this->show.='		</div><br/>'." \r\n";
    	}
		$this->show.='	<hr/>	</div>'." \r\n";
		$this->show.='</div>'." \r\n";


	}

	public function showListTpl(){

		$this->showlist ='<br/>'." \r\n";
		$this->showlist.='<div id="routediv"> '." \r\n";
		$this->showlist.='		<?php foreach($data as $d){ ?>'." \r\n";
		$this->showlist.='		<div class="row well">'." \r\n";
		$this->showlist.='			<span class="col-md-3 col-lg-3 col-sm-3 col-xs-3">'." \r\n";
		$this->showlist.='				<?php $id = $d->getId(); ?>'." \r\n";
		$this->showlist.='					<div class="btn btn-info" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_edit\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->showlist.='						 <span class="glyphicon glyphicon-pencil"></span> '." \r\n";
		$this->showlist.='					</div>'." \r\n";
		$this->showlist.='			</span>'." \r\n";
		$this->showlist.='			<span class="col-md-3 col-lg-3 col-sm-3 col-xs-3">'." \r\n";
		$this->showlist.='					<?php $id = $d->getId(); ?>'." \r\n";
		$this->showlist.='					<div class="btn btn-info" onclick="'.$this->getEntityname().'Ajax(\'<?php echo $view[\'router\']->path(\''.strtolower($this->getEntityname()).'_show\', array( \'id\' => $id )) ?>\' )"> '." \r\n";
		$this->showlist.='						<span class="glyphicon glyphicon-eye-open"></span>'." \r\n";
		$this->showlist.='					</div>'." \r\n";
		$this->showlist.='			</span>'." \r\n";
		
		foreach($this->getFields() as $f )
		{
			$this->showlist.='		<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">'." \r\n";
			$this->showlist.='				<br/><strong>'.ucfirst($f['name']).': </strong><br/><?=$d->get'.ucfirst($f['name']).'()?>'." \r\n";
			$this->showlist.='		</div><br/>'." \r\n";
    	}

		$this->showlist.='		</div>'." \r\n";
		$this->showlist.='		<?php }?>'." \r\n";
		$this->showlist.='</div>'." \r\n";
		$this->showlist.=''." \r\n";
	}



	public function deleteTpl()
	{
		$this->delete ='<div id="routediv">'." \r\n";
		$this->delete.='   '." \r\n";
		$this->delete.='	<div class="row">'." \r\n";
		$this->delete.='		<div class="col-lg-12">'." \r\n";
		$this->delete.='			'." \r\n";
		$this->delete.='				<div class="well">'." \r\n";
		$this->delete.='					<?=$message?>'." \r\n";
		$this->delete.='				</div>'." \r\n";
		$this->delete.='				'." \r\n";
		$this->delete.='		</div>'." \r\n";
		$this->delete.='	</div>'." \r\n";
		$this->delete.=''." \r\n";
		$this->delete.='</div>'." \r\n";

	}



	public function getFields()
	{
		return $this->fields;
	}

	public function getProjectname()
	{
		return $this->projectname;
	}

	public function getFosState(){
		return $this->setfos;
	}

	public function getEntityname(){
		return $this->entityname;
	}
	public function getLayout(){
		return $this->layout;
	}
	public function getAdmin(){
		return $this->index;
	}
	public function getAdd(){
		return $this->add;
	}
	public function getSearch(){
		return $this->search;
	}
	public function getEdit(){
		return $this->edit;
	}
	public function getShow(){
		return $this->show;
	}
	public function getShowList(){
		return $this->showlist;
	}
	public function getDelete(){
		return $this->delete;	
	}

	public function getAdminPage(){
		return $this->adminpage;	
	}

	public function WriteTpl($template, $url)
	{

		$create = $template;
		touch($url);
		
		if (is_writable($url)) 
		{
		    if (!$handle = fopen($url, 'w+')) {
		         echo "Impossible d'ouvrir le fichier ($url)";
		         exit;
		    }
		    // Ecrivons quelque chose dans notre fichier.
		    if (fwrite($handle, $create) === FALSE) {
		        echo "Impossible d'écrire dans le fichier ($url)";
		        exit;
		    }

	//	    echo "\r\n Writed \r\n";

		    fclose($handle);

		} 
		else 
		{
		    echo "Le fichier ".$url." n'est pas accessible en écriture.";
		}
	}


}



