<?php

namespace ExampleAPI;

/**
* Utility class with various useful functions
*/
class Utility
{
	/**
	* Generates database LIMIT and OFFSET statement using URL parameters
	* @param string $tLimitVariable (optional) Expected name of URL parameter for the limit
	* @param string $tOffsetVariable (optional) Expected name of URL parameter for the offset
	* @param int $tDefaultLimit (optional) Default limit, set to 500
	* @return string Formatted query segment
	*/
	public static function BuildLimitOffset( $tLimitVariable = "limit", $tOffsetVariable = "offset", $tDefaultLimit = 500 )
	{
		$tempQuery = "LIMIT " . ( ( empty( $tLimitVariable ) || !isset( $_GET[ $tLimitVariable ] ) ) ? $tDefaultLimit : (int)$_GET[ $tLimitVariable ] );
		
		if ( !empty( $tOffsetVariable ) && isset( $_GET[ $tOffsetVariable ] ) )
		{
			$tempQuery .= " OFFSET " . (int)$_GET[ $tOffsetVariable ];
		}
		
		return $tempQuery;
	}
	
	/**
	* Generates SELECT integer variables query using URL parameters
	* @param string[] $tVariables Expected variable name(s) of URL parameter for the variable
	*/
	public static function BuildIntegerConditions( $tVariables )
	{
		if ( $tVariables != null )
		{
			$tempConditions = null;
			$tempVariable = null;
			$tempValue = null;
			for ( $i = ( count( $tVariables ) - 1 ); $i >= 0; --$i )
			{
				$tempVariable = $tVariables[$i];
				if ( isset( $_GET[ $tempVariable ] ) )
				{
					$tempValue = $_GET[ $tempVariable ];
					if ( is_numeric( $tempValue ) )
					{
						if ( $tempConditions == null )
						{
							$tempConditions = "";
						}
						else
						{
							$tempConditions .= " AND ";
						}

						$tempConditions .= $tempVariable . "=" . (int)$tempValue;
					}
				}
			}
			
			return $tempConditions;
		}
		
		return null;
	}
}

?>