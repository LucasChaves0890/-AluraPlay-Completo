<?php

declare(strict_types=1);

namespace Repository;

use Entity\Video;
use PDO;

class VideoRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Video $video): bool
    {
        $sql = 'INSERT INTO videos (url, title, img) VALUES (?, ?, ?)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $video->url);
        $statement->bindValue(2, $video->title);
        $statement->bindValue(3, $video->getFilePath());

        $result = $statement->execute();
        $id = $this->pdo->lastInsertId();

        $video->setId(intval($id));

        return $result;
    }

    public function remove(int $id): bool
    {
        $sql = 'DELETE FROM videos WHERE id = ?';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);

        return $statement->execute();
    }

    public function removeCover(int $id): bool
    {
        $sqlSelect = 'SELECT img FROM videos WHERE id = ?';
        $statementSelect = $this->pdo->prepare($sqlSelect);
        $statementSelect->bindValue(1, $id);
        $statementSelect->execute();

        $resultado = $statementSelect->fetch(PDO::FETCH_ASSOC);
        $caminhoRelativo = $resultado['img'];

        $caminhoAbsoluto = $_SERVER['DOCUMENT_ROOT'] . '/img/uploads/';
        $caminhoCompleto = $caminhoAbsoluto . $caminhoRelativo;
    
        if (file_exists($caminhoCompleto)) {
            if (unlink($caminhoCompleto)) {
                $sqlUpdate = 'UPDATE videos SET img = NULL WHERE id = ?';
                $statementUpdate = $this->pdo->prepare($sqlUpdate);
                $statementUpdate->bindValue(1, $id);
                $resultUpdate = $statementUpdate->execute();

                return $resultUpdate;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update(Video $video): bool
    {
        $updateImageSql = '';
        if ($video->getFilePath() !== null) {
            $updateImageSql = ', img = :img';
        }
        $sql = "UPDATE videos SET
                  url = :url,
                  title = :title
                $updateImageSql
              WHERE id = :id;";
        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':url', $video->url);
        $statement->bindValue(':title', $video->title);
        $statement->bindValue(':id', $video->id, PDO::PARAM_INT);
        
        if ($video->getFilePath() !== null) {
            $this->removeCover($video->id);
            $statement->bindValue(':img', $video->getFilePath());
        }

        return $statement->execute();
    }

    /**
     * @return Video[]
     */
    public function all(): array
    {
        $videoList = $this->pdo
            ->query('SELECT * FROM videos;')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            $this->hydrateVideo(...),
            $videoList
        );
    }

    public function find(int $id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM videos WHERE id = ?;');
        $statement->bindValue(1, $id, \PDO::PARAM_INT);
        $statement->execute();

        return $this->hydrateVideo($statement->fetch(\PDO::FETCH_ASSOC));
    }

    private function hydrateVideo(array $videoData): Video
    {
        $video = new Video($videoData['url'], $videoData['title']);
        $video->setId($videoData['id']);

        if ($videoData['img'] !== null) {
            $video->setFilePath($videoData['img']);
        }

        return $video;
    }
}
