<?php

// Base class for patches and patchgroups
class xPatchBase
{
	protected $id = 0;
	protected $name = '';
	
	// getters
	public function getID() { return $this->id; }	
	public function getName() { return $this->name; }	
	
	// setters
	public function setID($val) { $this->id = $val; }
	public function setName($val) { $this->name = $val; }
}

class xPatch extends xPatchBase 
{
	private $type = '';
	private $desc = '';
	private $recommended = false;
	private $group = 0;
	
	private $changes=array();
	
	function __construct($id, $name, $type, $group=0, $desc='', $recommended=false)
	{
		$this->id = $id;
		$this->name = $name;
		$this->type = $type;
		$this->group = $group;
		$this->desc = $desc;
		$this->recommended = $recommended;
	}
	
	public function addChange($change)
	{
		$this->changes[] = $change;
	}
	
	public function writeToXml(&$xmlWriter)
	{
		
		
		$xmlWriter->startElement('patch');
		$xmlWriter->writeAttribute('id', $this->getID());
		$xmlWriter->writeAttribute('name', $this->getName());
		$xmlWriter->writeAttribute('type', $this->getType());
		if ($this->isRecommended()) $xmlWriter->writeAttribute('recommended', '1');
		$xmlWriter->writeElement('desc', $this->getDesc());
		
		$xmlWriter->startElement('changes');
		foreach ($this->getChanges() as $c)
		{
			$type = $c->getType();
			if ($type == XTYPE_BYTE)
				$xmlWriter->startElement('byte');
			else if ($type == XTYPE_WORD)
				$xmlWriter->startElement('word');
			else if ($type == XTYPE_DWORD)
				$xmlWriter->startElement('dword');
			else if ($type == XTYPE_STRING)
				$xmlWriter->startElement('string');
			else
				die("\nUnknown change type $type !\n\n");
				
			$xmlWriter->writeAttribute('offset', dechex($c->getOffset()));
			if ($type == XTYPE_STRING) {
				$xmlWriter->writeAttribute('old', $c->getOld());
				$xmlWriter->writeAttribute('new', $c->getNew());
			} else {
				$xmlWriter->writeAttribute('old', dechex($c->getOld()));
				$xmlWriter->writeAttribute('new', dechex($c->getNew()));
			} 				
			
			$xmlWriter->endElement(); //[type]
		}
		
		$xmlWriter->endElement(); //changes
		$xmlWriter->endElement(); //patch    				
	}
	
	// getters
	public function getType() { return $this->type; }
	public function getDesc() { return $this->desc; }
	public function isRecommended() { return $this->recommended; }
	public function getGroup() { return $this->group; }
	public function getChanges() { return $this->changes; }
	
	// setters
	public function setType($val) { $this->type = $val; }
	public function setDesc($val) { $this->desc = $val; }
	public function setRecommended($val) { $this->recommended = $val; }
	public function setGroup($val) { $this->group = $val; }
	public function setChanges($val) { $this->changes = $val; }
}

// Holds different patches
// NOTE: The constructor is ONLY used by the patchgroup's user function.
class xPatchGroup extends xPatchBase
{
	private $patchNames = array(); //array of string!
	private $patches = array();    //array of xPatch!
	
	function __construct($id, $name, $patchNames)
	{
		$this->id = $id;
		$this->name = $name;
		$this->patchNames = $patchNames;
	}
	
	public function addPatch($p)
	{
		$this->patches[] = $p;
	}
	
	public function writeToXml(&$xmlWriter)
	{
		$xmlWriter->startElement('patchgroup');
		$xmlWriter->writeAttribute('id', $this->id);
		$xmlWriter->writeAttribute('name', $this->name);
		
		foreach($this->patches as $p)
			$p->writeToXml($xmlWriter);
			
		$xmlWriter->endElement(); //patchgroup
	}
	
	// getters
	public function getPatches() { return $this->patches; }
	public function getPatchNames() { return $this->patchNames; }
	
	// setters
	public function setPatches($val) { $this->patches = $val; }
	public function setPatchNames($val) { $this->patchNames = $val; }
}

define('XTYPE_NONE',		0);
define('XTYPE_BYTE',		1);
define('XTYPE_WORD',		2);
define('XTYPE_DWORD',		3);
define('XTYPE_STRING',	4);

class xPatchChange 
{
	private $type = XTYPE_NONE;
	private $offset = 0;
	private $old = null;
	private $new = null;
	
	// getters
	public function getType() { return $this->type; }
	public function getOffset() { return $this->offset; }
	public function getOld() { return $this->old; }
	public function getNew() { return $this->new; }
	
	// setters
	public function setType($val) { $this->type = $val; }
	public function setOffset($val) { $this->offset = $val; }
	public function setOld($val) { $this->old = $val; }
	public function setNew($val) { $this->new = $val; }
}

?>