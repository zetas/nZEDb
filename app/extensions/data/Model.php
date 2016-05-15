<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program (see LICENSE.txt in the base directory.  If
 * not, see:
 *
 * @link      <http://www.gnu.org/licenses/>.
 * @author    niel
 * @copyright 2016 nZEDb
 */
namespace app\extensions\data;


/**
 * Class Model - extends the base Model class to add methods for the models to import/export
 * their data easily. These may need to be moved to somewhere more appropriate for SQL specific
 * metohds (i.e. adapter or Database classes).
 *
*@package app\extensions\data
 */
class Model extends \lithium\data\Model
{
	/**
	 * @var array Default options for use with SELECT INTO OUTFILE.
	 */
	protected $outfileOptions = [
		'enclosedby' => '',
		'fields'     => '\t',
		'limit'      => 0,
		'lines'      => '\r\n', // use Windows style endings so that text can contain \n
		'path'       => null,
	];

	public function exportTable(array $options = [])
	{
		$defaults = [];
		$options += $defaults;
	}

	public function importTable(array $options = [])
	{
	}
}
