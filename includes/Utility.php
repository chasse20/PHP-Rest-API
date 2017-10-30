<?php

namespace ExampleAPI;

/**
* Utility class with various useful functions
*/
class Utility
{
	/**
	* Generates SQL-formatted array of values
	* @param mysqli $tConnection SQL Connection used for injection checks
	* @param array $tArray Array of values to ingest
	* @return string SQL-formatted string
	*/
	public static function SQLArray( $tConnection, $tArray )
	{
		$i = 0;
		$tempValues = "(" . $tConnection->escape_string( $tArray[$i] );
		++$i;
		
		$tempListLength = count( $tArray );
		for ( ; $i < $tempListLength; ++$i )
		{
			$tempValues .= "," . $tConnection->escape_string( $tArray[$i] );
		}
		
		return $tempValues . ")";
	}
	
	/**
	* Generates SQL query for multiple inserts
	* @param mysqli $tConnection SQL Connection used for injection checks
	* @param stdclass[] $tArray Array of key-value pairs to ingest
	* @param string $tTable Table name to insert into
	* @return string SQL multi-query
	*/
	public static function SQLInsertArray( $tConnection, $tArray, $tTable )
	{
		$tempQuery = "";
		
		$tempListLength = count( $tArray );
		if ( $tempListLength > 1 )
		{
			$tempQuery .= "INSERT INTO " . $tTable . " " . Utility::SQLKVPs( $tConnection, $tArray[0] ) . ";";

			for ( $i = 1; $i < $tempListLength; ++$i )
			{
				$tempQuery .= " INSERT INTO " . $tTable . " " . Utility::SQLKVPs( $tConnection, $tArray[$i] ) . ";";
			}
		}
		
		return $tempQuery;
	}
	
	/**
	* Generates SQL-formatted array of key-value pairs
	* @param mysqli $tConnection SQL Connection used for injection checks
	* @param stdclass $tData Key-value container
	* @return string SQL-formatted string
	*/
	public static function SQLKVPs( $tConnection, $tData )
	{
		$tempKeys = "";
		$tempValues = "";
		$tempIsComma = false;
		foreach ( $tData as $tempKey => $tempValue )
		{
			if ( $tempIsComma )
			{
				$tempKeys .= ",";
				$tempValues .= ",";
			}
			else
			{
				$tempIsComma = true;
			}
			
			$tempKeys .= $tConnection->escape_string( $tempKey );
			$tempValues .= "\"" . $tConnection->escape_string( $tempValue ) . "\"";
		}
		
		return "(" . $tempKeys . ") VALUES (" . $tempValues . ")";
	}
	
	/**
	* Generates SQL query for multiple updates
	* @param mysqli $tConnection SQL Connection used for injection checks
	* @param stdclass[] $tArray Array of key-value pairs to ingest
	* @param string $tTable Table name to update into
	* @param string $tIDColumn ID column name to update into
	* @return string SQL multi-query
	*/
	public static function SQLUpdateArray( $tConnection, $tArray, $tTable, $tIDColumn )
	{
		$tempQuery = "";
		
		$tempListLength = count( $tArray );
		if ( $tempListLength > 1 )
		{
			if ( isset( $tArray[0]->{ $tIDColumn } ) )
			{
				$tempID = $tArray[0]->{ $tIDColumn };
				unset( $tArray[0]->{ $tIDColumn } );
				$tempQuery .= "UPDATE " . $tTable . " " . Utility::SQLSet( $tConnection, $tArray[0] ) . " WHERE " . $tIDColumn . "=" . $tempID . ";";
			}

			for ( $i = 1; $i < $tempListLength; ++$i )
			{
				if ( isset( $tArray[$i]->{ $tIDColumn } ) )
				{
					$tempID = $tArray[$i]->{ $tIDColumn };
					unset( $tArray[$i]->{ $tIDColumn } );
					$tempQuery .= "UPDATE " . $tTable . " " . Utility::SQLSet( $tConnection, $tArray[$i] ) . " WHERE " . $tIDColumn . "=" . $tempID . ";";
				}
			}
		}
		
		return $tempQuery;
	}
	
	/**
	* Generates SQL-formatted array of key-value setters
	* @param mysqli $tConnection SQL Connection used for injection checks
	* @param stdclass $tData Key-value container
	* @return string SQL-formatted string
	*/
	public static function SQLSet( $tConnection, $tData )
	{
		$tempSet = "";
		
		$tempIsComma = false;
		foreach ( $tData as $tempKey => $tempValue )
		{
			if ( $tempIsComma )
			{
				$tempSet .= ",";
			}
			else
			{
				$tempIsComma = true;
			}
			
			$tempSet .= $tConnection->escape_string( $tempKey ) . "='" . $tConnection->escape_string( $tempValue ) . "'";
		}
		
		return "SET " . $tempSet;
	}
}

?>