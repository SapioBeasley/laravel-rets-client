<?php namespace Lara\Rets;

use phRETS, Config;

class Rets {

	protected $rets;
	protected $login_url;
	protected $username;
	protected $password;
	protected $headers;
	protected $debug;
	protected $modified;
	protected $formatQuery;
	public $maxrow;
	public $select;
	public $resource;
	public $query;
	public $limit;
	public $offset;
	public $search;

	public function __construct()
	{
		$this->login_url = Config::get('rets.login_url');
		$this->username  = Config::get('rets.username');
		$this->password  = Config::get('rets.password');
		$this->headers   = Config::get('rets.headers');
		$this->debug     = Config::get('rets.debug');
		$this->limit     = 100;
		$this->offset    = 1;
		$this->client();
	}

	public function client()
	{

		$this->rets = new phRETS;

		//Debug Mode
		if($this->debug)
		{
			$this->rets->SetParam('debug_mode', true);
			$this->rets->SetParam("debug_file", Config::get('rets.debug_log'));
		}

		//Headers
		foreach($this->headers as $header => $resource)
		{
			$this->rets->AddHeader($header,$resource);
		}

		$connect = $this->rets->Connect($this->login_url, $this->username, $this->password);

		//Cannot connect, throw exception
		if( ! $connect )
		{
			$error = $this->getErrors();
			\Log::error('Rets Connector', $error);
			throw new \Exception( $error['text'] );
		}
		return $this;
	}

	public function select( array $attributes = [])
	{
		$this->select['rets'] = implode(',',$attributes);
		return $this;
	}

	/**
	 * Create format rets query
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function query(array $attributes = [])
	{
		foreach($attributes as $key => $resource)
		{
			$this->formatQuery[] = '('.$key.'='.$resource.')';
		}

		$this->query = $this->formatQuery;
		return $this;
	}

	/**
	 * Setup resource and class
	 * @param  [type] $resource [description]
	 * @param  [type] $class    [description]
	 * @return [type]           [description]
	 */
	public function resourceClass( $resource, $class )
	{
		$this->resource['resource'] = $resource;
		$this->resource['class'] = $class;

		return $this;
	}

	public function limit( $limit )
	{
		$this->limit = $limit;
		return $this;
	}

	public function offset( $offset )
	{
		$this->offset = $offset;
		return $this;
	}


	/**
	 * Make
	 * @return [type] [description]
	 */
	public function make()
	{
		//Run search
		$this->search = $this->rets->SearchQuery(
			$this->resource['resource'],
			$this->resource['class'],
			implode(',',$this->query),
			[
				'Select'=> $this->select['rets'],
				'Offset'=> $this->offset,
				'Limit' => $this->limit,
				'Count' => 1
			]);

		$this->offset = ( $this->offset + $this->countRows() );

		$this->results = $this->getRows();

		$this->maxrows = $this->rets->IsMaxrowsReached();

		$this->clearQuery();

		//Retun items
		return $this;
	}

	public function exist()
	{
		if($this->rets->NumRows($this->search) == 0 )
		{
			return false;
		}
		return true;
	}

	public function clearQuery()
	{
		return $this->rets->FreeResult($this->search);
	}

	public function getRows()
	{
		$rec = [];
		while ( $record = $this->rets->FetchRow($this->search) )
		{
             $rec[] = $record;
        }

		return $rec;
	}

	public function countRows()
	{
		return $this->rets->NumRows();
	}

	public function countTotalRecords()
	{
		return $this->rets->TotalRecordsFound($this->search);
	}

	public function getErrors()
	{
		return $this->rets->Error();
	}

}
