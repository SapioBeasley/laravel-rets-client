## RETS client for Laravel

###This package is DEPRECATED, please check out [PHRETS](https://github.com/troydavisson/PHRETS)

RETS client for Laravel using *PHRETS*.



	$select = ['ListingKey','ModificationTimestamp'];

	$query = [
		'ListingKey' => '0+',
		'ModificationTimestamp' => '2014-02-20T01:00:00+'
	];

	$client = Rets::resourceClass('Property','RES')
					->select($select)
					->query($query)
					->limit(10)
					->make();

	$records = [];

	if ( $client->exist() )
	{
		foreach($client->results as $row)
		{
			$records[] = $row;
		}

	}

	dd($recorods)
