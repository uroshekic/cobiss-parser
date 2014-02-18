<?php
/*
 * Httpful requires curl enabled! (php_curl extension)
 * If curl doesn't work:
 * 		http://stackoverflow.com/questions/10939248/php-curl-not-working-wamp-on-windows-7-64-bit
 * 		http://www.anindya.com/php-5-4-3-and-php-5-3-13-x64-64-bit-for-windows/
 *
 * Also PHP 5.4+ is required for short array syntax support.
 */

include('./httpful.phar');

/*
 * Cobiss Parser
 */
class Cobiss
{
	private $cookie;
	private $server_digit;
	private $session_id;

	private $userAgent;
	private $libraryId;
	
	private $prepared = false;

	public $userAgents = [
		'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:26.0) Gecko/20100101 Firefox/26.0',
		'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36',
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.107 Safari/537.36',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/537.36 (KHTML, like Gecko)',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0',
		'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', // WATCH OUT WE GOT A BADASS OVER HERE!
		'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)',
		'Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405'
	];
	public $libraryIds = [ // Keys mustn't be numeric (is_numeric) !
		'Ptuj' => '50360',
		'Ormoz' => '50395',
		'Kranj' => '50250',
		'FMF' => '50028',
		'CTK' => '50002',
		'NUK' => '',
	];

	public $debug = false;

	/*
	 *
	 */
	public function __construct($libraryId = null)
	{
		$this->libraryId = $libraryId === null ? $this->libraryIds['FMF'] : (is_numeric($libraryId) ? $libraryId : $this->libraryIds[$libraryId]);

		// Select a random user agent
		// Fajn bi bilo, če user agenta pošljemo še kje drugje kot v tej funkciji
		$rand = mt_rand(0, count($this->userAgents) - 1);
		$this->userAgent = $this->userAgents[$rand];

		// Let's visit the main page to extract server_digit and session_id
		$uri = 'http://www.cobiss.si/scripts/cobiss?ukaz=getid&lani=si';
		$response = \Httpful\Request::get($uri)->addHeader('User-agent:', $this->userAgent)->send();
		//var_dump($response);
		//print $response->raw_body;

		$p = <<<EOT
	|a href="http://cobiss(\d*?)\.izum\.si/scripts/cobiss\?ukaz=SFRM&amp;id=(\d*?)" title="Iskanje"|
EOT;
		$matches = [];
		preg_match_all($p, $response->body, $matches);
		//var_dump($matches);
		if (false) die('SESSION_ERROR');
		$this->server_digit = $matches[1][0];
		$this->session_id = $matches[2][0];
		//var_dump($this->session_id, $this->server_digit);

		$matches = [];
		preg_match_all("/Set-Cookie: (.*)/", $response->raw_headers, $matches);
		//if ($matches != []) var_dump($matches);
		$this->cookie = $matches[1][0];
		//var_dump($this->cookie);
	}

	/*
	 *
	 */
	public function prepare()
	{
		// Visit to the library page
		$uri = 'http://cobiss' . $this->server_digit . '.izum.si/scripts/cobiss?ukaz=BASE&bno=' . $this->libraryId . '&id=' . $this->session_id;
		$request = \Httpful\Request::get($uri)->addHeader('User-agent:', $this->userAgent)->addHeader('Set-Cookie', $this->cookie);
		$response = $request->send();
		//var_dump($response);
		//print($response->raw_body);

		// Select 'Osnovno iskanje' tab
		$uri = 'http://cobiss' . $this->server_digit . '.izum.si/scripts/cobiss?ukaz=SFRM&mode=7&id=' . $this->session_id;
		$response = \Httpful\Request::get($uri)->addHeader('User-agent:', $this->userAgent)->addHeader('Set-Cookie', $this->cookie)->send();

		$this->prepared = true;
	}

	/*
	 *
	 */
	public function search($query)
	{
		if (!$this->prepared) $this->prepare();
		//$this->prepared = false; // Cobiss does not require us to prepare() to search again

		// Search
		$uri = 'http://cobiss' . $this->server_digit . '.izum.si/scripts/cobiss?id=' . $this->session_id;
		$data = [
			'ukaz' => 'SEAR',
			'ID' => $this->session_id,
			'keysbm' => '',
			'mat' => '66', // Izbor gradiva: 51 - vse gradivo (tudi e-viri), 66 - knjige
			'lan' => '',
			'ss1' => $query,
			'find' => 'isci'
		];
		$data = http_build_query($data);
		$request = \Httpful\Request::post($uri, $data)->addHeader('User-agent:', $this->userAgent)->addHeader('Set-Cookie', $this->cookie);
		$response = $request->send();
		//var_dump($response);
		//var_dump($response->headers);
		//print $response->raw_body;

		$p = <<<EOT
			|<div class="left">Število najdenih zapisov:&nbsp;<b>(\d*)</b></div>|
EOT;
		$matches = [];
		preg_match_all($p, $response->raw_body, $matches);
		//var_dump($matches);

		if (count($matches[0]) == 0) {
			if (preg_match("|zapis \[1/1\]|", $response->raw_body)) { // Exact match!
				return [ 'nrResults' => -1, 'results' => $response->raw_body ]; // -1 means an exact match and we need to treat it differently
			} else if (preg_match("|Število najdenih zapisov: 0|", $response->raw_body)) { // Pointless (because of the else statement)
				return [ 'nrResults' => 0, 'results' => [] ];
			} else {
				return [ 'nrResults' => 0, 'results' => [] ];
			}
		}

		$nrResults = $matches[1][0];
		if ($this->debug) print 'Število najdenih rezultatov: <b>' . $nrResults . '</b>';

		// Parse rows
		$p = '|<tr>.*?</tr>|s';
		$matches = [];
		preg_match_all($p, $response->raw_body, $matches);
		//var_dump($matches);
		if (count($matches[0]) <= 2) return [ 'nrResults' => 0, 'results' => [] ];

		$results = array_slice($matches[0], 2);
		if ($this->debug) var_dump($results);

		return [ 'nrResults' => $nrResults, 'results' => $results ];
	}

	/*
	 *
	 */
	public static function parse($response) {
		$results = $response['results'];
		if ($response['nrResults'] > 0) {
			// A list of matches
			$data = [];
			foreach ($results as $r) $data[] = Cobiss::parseResult($r);
			return $data;
		} else if ($response['nrResults'] == -1) {
			// Exact match
			$result = Cobiss::parseExactMatch($results);
			return [ $result ];
		} else {
			// No matches
			return [];
		}
	}


	/* --- Multiple results --- */

	/*
	 *
	 */
	public static function parseResult($result)
	{
		$p = "|<td.*?>(.*?)</td>|s";
		$matches = [];
		preg_match_all($p, $result, $matches);

		if (count($matches[0]) != 10) return [];

		$author = $matches[1][3];
		$titleString = $matches[1][4];
		$genreString = $matches[1][5];
		$lang = $matches[1][6];
		$year = $matches[1][7];
		$isbnString = $matches[1][9];

		$parsedTitle = self::parseTitle($titleString);
		$title = $parsedTitle['title'];
		$titleURL = $parsedTitle['titleURL'];

		$isbn = self::parseISBN($isbnString);

		$genre = self::parseGenre($genreString);
		
		return [ 
			'author' => $author, 'title' => $title, 
			'genre' => $genre, 'lang' => $lang, 'year' => $year, 
			'isbn' => $isbn, 'titleUrl' => $titleURL
		];
	}

	/*
	 *
	 */
	public static function parseTitle($subject)
	{
		$titleP = '/<a href="(.*?)">(.*?)<\/a>/';
		$titleMatches = [];
		preg_match_all($titleP, $subject, $titleMatches);
		$title = $titleMatches[2][0];
		$titleURL = $titleMatches[1][0];
		return [ 'title' => $title, 'titleURL' => $titleURL ];
	}

	/*
	 *
	 */
	public static function parseISBN($subject)
	{
		$isbnP = '|rft\.isbn=([0-9x\-]*)&|i';
		$isbnMatches = [];
		preg_match_all($isbnP, $subject, $isbnMatches);
		if (count($isbnMatches[1]) > 0) {
			return $isbnMatches[1][0];
		}
		return '';
	}

	/*
	 *
	 */
	public static function parseGenre($subject)
	{
		$genreP = '|<.*?> *(.*)|';
		$genreMatches = [];
		preg_match_all($genreP, $subject, $genreMatches);
		return $genreMatches[1][0];
	}


	/* --- Exact Match --- */

	/*
	 *
	 */
	public static function parseExactMatch($result)
	{
		$p = "|<th.*?>(.*?)</th><td.*?>(.*?)</td>|s";
		$matches = [];
		preg_match_all($p, $result, $matches);

		//var_dump($matches);

		$authorString = self::pEMFindAttribute('Avtor', $matches);
		$titleString = self::pEMFindAttribute('Naslov', $matches);
		$genre = self::pEMFindAttribute('Vrsta/vsebina', $matches);
		$lang = self::pEMFindAttribute('Jezik', $matches);
		$year = self::pEMFindAttribute('Leto', $matches);
		$isbnString = self::pEMFindAttribute('ISBN', $matches);
		$additional = self::pEMFindAttribute('Založništvo in izdelava', $matches);
		$collectionOrig = self::pEMFindAttribute('Zbirka', $matches);

		$author = self::pEMAuthor($authorString);
		$title = self::pEMTitle($titleString);
		$genre = self::parseGenre($genre);
		$isbn = self::pEMISBN($isbnString);
		$collection = self::pEMCollection($collectionOrig);

		$additional = self::pEMAdditional($additional);

		return array_merge([ 
			'author' => $author, 'title' => $title, 
			'genre' => $genre, 'lang' => $lang, 'year' => $year, 
			'collection' => $collection, 'collectionOrig' => $collectionOrig, 
			'isbn' => $isbn, 
		], $additional);
	}

	/*
	 *
	 *
	 * pEM = parseExactMatch
	 */
	public static function pEMAuthor($subject)
	{
		$p = '|<a .*?>(.*?)</a>|';
		$matches = [];
		preg_match_all($p, $subject, $matches);
		return $matches[1][0];
	}

	/*
	 *
	 */
	public static function pEMTitle($title)
	{
		/*$i = strpos($title, ' :');
		return substr($title, 0, $i);*/
		$p = '| *(.*?) [/;:]|';
		$matches = [];
		preg_match_all($p, $title, $matches);
		return $matches[1][0];
	}

	/*
	 *
	 */
	public static function pEMAdditional($subject)
	{
		$p = '|(.*?) : (.*?), (\d*)|';
		$matches = [];
		preg_match_all($p, $subject, $matches);
		//var_dump($matches);
		return [ 'publisher' => $matches[2][0], 'publishedYear' => $matches[3][0], 'publishedCity' => $matches[1][0] ];
	}

	/*
	 *
	 */
	public static function pEMISBN($isbn)
	{
		$i = strpos($isbn, ' :');
		if ($i !== false) $isbn = substr($isbn, 0, $i);
		return explode(" ", $isbn)[1];
	}

	/*
	 *
	 */
	public static function pEMCollection($subject)
	{
		$p = '|Zbirka (.*?) /|';
		$matches = [];
		preg_match_all($p, $subject, $matches);
		if (count($matches[0]) > 0) return $matches[1][0];
		return $subject;
	}

	/*
	 *
	 */
	public static function pEMFindAttribute($attr, $matches)
	{
		foreach ($matches[1] as $i => $a) {
			if ($a == $attr) {
				return $matches[2][$i];
			}
		}
		return '';
	}
}