<?
/*
 * PHP HTML Engine
 *
 * Copyright (C) 2015 Charles Johannisen
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 *
 */
class HTML{
	
	const HTML_4_STR = 1;
    const HTML_4_TRA = 2;
    const HTML_4_FRA = 3;
    const XHTML_1_STR = 4;
    const XHTML_1_TRA = 5;
    const XHTML_1_FRA = 6;
    const XHTML_1_1 = 7;
    const XHTML_1_BASIC = 8;
    const HTML_5 = 9;
    const MATHML_1 = 10;
    const MATHML_2 = 11;
    const XML = 12;
    
    public static $templating = array();
    public static $ImageConfig = array();
    public static $folders = array();
    public static $files = array();
    public static $links = array();
    public static $dateid = 1;
    public static $zindex = 1;
    public static $cms_img_w = 50;
	public static $cms_img_h = 50;
	public static $IMGDIR = "";
	public static $HOST = "";
	
	public static $TINY = true;
	
	public static $menu_icons = array();
	
	public static $defaults = array();
	
	public static function doctype( $docid, $object=false ){
		
		$doctypes = array( 
		
		self::HTML_4_STR => array( "HTML" , "-//W3C//DTD HTML 4.01//EN" , "http://www.w3.org/TR/html4/strict.dtd" ),
		self::HTML_4_TRA => array( "HTML" , "-//W3C//DTD HTML 4.01 Transitional//EN" , "http://www.w3.org/TR/html4/loose.dtd" ),
		self::HTML_4_FRA => array( "HTML" , "-//W3C//DTD HTML 4.01 Frameset//EN" , "http://www.w3.org/TR/html4/frameset.dtd" ),

		self::XHTML_1_STR => array( "HTML" , "-//W3C//DTD XHTML 1.0 Strict//EN" , "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" ),
		self::XHTML_1_TRA => array( "HTML" , "-//W3C//DTD XHTML 1.0 Transitional//EN" , "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" ),
		self::XHTML_1_FRA => array( "HTML" , "-//W3C//DTD XHTML 1.0 Frameset//EN" , "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd" ),

		self::XHTML_1_1 => array( "HTML" , "-//W3C//DTD XHTML 1.1//EN" , "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" ),
		self::XHTML_1_BASIC => array( "HTML" , "-//W3C//DTD XHTML Basic 1.1//EN" , "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd" ),

		self::HTML_5 => array( "HTML" , "" , "" ),

		self::MATHML_1 => array( "MATH" , "" , "http://www.w3.org/Math/DTD/mathml1/mathml.dtd" ),
		self::MATHML_2 => array( "MATH" , "-//W3C//DTD MathML 2.0//EN" , "http://www.w3.org/Math/DTD/mathml2/mathml2.dtd" ),
		
		);
		
		$implementation = new DOMImplementation();
		$dtd = $implementation->createDocumentType( $doctypes[$docid][0], $doctypes[$docid][1], $doctypes[$docid][2] );
		$document = $implementation->createDocument('', '', $dtd);
		
		return ($object)? $document : $document->saveHTML();
		
	}
	
	public static function skeleton( $doctype ){
		
		$doc = self::doctype( $doctype, true );
		$html = $doc->createElement("html");
		$head = $doc->createElement("head");
		$head->setAttribute( "id", "head" );
		$body = $doc->createElement("body");
		$body->setAttribute( "id", "body" );	
		
		$html->appendChild( $head );
		$html->appendChild( $body );
		$doc->appendChild( $html );
		
		return $doc->saveHTML();
		
	}
		
	public static function element( $tag="", array $attributes=array(), $contents="" ){
		
		if( $tag != "" ){
			
			$html = new DOMDocument();
		
			$tag = self::prephtml( $tag, true, true );
		
			$e = $html->createElement($tag);
			
			if( $contents!="" ){
				
				$e->appendChild( $html->importNode( self::content_node( $contents ) , true) );
				
			}
			
			if( !empty($attributes) ){
				foreach( $attributes as $id=>$val ){
					$e->setAttribute($id, $val);
				}
			}
			
			$html->formatOutput = true;
			
			return self::prephtml( $html->saveHTML($e), false );
			
		}
		
		return "";		
		
	}
	
	public static function content_node( $source ){
		
		$source = self::prephtml( $source );
		$html = new DOMDocument();
		$html->preserveWhiteSpace = false;
		@$html->loadHTML( $source );
		$html->formatOutput = true;
		return $html->documentElement->firstChild;	
		
	}
		
	public static function prephtml( $val, $set=true, $tagnameonly=false ){
		
		if( $set ){
			
			if( $tagnameonly ){
				return ( $val == "body" || $val == "p" )? $val."extraname" : $val ;
			}else{
				return preg_replace('/<(\/*)(body|p)(.*?)>/msi', "<$1$2extraname$3>", $val );
			}
			
		}else{
			
			$val = preg_replace('/<(\/*)(body|p)>/msi', "", $val );
			return preg_replace('/<(\/*)(body|p)extraname(.*?)>/msi', "<$1$2$3>", $val );
						
		}
		
	}
	
	public static function has_tag( $tag, $source ){
		
		return preg_match( '/<'.$tag.'(\s|>)/i', $source );		
		
	}
	
	public static function addto( $addthis, $tothis, $id="" ){
		
		$hasdoc = self::has_tag( "!DOCTYPE", $tothis );
		$hashtml = self::has_tag( "html", $tothis );
		
		$tothis = self::prephtml( $tothis );
		
		$html = new DOMDocument();
		$html->preserveWhiteSpace = false;
		@$html->loadHTML( $tothis );
		$html->formatOutput = true;
		
		$addthis = $html->importNode( self::content_node( $addthis ) , true);
		
		if( $id != "" ){
			
			if( $html->getElementById($id) != NULL ){
				$html->getElementById($id)->appendChild( $addthis );
			}
			
			if( $hasdoc && $hashtml ){
				return self::prephtml( $html->saveHTML(), false );
			}else{
				
				$html = $html->saveHTML();
				
				if( !$hashtml ){
					$html = preg_replace('/<(\/*)html(.*?)>/msi', "", $html );
				}
				
				if( !$hasdoc ){
					$html = preg_replace('/<!DOCTYPE(.*?)>/msi', "", $html );
				}
				
				return self::prephtml( $html, false );
				
			}
			
			
		}else{
			
			if( $hasdoc && $hashtml ){
				
				$html->documentElement->firstChild->appendChild( $addthis );
				return self::prephtml( $html->saveHTML(), false );
				
			}else{
				
				if( !$hasdoc && !$hashtml ){
					
					$html->documentElement->firstChild->firstChild->appendChild( $addthis );					
					return self::prephtml( $html->saveHTML(), false );
					
				}else{
					
					if( $hashtml ){
						$html->documentElement->appendChild( $addthis );
					}else{
						$html->documentElement->firstChild->appendChild( $addthis );
					}
					$html = $html->saveHTML();
				
					if( !$hashtml ){
						$html = preg_replace('/<(\/*)html(.*?)>/msi', "", $html );
					}
					
					if( !$hasdoc ){
						$html = preg_replace('/<!DOCTYPE(.*?)>/msi', "", $html );
					}
					
					return self::prephtml( $html, false );
					
				}
				
			}
			
			
			
		}
		
		
		
	}
	
	public static function alter_attributes( $source, $attributes, $id ){
		
		if( $source != "" && $id != "" && is_array($attributes) ){
			
			$notdoc = !self::has_tag( "!DOCTYPE", $source );
			$nothtml = !self::has_tag( "html", $source );
			
			$source = self::prephtml( $source );
			
			$html = new DOMDocument();
			$html->preserveWhiteSpace = false;
			@$html->loadHTML( $source );
			$html->formatOutput = true;
			
			if( $html->getElementById($id) != "" && is_array($attributes) ){
				$changeid = "";
				foreach( $attributes as $attr=>$val ){
					if( strtolower($attr) != "id" ){
						$html->getElementById($id)->setAttribute($attr, $val);
					}else{
						$changeid = $val;
					}
				}
				
				if( $changeid != "" ){
					$html->getElementById($id)->setAttribute("id", $changeid);
				}
				
			}			
			
			if( !$notdoc && !$nothtml ){
				return self::prephtml( $html->saveHTML(), false );
			}else{
				
				$html = $html->saveHTML();
				
				if( $nothtml ){
					$html = preg_replace('/<(\/*)html(.*?)>/msi', "", $html );
				}
				
				if( $notdoc ){
					$html = preg_replace('/<!DOCTYPE(.*?)>/msi', "", $html );
				}
				
				return self::prephtml( $html, false );
				
			}
			
		}
		
		return $source;
		
	}
	
}

?>
