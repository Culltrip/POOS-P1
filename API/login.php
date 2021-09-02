<?php

    $inData = getRequestInfo();

    $conn = new mysqli("localhost", "ChiefHenny", "WeLoveCOP4331", "ContactTracing"); 	
	if( $conn->connect_error )
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT UserID, FirstName, LastName FROM Users WHERE Login=? and Password=?");
		$stmt->bind_param("ss", $inData["Login"], $inData["Password"]);
		$stmt->execute();
		$result = $stmt->get_result();

		if($line = $result->fetch_assoc())
		{
			formatReturn($line['UserID'], $line['FirstName'], $line['LastName']);
		}
		else
		{
			returnWithError("Invalid Login Attempt");
		}
	

		$stmt->close();
		$conn->close();
	}

	function formatReturn($first, $last, $id)
	{
		$toReturn = '{"id":"' . $id . '","firstName":"' . $first . '","lastName":"' . $last . '"}';
		sendResultInfoAsJson($toReturn);
	}

    function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

    function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

?>