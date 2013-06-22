<?php
/*
RObin.php

This file creates the RObin class, which is used to
apply modifications to the Ragnarok Online client.

*/
class RObin
{
	//-- Attributes --//
    public /*protected*/ $exe = ""; //full file + extra bytes;
    public /*protected*/ $size = 0; //complete size 
    public $dif = array();
    public $xDiff = array();  //Contains all patches and patch groups
    public $xPatch = null;    //Current xPatch
    public $xmlWriter = null; //xDiff Writer Interface
    
    private $PEHeader = null; //Offset of PE Header
    private $image_base = 0;  //Value of ImageBase (rva are relative to this one)
    private $sections;        //list of sections
    private $client_date = 0; //Client date converted from UNIX Timestamp format to readable form
	private $linker = 0;      //Linker version
    private $crc = 0;	      //crc of original file
    public $themida = false;  //Boolean representing whether Client is mangled from themida unpacking
	private $nullStart = array(); //Set of Offsets from where string of nulls start (there are 2 keys code and xdiff representing sect_0/.text and .xdiff)
	
    ///////////////////////////////////////////////////////////////////////////
	// Method 1 = Loads file from $path & retrieves information from headers
	//////////////////////////////////////////////////////////////////////////
    public function load($path,$debug=false)
    {
		//Read full file into class for easy access
        $file = file_get_contents($path);
        if ($file === false) return false;		
		
		//assign contents to exe attribute and calculate crc & length
		$this->exe = $file;			
        $this->crc = crc32($file);        
        $this->size = strlen($file);
		
		///////////////////////////////////////////////////////////////////////////
		// For Information about the PE file Headers refer to                    //
		// http://www.microsoft.com/whdc/system/platform/firmware/PECOFFdwn.mspx //
		///////////////////////////////////////////////////////////////////////////
		
		//Find PE Header Offset - starting point of headers.        
        $this->PEHeader = $this->match("\x50\x45\x00\x00");
        if($debug) echo "PE Header @\t".dechex($this->PEHeader)."h\n";
		
        //If you dont find it - no use going further, its not a valid file
        if($this->PEHeader === false)  die("Invalid PE file used!\n");
            
		// Read ImageBase, Client Date, Linker version
        $this->image_base = $this->read($this->PEHeader + 0x34, 4, 'V');
        if($debug) echo "Image Base\t".dechex($this->image_base)."h\n";
        
        $date = $this->read($this->PEHeader+8, 4, 'V');
        $this->client_date = date('Y', $date) . date('m', $date) . date('d', $date);
        if($debug) echo "Client Date\t".$this->client_date."\n";
		
		$this->linker = $this->read($this->PEHeader + 0x1a, 2, 'S');
		if($debug) echo "Linker Version\t".$this->linker."\n";
		
		//Read Section Information
        if($debug) echo "\nName\tvSize\tvOffset\trSize\trOffset\tvrDiff\n";
        if($debug) echo "----\t-----\t-------\t-----\t-------\t------\n";
        
        $sectionCount = $this->read($this->PEHeader + 0x6, 2, "S");
        for($i = 0, $curSection = $this->PEHeader + 0x18 + 0x60 + 0x10 * 0x8; $i < $sectionCount; $i++)
		{
			//First comes name - 8 bytes		
            $sectionInfo['name'] = $this->read($curSection, 8);
			
			//Remove trash bytes after first null and the null itself
            $a = explode("\0", $sectionInfo['name']);
			$sectionInfo['name'] = trim($a[0]);
			
			//For themida unpacked clients the first section name is blank so we will use sect_<number> to represent it.
            if(!$sectionInfo['name'])
			{
                $sectionInfo['name'] = "sect_".$i;				
                $this->themida = true;
            }
			
			// Remaining items in the Section Header (only sizes and addresses we need)
            $sectionInfo['vSize']         = $this->read($curSection+8+0*4, 4, "V");
            $sectionInfo['vOffset']       = $this->read($curSection+8+1*4, 4, "V");
            $sectionInfo['rSize']         = $this->read($curSection+8+2*4, 4, "V");
            $sectionInfo['rOffset']       = $this->read($curSection+8+3*4, 4, "V");
			
            $sectionInfo['vEnd']          = $sectionInfo['vOffset'] + $sectionInfo['vSize'];
            $sectionInfo['rEnd']          = $sectionInfo['rOffset'] + $sectionInfo['rSize'];
            $sectionInfo['vrDiff']        = $sectionInfo['vOffset'] - $sectionInfo['rOffset'];
            
            if($debug)
				echo  $sectionInfo['name'] . "\t"
					. dechex($sectionInfo['vSize']) . "\t"
					. dechex($sectionInfo['vOffset']) . "\t"
					. dechex($sectionInfo['rSize']) . "\t"
					. dechex($sectionInfo['rOffset']) . "\t"
					. dechex($sectionInfo['vrDiff']) . "\n";
			
            // Convert to object for easier access
            // E.g: $exe->getSection(".rdata")->rOffset...
            $this->sections[$sectionInfo['name']] = new stdClass();
            if(is_array($sectionInfo) && count($sectionInfo) > 0) 
                foreach($sectionInfo as $name => $value) 
                    if (!empty($name))
                        $this->sections[$sectionInfo['name']]->$name = $value;
			
            $curSection += 0x28;// Each section header is 40 bytes long 40 = 0x28
        }
		
		// Now we need to add an extra section called .xdiff if its not already there.
		// Currently SectionInfo holds the info about last section so we can utilize that.		
		if (!$this->getSection(".xdiff"))
		{			
			$sectionAlign = $this->read($this->PEHeader + 0x38, 4, "V");
			$fileAlign = $this->read($this->PEHeader + 0x3C, 4, "V");
			$sectionInfo['name'] 		= ".xdiff";
			
			$sectionInfo['vSize'] 		= $sectionAlign;
			
			// vEnd of previous section should have been vOffset here but the virtual size in unpacked exe isnt exactly multiple of SectionAlignment
			$sectionInfo['vOffset']		= ceil($sectionInfo['vEnd'] / $sectionAlign ) * $sectionAlign; 
									
			$sectionInfo['rSize']		= 4 * $fileAlign; // 4 * 0x200 = 0x800 => can be anything less than $sectionAlign - but ideally multiple of $fileAlign
			
			//same issue as vEnd but here it should have been multiple of file alignment
			$sectionInfo['rOffset']		= ceil($sectionInfo['rEnd'] / $fileAlign ) * $fileAlign;
			
			$sectionInfo['vEnd']		= $sectionInfo['vOffset'] + $sectionInfo['vSize'];
			$sectionInfo['rEnd']		= $sectionInfo['rOffset'] + $sectionInfo['rSize'];			
			$sectionInfo['vrDiff']		= $sectionInfo['vOffset'] - $sectionInfo['rOffset'];

			// Convert to object for easier access
            // E.g: $exe->getSection(".rdata")->rOffset...
			$this->sections[$sectionInfo['name']] = new stdClass();
			foreach($sectionInfo as $name => $value)
				if (!empty($name))
					$this->sections[$sectionInfo['name']]->$name = $value;
					
			if($debug) 
				echo  $sectionInfo['name'] . "\t"
					. dechex($sectionInfo['vSize']) . "\t"
					. dechex($sectionInfo['vOffset']) . "\t"
					. dechex($sectionInfo['rSize']) . "\t"
					. dechex($sectionInfo['rOffset']) . "\t"
					. dechex($sectionInfo['vrDiff']) . "\n";

			//Along with adding section information we need to insert null bytes to the end of the exe
			//so that the match & zeroed functions can detect a proper offset.
			$this->exe = str_pad($this->exe, $this->getSection(".xdiff")->rEnd, "\x00");
			$this->size = $this->getSection(".xdiff")->rEnd;
			
			//Update Section Count
			$sectionCount++;
		}
		else
		{
			$curSection -= 0x28; // if already present go back 40 bytes so that curSection represents offset of .xdiff header.
		}
		echo "\r\n";
		
		//Initializing nullStart Array for auto detecting Null areas
		if ($this->themida)
			$sectStart = $this->getSection("sect_0")->rOffset;
		else
			$sectStart = $this->getSection(".text")->rOffset;
			
		$this->nullStart['code'] = $this->read($this->PEHeader + 0x1c, 4, "V") + $sectStart;//needed for zeroed function
		$this->nullStart['xdiff'] = $this->getSection(".xdiff")->rOffset;
			
			
        // Prepare XMLWriter for xDiff
        $this->xmlWriter = new XMLWriter();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->setIndentString("\t");
		$this->xmlWriter->startDocument('1.0', 'ISO-8859-1');
        $this->xmlWriter->startElement('diff');
        
        $this->xmlWriter->startElement('exe');
        $this->xmlWriter->writeElement('builddate', $date);
        $this->xmlWriter->writeElement('filename', basename($path));
        $this->xmlWriter->writeElement('crc', $this->crc);
        $this->xmlWriter->writeElement('type', 'RE');
        $this->xmlWriter->endElement(); // exe
        
        $this->xmlWriter->startElement('info');
        $this->xmlWriter->writeElement('name', '[ '.substr($this->client_date,0,4) . '-' . substr($this->client_date,4,2) . '-' . substr($this->client_date,6,2) . ' kRO ]');
        $this->xmlWriter->writeElement('author', 'DiffTeam');
        $this->xmlWriter->writeElement('version', '1.0');
        $this->xmlWriter->writeElement('releasedate', 'now');
        $this->xmlWriter->endElement(); // info
        
		$this->xmlWriter->startElement('override');
		$this->xmlWriter->writeElement('peheader', $this->PEHeader);
		$this->xmlWriter->writeElement('imagesize', $this->getSection(".xdiff")->vEnd);
		$this->xmlWriter->writeElement('sectioncount', $sectionCount);
		$this->xmlWriter->writeElement('xdiffstart', $curSection);
		$this->xmlWriter->writeElement('vsize', $this->getSection(".xdiff")->vSize);
		$this->xmlWriter->writeElement('voffset', $this->getSection(".xdiff")->vOffset);
		$this->xmlWriter->writeElement('rsize', $this->getSection(".xdiff")->rSize);
		$this->xmlWriter->writeElement('roffset', $this->getSection(".xdiff")->rOffset);
		$this->xmlWriter->endElement(); // override
        return true;
    }

	////////////////////////////////////////////////////////////////////////
    // Method 2 = Reads $size bytes starting at $offset and returns it raw.
	// Or you can also make it return the bytes unpacked into an array
	// with $format as format string. 
	// If the unpacked array only contains 1 data, the array is smashed and 
	// the single value is returned instead.
	//
	// For format specification see http://www.php.net/pack
	////////////////////////////////////////////////////////////////////////
	
    public function read($offset, $size, $format = null)
    {
		//sanity check -> negative size & not enough bytes left
        if ($size < 1 || ($offset >= $this->size - $size)) return false;
        
		//read the raw bytes
        $data = substr($this->exe, $offset, $size);
        
        if (is_string($format)) 
		{
            // if $format is specified unpack data
            $data = unpack($format, $data);
            if ($data === false)
			{
                echo "Bad Format" . $format;
                return false;
            }
			
			// Smash the array if there is only 1 entry inside.			
            if ((count($data) == 1) && (isset($data[1])))
                $data = $data[1];            
        }
        return $data;
    }
	
	//////////////////////////////////////////////////////////////////////
	// Method 3 = Search for $pattern (using $wildcard as wildcard) within
	// real address range ($start - $finish) and returns the offset.
    // If $start or $finish is omitted beginning or end of file is used 
	// respectively. Returns false if not found.
	//////////////////////////////////////////////////////////////////////
	
    public function match($pattern, $wildcard = "", $start = null, $finish = null)
    {
		//Setup Length and Boundaries of search
        $length = strlen($pattern);
        $start = (is_null($start) || ($start <= 0) ? 0 : $start);
        $finish = (is_null($finish) || ($finish > $this->size) ? $this->size : $finish);
		
		//Sanity Check -> pattern is not blank and $start - $finish is a proper boundary
        if (($length < 1) || ($start >= $this->size-$length) || ($finish <= $start)) return false;
		
        $offset = $start;
		
        // Is there a wildcard?
        if (strlen($wildcard) == 1) {
			//trim wildcard
			$pattern = trim($pattern, $wildcard);
			
            // Check if wildcard appears in the pattern if not nullify wildcard
            $wpos = strpos($pattern, $wildcard);
            if ($wpos === false) $wildcard = "";
        }
		
        // Did wildcard survive?
        if (strlen($wildcard) == 1) {
            // If yes we need to split up the pattern and search by parts
			$exploded = explode($wildcard, $pattern);
            
			// To facilitate searching we put the split pattern into an array with relative offset as keys
			$pieces = array();
            $offset = 0;
			foreach ($exploded as $key => $value)
            {
				if (empty($value) === false)
				{                    
                    if ($key == 0)
						$partial = $value;
					else
						$pieces[$offset] = array($value, strlen($value));
                    $offset += strlen($value);
                }
                $offset++;
            }
			
            // Search for the first part and try to match the rest
            for ($i = strpos($this->exe, $partial, $start); ($i !== false) && ($i < $finish); $i = strpos($this->exe, $partial, $i + 1))
            {
                foreach ($pieces as $offset => $value)
                    if (substr_compare($this->exe, $value[0], $i + $offset, $value[1]) != 0)
                        continue 2;
                return $i;
            }
        } 
		else 
		{
            // If the wildcard didnt survive or was already blank we can directly use strpos() for finding pattern.
            $i = strpos($this->exe, $pattern, $start);
            if ($i < $finish) return $i;
        }
        return false;
    }
    
	//////////////////////////////////////////////////////////////////
	// Method 4 - Extended version of match(). matches multiple times 
	// for same pattern and returns array of offsets.
	//////////////////////////////////////////////////////////////////
    public function matches($pattern, $wildcard = "", $start = null, $finish = null)
    {
        $offsets = array();
        $offset = $start;
        while ($offset = $this->match($pattern, $wildcard, $offset + strlen($pattern), $finish)) {
            $offsets[] = $offset;
        }
        if(sizeof($offsets) > 0)
            return $offsets;
        return false;
    }
    
	////////////////////////////////////////////////////////////////
	// Method 5 - Searches for unused $size null bytes and returns
	// the offset. Only two sections are searched for the bytes i.e.
	//
	// code section  = sect_0/.text & xdiff section = .xdiff in that	
	// order
	////////////////////////////////////////////////////////////////	
	public function zeroed($size)
    {
		$zeroed = str_repeat("\x00", $size + 2); // 1 free byte either side
		//first check in code section
		if($this->themida)
            $section = $this->getSection("sect_0");
        else
            $section = $this->getSection(".text");
		
		$offset = $this->match($zeroed, "", $this->nullStart['code'], $section->rEnd);

		if ($offset === false) //otherwise check in xdiff section
		{
			$offset = $this->match($zeroed, "", $this->nullStart['xdiff']);//xdiff extends till finish so we dont need to specify finish
		}
		
		if ($offset === false)
			return false;
		else
			return $offset + 1;
	}
    
	////////////////////////////////////////////////////////////////////////
	// Method 6 - Accompanying function for zeroed. Used to overwrite bytes
	// in the null byte area starting at offset with $code.
	// It also updates the nullStart array using $allocSize. 
	// Functions similar to null but without the xDiffInput check & 
	// $code is not an array.
	////////////////////////////////////////////////////////////////////////
    public function insert($code, $allocSize, $offset)
    {		
        $length = strlen($code);
        if ($length < 1) return false;// Sanity check
        
        for ($i = 0; $i < $length; $i++) 
		{
            if ($this->exe[$offset + $i] != $code[$i]) 
			{
                $poffset = strtoupper(dechex($offset + $i));
                $pvalue1 = ord($this->exe[$offset + $i]);
                $pvalue2 = ord($code[$i]);
                $change = new xPatchChange();
                $change->setType(XTYPE_BYTE);
                $change->setOffset($offset + $i);
                $change->setOld($pvalue1);
                $change->setNew($pvalue2);
                $this->xPatch->addChange($change);
            }
            $this->exe[$offset + $i] = $code[$i];
        }
		
        // Modify nullStart to point to next null area start.
        if( $offset >= $this->nullStart['xdiff'] )
			$this->nullStart['xdiff'] = $offset + $allocSize;
		elseif($offset >= $this->nullStart['code'] )
			$this->nullStart['code'] = $offset + $allocSize;
			
		//echo $this->xPatch->name;
        return true;
    }
    
	/////////////////////////////////////////////////////////////////////
	// Method 7 - Replace bytes starting at $offset using $replace array.
	// The array maps relative offsets (relative to $offset) to replace 
	// byte sequences.
	// Example:
    // replace(400, array(4 => "\x00", 2 => "\xAB"))
    // Replaces the byte at 404 (400 + 4) with a null (x00) byte;
    // Replaces the byte at 402 (400 + 2) with a xAB byte.
	////////////////////////////////////////////////////////////////////
    public function replace($offset, $replace)
    {
        foreach ($replace as $pos => $value) 
		{
			if (substr($value,0,1) == '$')// input variable (xDiff)
			{
				//echo 'input: '.$value. " : "; //Enable if required later
				$change = new xPatchChange();
	            $change->setType(XTYPE_BYTE);
	            $change->setOffset($offset + $pos);
	            $change->setOld(ord($this->exe[$offset + $pos]));
	            $change->setNew($value);
	            $this->xPatch->addChange($change);
			} 
			else //Hardcoded bytes
	            for ($i = 0; $i < strlen($value); $i++)
	                if ($this->exe[$offset + $pos + $i] != $value[$i]) 
					{
	                    $poffset = strtoupper(dechex($offset + $pos + $i));
	                    $pvalue1 = ord($this->exe[$offset + $pos + $i]);
	                    $pvalue2 = ord($value[$i]);
						$change = new xPatchChange();
						$change->setType(XTYPE_BYTE);
						$change->setOffset($offset + $pos + $i);
						$change->setOld($pvalue1);
						$change->setNew($pvalue2);
						$this->xPatch->addChange($change);
					}            
        }
        return true;
    }
    
	//////////////////////////////////////////////////////////////
	// Method 8 - functions like replace() but for WORDs (2 Bytes)
	//////////////////////////////////////////////////////////////
    public function replaceWord($offset, $replace)
    {
    	foreach ($replace as $pos => $value)
    	{
    		$old = ord($this->exe{$pos+$offset++})+($this->exe($buf{$pos+$offset})<<8);    		
    		$change = new xPatchChange();
    		$change->setType(XTYPE_WORD);
    		$change->setOffset($offset-1 + $pos);
    		$change->setOld($old);
    		$change->setNew($value);
    		$this->xPatch->addChange($change);
    	}
    }
    
	
	///////////////////////////////////////////////////////////////
	// Method 9 - functions like replace() but for DWORDs (4 Bytes)
	///////////////////////////////////////////////////////////////    
    public function replaceDword($offset, $replace)
    {
    	foreach ($replace as $pos => $value)
    	{
    		$old = ord($this->exe{$pos+$offset++})+(ord($this->exe{$pos+$offset++})<<8)+(ord($this->exe{$pos+$offset++})<<16)+(ord($this->exe{$pos+$offset})<<24);    		
    		$change = new xPatchChange();
    		$change->setType(XTYPE_DWORD);
    		$change->setOffset($offset-3 + $pos);
    		$change->setOld($old);
    		$change->setNew($value);
    		$this->xPatch->addChange($change);
    	}
    }
	
	
	//////////////////////////////////////////////////////////////
	// Method 10 - functions like replace() but for Strings
	//////////////////////////////////////////////////////////////    
    public function replaceString($offset, $replace)
    {
    	foreach ($replace as $pos => $value)
    	{
    		//$old = ord($this->exe{$offset++})+(ord($this->exe{$offset++})<<8)+(ord($this->exe{$offset++})<<16)+(ord($this->exe{$offset})<<24);
    		$old = '';
    		
    		$change = new xPatchChange();
    		$change->setType(XTYPE_STRING);
    		$change->setOffset($offset + $pos);
    		$change->setOld($old);
    		$change->setNew($value);
    		$this->xPatch->addChange($change);
    	}    	
    }  
    
	
	//////////////////////////////////////////////////////////////
	// Method 11 - Add an xPatchInput from the suppled values
	///////////////////////////////////////////////////////////////
    public function addInput($name, $type, $op='', $min=null, $max=null)
    {
    	$input = new xPatchInput($name, $type, $op, $min, $max);
    	$this->xPatch->addInput($input);
    }
  
	//////////////////////////////////////////////////////////////////////////////
	// Method 12 - Returns an array with the changes made since last diff() call.
	//////////////////////////////////////////////////////////////////////////////
    public function diff()
    {
        $diff = $this->dif;
        $this->dif = array();
        return $diff;
    }
    
	//////////////////////////////////////////////////////////////////////////
	// Method 13 - Wrapper around matches() function for searching $code
	// pattern in code section = .text/sect_0 (using $wildcard). An additional
	// parameter $count defines exact number of times the pattern should be 
	// there in the section for success. Like matches() it returns array of 
	// offsets . Returns false if it fails. 
	// To match all occurences of the pattern use $count = -1
	//////////////////////////////////////////////////////////////////////////	
    public function code($code, $wildcard, $count = 1)
    {
        if($this->themida)
            $section = $this->getSection("sect_0");
        else
            $section = $this->getSection(".text");

        $offsets = $this->matches($code, $wildcard, $section->rOffset, $section->rOffset + $section->rSize);        
        if (($count != -1) && (count($offsets) != $count))
		{
            echo "#code() found ".count($offsets)." matches# ";
            return false;
        }
        if ($offsets == false)
		{
            echo "#code() found no matches# ";
            return false;
        }
        return ($count == 1 ? $offsets[0] : $offsets);
    }
	
	/////////////////////////////////////////////////////////////////////////
	// Method 13 - Wrapper around match() function for searching for a string
	// $str in data section = .rdata/sect_0. Used for finding offset of ascii
	// strings pushed or assigned to registers.
	// An additional parameter $type specifies if the return value should be
	// an rva or raw address. Returns false on failure
	/////////////////////////////////////////////////////////////////////////    
    public function str($str,$type)
    {        
        $iBase = $this->imagebase();
		
        if($this->themida)
            $section = $this->getSection("sect_0");
        else
            $section = $this->getSection(".rdata");
        
        $offset = $this->match("\x00".$str."\x00", "", $section->rOffset, $section->rOffset + $section->rSize);
        if ($offset === false) return false;
		
        if($type == "rva")
            return $offset + 1 + $section->vrDiff + $iBase;
        if($type == "raw")
            return $offset + 1;
			
        return false;
    }
    
	/////////////////////////////////////////////////////////////
	// Method 14 - Find offset of imported function $func.
	// $func can be either 
	// a string ($str = true) or a hex value ($str = false)	
	/////////////////////////////////////////////////////////////
    public function func($func, $str = true)
    {    
        $iBase = $this->imagebase();        
        if($this->themida)
            $section = $this->getSection("k3dT");
        else
            $section = $this->getSection(".rdata");
        
        if ($str) 
		{
			// if the input is a name we need to find its corresponding Jmp Pointer location from import section
            $offset = $this->match($func . "\x00", "", $section->rOffset, $section->rOffset + $section->rSize);			
            $code = pack("I", $offset - 2 + $section->vrDiff);
			
			//echo dechex($offset) . " - ";
			//echo bin2hex($code) . " - ";
        }
		else 
            $code = $func;
		
		if($this->themida)
            $section = $this->getSection("sect_0");
        else
            $section = $this->getSection(".rdata");
		
        $offset = $this->match($code, "", $section->rOffset, $section->rOffset + $section->rSize);
        if ($offset === false) return false;
		
		return $offset + $section->vrDiff + $iBase;
    }
    
	/////////////////////////////////////////////////////////////
	// Method 15 - Generate xDiff File from list of patches made
	/////////////////////////////////////////////////////////////
	public function writeDiffFile($filePath)
    {
    	//print_r($this->xDiff);
    	$this->xmlWriter->startElement('patches');    
    	foreach ($this->xDiff as $p)
    		if (is_a($p, 'xPatchBase')) //Both xPatch and xPatchGroup implement "writeToXml" :)
    			$p->writeToXml($this->xmlWriter);
    
    	$this->xmlWriter->endElement(); //patches
    	$this->xmlWriter->endElement(); //diff
    	$this->xmlWriter->endDocument();
    	
    	file_put_contents($filePath, $this->xmlWriter->outputMemory(true));
    	$this->xmlWriter->flush();
    	unset($this->xmlWriter);
    }
	
	///////////////////////////////////////////////////////////
	// Attribute Public Read-only Access Methods
	//////////////////////////////////////////////////////////
        
    public function PEHeader() {return $this->PEHeader;}
    public function imagebase() {return $this->image_base;}    
    public function clientdate(){return $this->client_date;}
    
	///////////////////////////////////////////////////////////
	// Utility Method 1: Returns the section specified by name
	///////////////////////////////////////////////////////////
    public function getSection($name)
    {
        if (isset($this->sections[$name]))
			return $this->sections[$name];
		else
			return false;
    }
    
	///////////////////////////////////////////////////
	// Utility Method 2: Convert Real Address to RVA
	///////////////////////////////////////////////////    
    public function Raw2Rva($offset)
    {
		if(($section = $this->RawOffset2Section($offset)) !== false)
			return $offset + $section->vrDiff + $this->image_base;

		return false;
	}
    
	///////////////////////////////////////////////////
	// Utility Method 3: Convert RVA to Real Address
	///////////////////////////////////////////////////
    public function Rva2Raw($offset)
    {    
		if(($section = $this->RvaOffset2Section($offset)) !== false)
			return $offset - $this->image_base - $section->vrDiff;

		return false;
    }
	
	///////////////////////////////////////////////////
	// Utility Method 4: Get Section pointer to point 
	// which contains the offset
	///////////////////////////////////////////////////
    public function RawOffset2Section($offset)
    {
		foreach($this->sections as $section )
			if($offset >= $section->rOffset && $offset < ($section->rOffset + $section->rSize))
				return $section;

		return false;
    }
    
	///////////////////////////////////////////////////
	// Utility Method 4: Get Section pointer to point 
	// which contains the RVA offset
	///////////////////////////////////////////////////
    public function RvaOffset2Section($offset)
    {
		$offset -= $this->image_base;
		foreach($this->sections as $section )
			if($offset >= $section->vOffset && $offset < ($section->vOffset + $section->vSize + ($section->rSize - $section->vSize)))
				return $section;
				
		return false;
    }
}
?>