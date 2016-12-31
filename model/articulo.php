<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2013-2016  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'plugins/facturacion_base/model/core/articulo.php';

/**
 * Albarán de proveedor o albarán de compra. Representa la recepción
 * de un material que se ha comprado. Implica la entrada de ese material
 * al almacén.
 *
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class articulo extends FacturaScripts\model\articulo
{
	 public function all_publico($offset=0, $limit=FS_ITEM_LIMIT)
   {
      $artilist = array();
      $sql = "SELECT * FROM ".$this->table_name
              ." WHERE publico ORDER BY lower(referencia) ASC";

      $data = $this->db->select_limit($sql, $limit, $offset);
      if($data)
      {
         foreach($data as $d)
         {
            $artilist[] = new \articulo($d);
         }
      }

      return $artilist;
   }

   public function url_public()
   {
      if( is_null($this->referencia) )
      {
         return "index.php?page=ventas_tienda_articulos";
      }
      else
         return "index.php?page=ventas_tienda_articulos&ref=".urlencode($this->referencia);
   }

   public function imagen_url_public()
   {
      if( file_exists('images/articulos/'.$this->image_ref().'-1.png') )
      {
         return 'images/articulos/'.$this->image_ref().'-1.png';
      }
      else if( file_exists('images/articulos/'.$this->image_ref().'-1.jpg') )
      {
         return 'images/articulos/'.$this->image_ref().'-1.jpg';
      }
      else
         return FALSE;
   }

	 public function all_publico_ajax($offset=0, $limit=FS_ITEM_LIMIT)
   {
      $artilist = array();
      $sql = "SELECT * FROM ".$this->table_name
              ." WHERE publico ORDER BY lower(referencia) ASC";

      $data = $this->db->select_limit($sql, $limit, $offset);
      if($data)
      {
         foreach($data as $d)
         {
            $artilist[] = new \articulo($d);
         }
      }

			echo json_encode($artilist);
   }

}
