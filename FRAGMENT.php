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
class FRAGMENT extends HTML{
	
	public $__config = array();
	public $__id = -1;
	public $__lastid = 0;
	
	public function __construct(){
	}
	
	public function __call( $name, $args ){
		
		$attributes = array();
		$addto = null;
		$id = null;
		$content = "";
		
		if( count($args) > 0 ){
			foreach( $args as $arg ){
				if( !ctype_digit( $arg ) && !is_array( $arg ) && $this->startswith( trim( $arg ), "{" ) ){
					$attributes = json_decode( $arg, true );
				}elseif( is_int( $arg ) && $arg > 0 ){
					$id = $arg;
				}elseif( is_array( $arg ) && count( $arg ) == 1 ){
					$addto = $arg[0];
				}
			}
		}
		
		if( $id === $addto ){
			$id = null;
		}
		
		$element = array( "id"=>$id, "name"=>$name, "attributes"=>$attributes, "content"=>$content, "addto"=>$this->parent_id( $addto ) );
		$this->__id++;
		
		
		if( $this->__id == 0 ){
			
			$element['id'] = 0;
			$element['addto'] = -1;
			
		}
		
		$this->__config[$this->__id] = $element;
		return $this;
		
	}
	
	public function parent_id( $id ){
		
		$__id = $this->__id;
		foreach( $this->__config as $parentid=>$value ){
			if( is_int($value["id"]) && $id === $value["id"] ){
				$__id = $parentid;
			}
		}
		return $__id;
		
	}
	
	public function find_id( $id ){
		
		foreach( $this->__config as $findid=>$value ){
			if( $id == $value["id"] ){
				return true;
			}
		}
		return false;
		
	}
	
	public function startswith( $str, $prefix ){
		return strpos( $str, $prefix ) === 0;
	}
	
	public function content( $content ){
		$this->__config[$this->__id]['content'] = $content;
		return $this;
	}
	
	public function render(){
		
		$__list = array();
		
		foreach( $this->__config as $id=>$value ){
			$__list[] = $value['addto'];
		}
		
		$__list = array_unique($__list);
		arsort($__list);
		$__list = array_values($__list);
		return $this->render_r( $__list, 0 );		
		
	}
	
	private function render_r( $list, $currentid ){
		
		$contents = array();
		$parent = $list[$currentid];
		
		foreach( $this->__config as $id=>$element ){
			
			if( $element['addto'] == $parent ){
				$contents[] = isset($element['rendered']) ? $element['rendered'] : self::element( $element['name'], $element['attributes'], $element['content'] );
			}
			
		}
		
		$this->__config[$parent]['rendered'] = self::element( $this->__config[$parent]['name'], $this->__config[$parent]['attributes'], implode( "\n", $contents )."\n".$this->__config[$parent]['content'] );
		
		if( $parent == 0 ){
			return $this->__config[$parent]['rendered'];
		}else{
			$currentid++;
			return $this->render_r( $list, $currentid );
		}
		
		
	}
	
}
