<?php
	/*
	 *  spmfos.php
	 *  Satprem Admin
	 *
	 *  Created by Sebastien Koné on 02/2016.
	 *  Copyright © 2016 weborium/Sebastien Koné. All rights reserved.
	 */
class spmfos{

	private $bundle_root_file;
	private $bundlename;
	private $cmd_user_bundle;
	private $cmd_user_entity;
	private $doctrine_update;
	private $doctrine_force;
	private $userbundle_file;
	private $security;
	private $routes;
	private $config;
	private $kernel;
	private $create_new_user;
	private $ajax_func;
	private $login_twig;
	private $composer;
	private $downlad_fos;
	private $user_ent;
	private $bundle_root_name;
	private $first_entity;
	private $promote_user;

	public function setBundleRootName($bundle_path){
		$tab = explode('/', $bundle_path);
		$this->bundle_root_name = $tab[1];
	}

	public function setBundlenamespace($bundle_path)
	{
		$this->bundlename = $bundle_path;
	}

	public function setFirstEntity($entity){
		$this->first_entity = $entity;
	}


	//1
	public function buildUserBndl(){
		$this->cmd_user_bundle="php app/console generate:bundle --namespace=".$this->getBundleRootName()."/UserBundle --bundle-name=".$this->getBundleRootName()."UserBundle --format=annotation --no-interaction";
		return true; // TODO write new user bundle and rewrite it to 'protect user ' in place of 'private user'
	}

	//2
	public function buildUserEnt(){
		$this->cmd_user_entity="php app/console doctrine:generate:entity --no-interaction --entity=".$this->getBundleRootName()."UserBundle:User --format=xml"; 
	}

	//3
	public function docUpdate(){
		$this->doctrine_update = "php app/console doctrine:schema:update --dump-sql";		
	}
	public function docForce(){
		$this->doctrineforce = "php app/console doctrine:schema:update --force";
	}

	//4
	public function editUserbundleFile(){
		$this->userbundle_file.="<?php\r\n";
		$this->userbundle_file.="namespace ".$this->getBundleRootName()."\UserBundle;\r\n";
		$this->userbundle_file.="use Symfony\Component\HttpKernel\Bundle\Bundle;\r\n";
		$this->userbundle_file.="class ".$this->getBundleRootName()."UserBundle extends Bundle\r\n";
		$this->userbundle_file.="{\r\n";
		$this->userbundle_file.="  public function getParent()\r\n";
		$this->userbundle_file.="  {\r\n";
		$this->userbundle_file.="    return 'FOSUserBundle';\r\n";
		$this->userbundle_file.="  }\r\n";
		$this->userbundle_file.="}\r\n";
	}


	//5 ****************************************** 
	public function addInComposer($filepath){
		$composerJson = file_get_contents("composer.json");
		$tabJson = json_decode($composerJson);
		$tabJson->{"require"}->{"friendsofsymfony/user-bundle"} = "dev-master";
		$tabJson->{"autoload"}->{"psr-4"} = array(""=>"src/");
		file_put_contents($filepath, json_encode($tabJson , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES )); 
	} 			//5 ****************************************** 

	//6
	public function addInKernel($filepath){
		$mykernel = file_get_contents($filepath);
		$kernelexplode1 = explode('array(', $mykernel);
		$kernelexplode2 = explode(');', $kernelexplode1[1]);
		$bundleList = $kernelexplode2[0]."     	new FOS\UserBundle\FOSUserBundle(),";

		$this->kernel = '<?php'."\n";
		$this->kernel .= 'use Symfony\Component\HttpKernel\Kernel;'."\n";
		$this->kernel .= 'use Symfony\Component\Config\Loader\LoaderInterface;'."\n";
		$this->kernel .= 'class AppKernel extends Kernel'."\n";
		$this->kernel .= '{'."\n";
		$this->kernel .= '    public function registerBundles()'."\n";
		$this->kernel .= '    {'."\n";
		$this->kernel .= '        $bundles = array(';
		$this->kernel .=    $bundleList."\n";
		$this->kernel .= '	);'."\n";
		
		$this->kernel .=' 	if (in_array($this->getEnvironment(), array(\'dev\', \'test\'), true)) {'."\n";
		$this->kernel .=' 		$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();'."\n";
		$this->kernel .=' 		$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();'."\n";
		$this->kernel .=' 		$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();'."\n";
		$this->kernel .=' 		$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();'."\n";
		$this->kernel .=' 	}'."\n";
		$this->kernel .=' '."\n";
		$this->kernel .=' 	return $bundles;'."\n";
		$this->kernel .=' }'."\n";
		$this->kernel .=' '."\n";
		$this->kernel .=' public function registerContainerConfiguration(LoaderInterface $loader)'."\n";
		$this->kernel .=' {'."\n";
		$this->kernel .=' 	$loader->load($this->getRootDir().\'/config/config_\'.$this->getEnvironment().\'.yml\'); '."\n";
		$this->kernel .=' }'."\n";
		$this->kernel .=' }	'."\n";
		
		file_put_contents($filepath, $this->kernel);

	}

	public function getKernel(){
		return $this->kernel;
	}

	//7
	public function config($filepath){ // config.yml
		$this->config .= "fos_user:\r\n";
		$this->config .= "    db_driver:     orm                      # Le type de BDD à utiliser, nous utilisons l'ORM Doctrine depuis le début\r\n";
		$this->config .= "    firewall_name: main                     # Le nom du firewall derrière lequel on utilisera ces utilisateurs\r\n";
		$this->config .= "    user_class:    ".$this->getBundleRootName()."\UserBundle\Entity\User # La classe de l'entité User que nous utilisons\r\n";

		file_put_contents($filepath, "\n".$this->config.PHP_EOL , FILE_APPEND);
	}

	//8
	public function securityFile($filepath){ //security.yml
			$this->security="# To get started with security, check out the documentation:\r\n";
			$this->security.="# http://symfony.com/doc/current/book/security.html\r\n";
			$this->security.="security:\r\n";
			$this->security.="    encoders:\r\n";
			$this->security.="            FOS\UserBundle\Model\UserInterface: sha512\r\n";
			$this->security.="            ".$this->getBundleRootName()."\UserBundle\Entity\User: sha512\r\n";
			$this->security.="    # http://symfony.com/doc/current/book/security.html#where-do-users-come-from-user-providers\r\n";
			$this->security.="    providers:\r\n";
			$this->security.="        main:\r\n";
			$this->security.="            id: fos_user.user_provider.username\r\n";
			$this->security.="\r\n";
			$this->security.="    firewalls:\r\n";
			$this->security.="        # disables authentication for assets and the profiler, adapt it according to your needs\r\n";
			$this->security.="        dev:\r\n";
			$this->security.="            pattern: ^/(_(profiler|wdt)|css|images|js)/\r\n";
			$this->security.="            security: false\r\n";
			$this->security.="\r\n";
			$this->security.="        main:\r\n";
			$this->security.="            pattern:        ^/\r\n";
			$this->security.="            anonymous:      true\r\n";
			$this->security.="            provider:       main\r\n";
			$this->security.="            form_login:\r\n";
			$this->security.="                login_path: fos_user_security_login\r\n";
			$this->security.="                check_path: fos_user_security_check\r\n";
			$this->security.="                always_use_default_target_path : true\r\n";
            $this->security.="                default_target_path: /".strtolower($this->getFirstEntity())."/index\r\n";
            $this->security.="                use_referer : true\r\n";
			$this->security.="            logout:\r\n";
			$this->security.="                path:       fos_user_security_logout\r\n";
			$this->security.="                target:     /\r\n";
			$this->security.="            remember_me:\r\n";
			$this->security.="                key:        %secret%\r\n";

			file_put_contents($filepath, $this->security);
	}

 	//9
	public function routingFos($filepath){ //routing.yml
		$this->routes="fos_user_security:\r\n";
		$this->routes.="    resource: \"@FOSUserBundle/Resources/config/routing/security.xml\"\r\n";
		$this->routes.="\r\n";
		$this->routes.="fos_user_profile:\r\n";
		$this->routes.="    resource: \"@FOSUserBundle/Resources/config/routing/profile.xml\"\r\n";
		$this->routes.="    prefix: /profile\r\n";
		$this->routes.="\r\n";
		$this->routes.="fos_user_register:\r\n";
		$this->routes.="    resource: \"@FOSUserBundle/Resources/config/routing/registration.xml\"\r\n";
		$this->routes.="    prefix: /register\r\n";
		$this->routes.="\r\n";
		$this->routes.="fos_user_resetting:\r\n";
		$this->routes.="    resource: \"@FOSUserBundle/Resources/config/routing/resetting.xml\"\r\n";
		$this->routes.="    prefix: /resetting\r\n";
		$this->routes.="\r\n";
		$this->routes.="fos_user_change_password:\r\n";
		$this->routes.="    resource: \"@FOSUserBundle/Resources/config/routing/change_password.xml\"\r\n";
		$this->routes.="    prefix: /profile\r\n";

		file_put_contents($filepath, "\n".$this->routes.PHP_EOL , FILE_APPEND);
	}

	//10
	public function cmdDownloadFos(){

		$this->downlad_fos = "composer update friendsofsymfony/user-bundle";
		// TODO If Linux  or windows do "php composer.phar update friendsofsymfony/user-bundle"
	}

	
	//11
	public function rewriteUserEnt(){
		$this->user_ent = "<?php \r\n";
			$this->user_ent .= "// src/".$this->getBundleRootName()."/UserBundle/Entity/User.php \r\n";
			$this->user_ent .= "namespace ".$this->getBundleRootName()."\UserBundle\Entity; \r\n";
			$this->user_ent .= "use Doctrine\ORM\Mapping as ORM;\r\n";
			$this->user_ent .= "use FOS\\UserBundle\\Model\\User as BaseUser; \r\n";
			$this->user_ent .= "/** \r\n";
			$this->user_ent .= "* @ORM\Entity \r\n";
			$this->user_ent .= "*/ \r\n";
			$this->user_ent .= "class User extends BaseUser \r\n";
			$this->user_ent .= "{ \r\n";
			$this->user_ent .= "  /** \r\n";
			$this->user_ent .= "    * @ORM\Column(name=\"id\", type=\"integer\") \r\n";
			$this->user_ent .= "    * @ORM\Id \r\n";
			$this->user_ent .= "    * @ORM\GeneratedValue(strategy=\"AUTO\") \r\n";
			$this->user_ent .= "    */ \r\n";
			$this->user_ent .= "    protected \$id; \r\n";
			$this->user_ent .= "} \r\n";
	}
	

	//12 
	/* doctrine:schema:force */
	//========================


	//13  TODO read() "Create new admin user,  enter username mail and password"
	public function createUser($nom, $mail, $pass){
		$this->name = $nom; 
		$this->create_new_user = "php app/console fos:user:create ".$this->name." ".$mail." ".$pass;
	}
	public function promoteUser(){
		$this->promote_user =  "php app/console fos:user:promote ".$this->name." ROLE_ADMIN";
	}

	//14
	public function setRouteDebug(){
		$this->routeDebug = "php app/console debug:router";
	}

	//15
	public function setFosajax()
	{
		$this->ajax_func='function loginAjax(routeResultat, x){'."\r\n";
		$this->ajax_func.='  var data = $(\'#loginToAjax\').serialize();'."\r\n";
		$this->ajax_func.='  if(x%2==0){'."\r\n";
		$this->ajax_func.='	  	$.ajax({'."\r\n";
		$this->ajax_func.='		    url: routeResultat,'."\r\n";
		$this->ajax_func.='		    type: "POST",'."\r\n";
		$this->ajax_func.='		    data: data,'."\r\n";
		$this->ajax_func.='		    success: function(r){'."\r\n";
		$this->ajax_func.='		   $("#reponse").html(r);'."\r\n";
		$this->ajax_func.='		  }'."\r\n";
		$this->ajax_func.='	 	});'."\r\n";
		$this->ajax_func.='	 }'."\r\n";
		$this->ajax_func.='	 else{'."\r\n";
		$this->ajax_func.='	 	 $("#reponse").html(" ");'."\r\n";
		$this->ajax_func.='	 }'."\r\n";
		$this->ajax_func.='};'."\r\n";
		$this->ajax_func.='function ControlAjax(x){'."\r\n";
		$this->ajax_func.='	this.x = x;'."\r\n";
		$this->ajax_func.='}'."\r\n";
		$this->ajax_func.='ControlAjax.prototype.getN = function(){'."\r\n";
		$this->ajax_func.='	this.x++;'."\r\n";
		$this->ajax_func.='	return this.x;'."\r\n";
		$this->ajax_func.='}'."\r\n";
		$this->ajax_func.='var ctrl = new ControlAjax(-1);'."\r\n";

	}



	

	public function writeFosAjax()
	{	
		if(!file_exists("web/spmAjax")){ 
			mkdir("web/spmAjax"); 
			touch('web/spmAjax/login.js');
			chmod('web/spmAjax/login.js', 0777);
		}
		if(file_exists("web/spmAjax")){ 
			chmod("web/spmAjax", 0777);
			touch('web/spmAjax/login.js');
			chmod('web/spmAjax/login.js', 0777);
			if (is_writable('web/spmAjax/login.js')) 
			{
			    if (!$handle = fopen('web/spmAjax/login.js', 'w+')) {
			         echo "Impossible d'ouvrir le fichier ($this->path)";
			         exit;
			    }
			    // Ecrivons quelque chose dans notre fichier.
			    if (fwrite($handle, $this->getFosAjax()) === FALSE) {
			        echo "Impossible d'écrire dans le fichier (web/spmAjax)";
			        exit;
			    }
			//    echo "\najax writed\r\n";
			    fclose($handle);
			} 
			else 
			{
			    echo "Le fichier $this->path n'est pas accessible en écriture.";
			}
		}
		if( chmod("web/spmAjax", 0755) && !chmod('web/spmAjax/login.js', 0755)) echo "vérifier les droits du fichier 'web' ";
	}



	//
	public function setLoginForm(){
		$this->login_twig = '	{% trans_default_domain "FOSUserBundle" %}  '."\r\n";
		$this->login_twig .= '	{% block fos_user_content %}    '."\r\n";
		$this->login_twig .= '	<div id="loginToAjax">'."\r\n";
		$this->login_twig .= '		{% if error %}  '."\r\n";
		$this->login_twig .= '		<div class="alert alert-danger alert-dismissable">  '."\r\n";
		$this->login_twig .= '		<h3 style="color: #D2E4E8;">'."\r\n";
		$this->login_twig .= '			{{ error.messageKey|trans(error.messageData, "security") }}'."\r\n";
		$this->login_twig .= '		</h3>  '."\r\n";
		$this->login_twig .= '		</div>  '."\r\n";
		$this->login_twig .= '		{% endif %}  '."\r\n";
		$this->login_twig .= '		<div class="col-md-6">  '."\r\n";
		$this->login_twig .= '		<!-- general form elements -->  '."\r\n";
		$this->login_twig .= '		<div class="box box-primary">  '."\r\n";
		$this->login_twig .= '		<div class="box-header with-border">  '."\r\n";
		$this->login_twig .= '		<h3 class="box-title">  '."\r\n";
		$this->login_twig .= '		{% if is_granted("IS_AUTHENTICATED_REMEMBERED") %}  '."\r\n";
		$this->login_twig .= '		{{ "Connecté"|trans({"%username%": app.user.username}, "FOSUserBundle") }}'."\r\n";
		$this->login_twig .= '		|  '."\r\n";
		$this->login_twig .= '		<a href="{{ path("fos_user_security_logout") }}">  '."\r\n";
		$this->login_twig .= '			{{ "Déconnexion"|trans({}, "FOSUserBundle") }}  '."\r\n";
		$this->login_twig .= '		</a>  '."\r\n";
		$this->login_twig .= '		{% else %}  '."\r\n";
		$this->login_twig .= '		<a href="{{ path("fos_user_security_login") }}">'."\r\n";
		$this->login_twig .= '			{{ "Connexion"|trans({}, "FOSUserBundle") }}'."\r\n";
		$this->login_twig .= '		</a>  '."\r\n";
		$this->login_twig .= '		{% endif %}  '."\r\n";
		$this->login_twig .= '		</h3>  '."\r\n";
		$this->login_twig .= '	</div>  '."\r\n";
		$this->login_twig .= '	<form action="{{ path("fos_user_security_check") }}" method="post">  '."\r\n";
		$this->login_twig .= '		<div class="box-body">  '."\r\n";
		$this->login_twig .= '			<div class="form-group">  '."\r\n";
		$this->login_twig .= '				<input type="hidden" name="_csrf_token" value="{{ csrf_token }}" />  '."\r\n";
		$this->login_twig .= '				<label for="username">{{ "Username"|trans }}</label><br/>'."\r\n\r\n";
		$this->login_twig .= '				<input type="text" class="form-control" id="username" name="_username" value="{{ last_username }}" required="required" />  '."\r\n";
		$this->login_twig .= '			</div> '."\r\n";
		$this->login_twig .= '		<div class="form-group">  '."\r\n";
		$this->login_twig .= '			<label for="password">{{ "Password"|trans }}</label><br/>';
		$this->login_twig .= '		 	<input type="password" class="form-control" id="password" name="_password" required="required" />  '."\r\n";
		$this->login_twig .= '		</div> '."\r\n";
		$this->login_twig .= '		<div class="form-group"> '."\r\n";
		$this->login_twig .= '			<input type="checkbox" id="remember_me" name="_remember_me" value="on" />'."\r\n";
		$this->login_twig .= '			<label for="remember_me">{{ "remember_me"|trans }}</label>'."\r\n";
		$this->login_twig .= '		</div> '."\r\n";
		$this->login_twig .= '		<div class="form-group"> '."\r\n";
		$this->login_twig .= '			<input type="submit" class="btn btn-primary" id="_submit" name="_submit" value="{{ "Connect me"|trans }}" /> '."\r\n";
		$this->login_twig .= '		</div>  '."\r\n";
		$this->login_twig .= '		</div>   '."\r\n";
		$this->login_twig .= '		</form>   '."\r\n";
		$this->login_twig .= '		</div>   '."\r\n";
		$this->login_twig .= '	</div>   '."\r\n";
		$this->login_twig .= '</div>   '."\r\n";
		$this->login_twig .= '{% endblock fos_user_content %}'."\r\n";
	}


	public function getBundleRootName(){
		return $this->bundle_root_name;
	}

	public function getBundlenamespace()
	{	$name=""; 
		$b = explode( '/', $this->bundlename );
		if(count($b) == 2) $name = $b[1];	
		if(count($b) == 3) $name = $b[1].'\\'.$b[2];
		return $name;
	}

	public function getBuildUserBndl(){ return $this->cmd_user_bundle;}
	public function getBuildUserEnt(){ return $this->cmd_user_entity; }
	public function getDocUpdate(){ return $this->doctrine_update; }
	public function getDocForce(){ return $this->doctrineforce; }
	public function getEditUserbundleFile(){ return $this->userbundle_file; } //towrite
	public function getAddInComposer(){ return $this->composer;} //towrite
	public function getAddInKernel(){ return $this->kernel; } //towrite
	public function getConfig(){ return $this->config;} //towrite
	public function getSecurityFile(){ return $this->security;} //towrite
	public function getRoutingFos(){ return $this->routes;} //towrite
	public function getCmdDownloadFos(){ return $this->downlad_fos;}
	public function getRewriteUserEnt(){ return $this->user_ent;} //towrite
	public function getCreateUser(){ return $this->create_new_user;}
	public function getRouteDebug(){ return	$this->routeDebug; }
	public function getFosajax(){ return $this->ajax_func;}
	public function getLoginForm(){ return $this->login_twig;} //towrite
	public function getFirstEntity(){ return $this->first_entity; }
	public function getPromoteUser(){ return $this->promote_user; }

	public function writeFile($file, $path)
	{
		$monchemin  = $path;
		$create = $file;
		touch($path);
		if (is_writable($path)) 
		{
		    if (!$handle = fopen($path, 'w+')) {
		         echo "Impossible d'ouvrir le fichier ($path)";
		         exit;
		    }
		    // Ecrivons quelque chose dans notre fichier.
		    if (fwrite($handle, $create) === FALSE) {
		        echo "Impossible d'écrire dans le fichier ($path)";
		        exit;
		    }
		  //  echo "###File writed\n";
		    fclose($handle);
		} 
		else 
		{
		    echo "Le fichier ".$monchemin." n'est pas accessible en écriture.";
		}
	}

	


}