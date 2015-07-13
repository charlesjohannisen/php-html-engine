<?
/* <!-- copyright */
/*
 * PHP Database Engine
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
/* copyright --> */
class DOC extends HTML{
	
	public static $doc = "";
	public $startedtag = null;
	public $startedid = null;
	
	function __construct( $doctype ){
		self::$doc = self::skeleton($doctype);
		$head = $this;
	}
	
	public function __toString() {		
		return self::$doc;		
	}
		
	public function add_title( $value ){
		$value = self::element( "title", array(), $value );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_description( $value ){
		$value = self::element( "meta", array( 'name'=>"description", 'content'=>$value ) );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_keywords( $value ){
		$value = self::element( "meta", array( 'name'=>"keywords", 'content'=>$value ) );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_meta( $value ){
		$value = self::element( "meta", $value );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_icon( $value, $type='image/x-icon', $rel='shortcut icon' ){
		$value = self::element( "link", array( 'rel'=>$rel, 'href'=>$value, 'type'=>$type ) );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_stylesheet( $value, $type='text/css', $rel='stylesheet' ){
		$value = self::element( "link", array( 'rel'=>$rel, 'href'=>$value, 'type'=>$type ) );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_link( $value ){
		$value = self::element( "link", $value );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_scriptfile( $value ){
		$value = self::element( "script", array( 'src'=>$value ) );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_script( $value, $attributes = array() ){
		$attributes["type"] = "text/javascript";
		$value = self::element( "script", $attributes, str_replace( "<", "__LT__", $value )  );
		$doc = self::addto( $value, self::$doc, "head" );
		self::$doc = str_replace( "__LT__", "<", $doc );
	}
	
	public function add_style( $value, $attributes = array() ){
		$attributes["type"] = "text/css";
		$value = self::element( "style", $attributes, $value  );
		self::$doc = self::addto( $value, self::$doc, "head" );
	}
	
	public function add_attributes( $attributes, $id ){
		self::$doc = self::alter_attributes( self::$doc, $attributes, $id );		
	}
	
	public function add_element( $value, $id="body" ){
		self::$doc = self::addto( $value, self::$doc, $id );
	}
	
}
