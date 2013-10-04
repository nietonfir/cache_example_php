<?php

/**
 * SocialFollowers implementing class for Twitter
 */
class TwitterFollowers implements SocialFollowers
{
	private $data = null;
	private $url = "";
	private $db = null;
	private $followers = null;

	protected $shareURL = "https://cdn.api.twitter.com/1/urls/count.json?url=";

	public function __construct($db, $url) {
		// initialize the database connection here
		// or use an existing handle
		$this->db = $db;

		// store the url
		$this->url = $url;

		// fetch the record from the database
		$stmt = $this->db->prepare('SELECT * FROM `Followers` WHERE url = :url ORDER BY last_update DESC LIMIT 1');
		$stmt->bindParam(":url", $url);
		$stmt->execute();

		$this->data = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($this->data))
			$this->followers = $this->data["followers"];
	}

	public function getFollowers()
	{
		// create a timestamp that's 30 minutes ago
		// if it's newer than the value from the database -> call the api
		$old = new DateTime();
		$old->sub(new DateInterval("PT30M"));

		if (is_null($this->followers) || (new DateTime($this->data["last_update"]) < $old) ) {
			return $this->retrieveFromAPI();
		}

		return $this->followers;
	}

	private function retrieveFromAPI()
	{
		// mostly untouched
		ob_start();
		$twittershare = $this->shareURL . $this->url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $twittershare);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$jsonstring = curl_exec($ch);
		curl_close($ch);
		$bufferstr = ob_get_contents();
		ob_end_clean();
		$json = json_decode($bufferstr);

		$this->followers = $json->count;

		// store the retrieved values in the database
		$stmt = $this->db->prepare('INSERT INTO Followers (url, data, followers)'
			.'VALUES (:url, :data, :followers)');
		$stmt->execute(array(
			":url" => $this->url,
			":data" => $bufferstr,
			":followers" => $this->followers
		));

		return $this->followers;
	}
}