var xmlhttpObj = createXMLHttpRequest();
var startZoom = 17;
var map;
var address = '';
/*********************** XML Request/Respons Functions ************************************/
function createXMLHttpRequest()
{
	var request = false;

	/* Does this browser support the XMLHttpRequest object? ie Not IE */
	if( window.XMLHttpRequest )
	{
		if( typeof XMLHttpRequest != 'undefined' )
		{
			/* Try to create a new XMLHttpRequest object */
			try
			{
				request = new XMLHttpRequest();
			}
			catch (e)
			{
				request = false;
			}
		}
	}
	/* Does this browser support ActiveX objects, ie IE */
	else if( window.ActiveXObject )
	{
		/* Try to create a new ActiveX XMLHttp object */
		try
		{
			request = new ActiveXObject( 'Msxml2.XMLHTTP' );
		}
		catch (e)
		{
			request = false;
		}
	}

	return request;
}

function requestData( p_request, p_URL, p_data, p_func )
{
	/* See if the XMLHttpRequest Object actually exists */
	if( p_request )
	{
		p_request.open( 'GET', p_URL + '?' + p_data, true );
		p_request.onreadystatechange = p_func;
		p_request.send();
	}
	else
		alert( "Errror attempting to pass information to the server!" );
}
/*********************** End XML Request/Respons Functions ************************************/
function init()
{
	requestData( xmlhttpObj, 'savedsearches.php', 'request=getAddress', fillAddress );
}

function newRoute()
{
	var dataPoints = new Array();
	var distMi = 0.0;
	var distKm = 0.0;

	if(GBrowserIsCompatible())
	{
		map = new GMap2(document.getElementById("map"));
		var geocoder = new GClientGeocoder();
		var mapControl = new GMapTypeControl();

		map.removeMapType(G_HYBRID_MAP);
		map.removeMapType(G_SATELLITE_MAP);
		map.addControl(mapControl);
		map.addControl(new GLargeMapControl());

		geocoder.getLatLng(address,
		function (point)
		{
			//if (!point)
			//{
			//	alert(address + " not found!");
			//}
			//else
			//{
				map.setCenter(point, startZoom);
			//}
		}
		);

		//allow the user to click on the map to start a new route.
		GEvent.addListener(map, "click",
		function(overlay, latlng)
		{
			dataPoints.push(latlng);
			var marker = new GMarker(latlng)
			map.addOverlay(marker);
			
			// calc the total distance
			for( var i=0; i<dataPoints.length; i++)
			{
				if( i!=0 && dataPoints.length !=1 )
				{
					distMi = distMi + dataPoints[i-1].distanceFrom(dataPoints[i], 3959).toFixed(1);
					alert( 'Miles: ' + distMi );
				}
			}
		}
		);
	}
}

function fillAddress()
{
	/* Is the /readyState/ 4? */
	if( xmlhttpObj.readyState == 4 )
	{
		/*Is the /status/ 200? */
		if( xmlhttpObj.status == 200 )
		{
			/* Grab the /responseText/ from the request Object */
			var response = xmlhttpObj.responseXML;
			var response_XML = response.getElementsByTagName('param')
			for(var i=0; i<response_XML.length; i++)
				address += response_XML[i].firstChild.nodeValue + ' ';
		}
		else
			alert( 'There was a problem retrieving the data: \n' + xmlhttpObj.statusText );

		xmlhttpObj = null;
	}
	newRoute();
}
window.onload=init;
window.onunload = GUnload;