<?php
//SFR.php
class sfrel
{	
	private $entity;
	private $entity2;
	private $relation;
	private $fieldname;

	public function setRelation($entity, $relation)
	{
		//relation from present entity  
		$this->entity = $entity;
		$this->relation = $relation;		
	}
	
	public function setField($entity2)
	{  // to other entity, that the private field to add in doctrine present entity!
		$this->entity2 = $entity2;		
	}

	public function sfrel()
	{			// ex :  Entity:ManyToOne:Entity2
		return  $this->entity.':'.$this->relation.':'.$this->entity2;		
	}




}