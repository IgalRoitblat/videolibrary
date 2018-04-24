<?php

class Clip
{
	private $title;
	private $src;
	private $comments;

	
	function __construct($title, $src, $comments)
	{
		$this->title = $title;
		$this->src = $src;
		$this->comments = $comments;
	}

	public static function getClips($query) {
			$stmt = DB::getConnection()->prepare("
			SELECT * FROM clips
			WHERE title  OR comments LIKE :searchTerm
			   ;");

			$searchTerm = '%'. $query . '%';
			$stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
			$stmt->execute();

			$result = $stmt->fetchAll();
			return $result;
	}

	public static function getOne($id) {
			$stmt = DB::getConnection()->prepare("
			SELECT * FROM clips WHERE id = :id;");

			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();

			$result = $stmt->fetch();
			return $result;
	}

}