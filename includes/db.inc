<?
	$hostName = "localhost";
	$databaseName = "vs_fitness";
	$username = "";
	$password = "";
  
	function showerror()
	{
		die( "Error " . mysql_error() . " : " . mysql_error() );
	}

	function mysqlclean( $array, $index, $maxlength, $connection )
	{
		if( isset( $array["{$index}"] ) )
		{
			$input = substr( $array["{$index}"], 0, $maxlength );
			$input = mysql_real_escape_string( $input, $connection );
			return ( $input );
		}
		return NULL;
	}

	function shellclean( $array, $index, $maxlength )
	{
		if( isset( $array["{$index}"] ) )
		{
			$input = substr( $array["{$index}"], 0, $maxlength );
			$input = EscapeShellArg( $input );
			return ( $input );
		}
		return NULL;
	}
?>
